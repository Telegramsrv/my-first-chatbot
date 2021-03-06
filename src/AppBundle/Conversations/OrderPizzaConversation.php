<?php

namespace AppBundle\Conversations;

use AppBundle\Entity\Address;
use AppBundle\Entity\Category;
use AppBundle\Entity\Customer;
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Pizza;
use AppBundle\Entity\Uf;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use BotMan\Drivers\Facebook\Extensions\ReceiptAddress;
use BotMan\Drivers\Facebook\Extensions\ReceiptElement;
use BotMan\Drivers\Facebook\Extensions\ReceiptSummary;
use BotMan\Drivers\Facebook\Extensions\ReceiptTemplate;
use Symfony\Component\Validator\Constraints\Email;

class OrderPizzaConversation extends BaseConversation
{
    const ORDER_SELECT_PIZZA_FINISHED = 'order_select_pizza_finished';
    const ORDER_PIZZA_ADD_ITEM = 'order_pizza_add_item_';
    /**
     * @var int
     */
    protected $categoryId;

    /**
     * @var Orders
     */
    protected $order;

    /**
     * @var OrderItems
     */
    protected $currentOrdemItem;

    /**
     * @var Address
     */
    protected $address;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * OrderPizzaConversation constructor.
     *
     */
    public function __construct()
    {
        $this->order = new Orders();
        $this->address = new Address();
        $this->customer = new Customer();
    }

    public function run()
    {
        $this->setUserInformation();
        $this->askOrderCategory();
    }

    private function setUserInformation()
    {
        global $kernel;

        $user = $this->bot->getUser();

        $customer = $kernel->getContainer()->get('doctrine')->getRepository(Customer::class)
            ->findOneBy(['fbId' => $user->getId()]);

        if ($customer) {
            $this->customer = $customer;
        } else {
            $this->customer
                ->setFirstName($user->getFirstName())
                ->setLastName($user->getLastName())
                ->setFbId($user->getId());
        }

        $this->order->setCustomer($this->customer);
    }

    private function userAlreadyRegistered()
    {
        return !empty($this->customer->getId()) ? true : false;
    }

    public function askOrderCategory($selectPizzaFinished = false)
    {
        global $kernel;

        $categories = $kernel->getContainer()->get('doctrine')->getRepository(Category::class)
            ->findAll();

        if ($selectPizzaFinished) {
            $question = Question::create(
                "Para escolher outra pizza, escolha entre as categorias abaixo ou clique em finalizar."
            )
                ->addButton(
                    Button::create('Finalizar Pedido')->value(self::ORDER_SELECT_PIZZA_FINISHED)
                );
        } else {
            $question = Question::create("Escolha entre as categorias abaixo:");
        }

        foreach ($categories as $category) {
            $question->addButton(
                Button::create($category->getDescription())->value($category->getId())
            );
        }

        $this->ask(
            $question,
            function (Answer $answer) use ($categories, $question) {
                if ($answer->isInteractiveMessageReply()) {

                    if ($answer->getValue() == self::ORDER_SELECT_PIZZA_FINISHED) {
                        $this->selectPizzaFinished();
                    } else {
                        $this->categoryId = $answer->getValue();
                        $this->say('Ok, ' . $answer->getText() . ' selecionado.');
                        $this->askPizzaItem();
                    }
                }
                /*else if (!empty($answer->getText())) {

                    if ($answer->getValue() == self::ORDER_SELECT_PIZZA_FINISHED) {
                        $this->selectPizzaFinished();
                    } else {
                        $validCategory = false;

                        foreach ($categories as $category) {
                            if (strtolower($answer->getText()) == strtolower($category->getDescription())) {
                                $validCategory = true;
                                $this->categoryId = $category->getId();
                            }
                        }

                        if (!$validCategory) {
                            $this->say('Categoria não encontrada!');
                            $this->repeat($question);
                        } else {
                            $this->askPizzaItem();
                        }
                    }
                }*/
            }
        );
    }

    public function askPizzaItem()
    {
        global $kernel;

        $pizzas = $kernel->getContainer()->get('doctrine')
            ->getRepository(Pizza::class)->findByCategoryId($this->categoryId);

        if (!empty($pizzas)) {

            $generenicTemplate = GenericTemplate::create()->addImageAspectRatio(GenericTemplate::RATIO_HORIZONTAL);

            /** @var Pizza $pizza */
            foreach ($pizzas as $pizza) {
                $generenicTemplate->addElements([
                    Element::create(
                        'COD: ' . $pizza->getCode() . ' R$: ' . number_format(
                            $pizza->getPrice(),
                            2,
                            ',',
                            '.'
                        ) . ' ' . $pizza->getDescription()
                    )
                        ->subtitle($pizza->getSubtitle())
                        ->image($pizza->getImage())
                        ->addButtons([
//                            ElementButton::create('Detalhes')
//                                ->url($pizza->getUrlDetail()),
                            ElementButton::create('Adicionar')
                                ->type('postback')
                                ->payload(self::ORDER_PIZZA_ADD_ITEM . $pizza->getId())
                        ])
                    ,
                ]);
            }

            $this->ask($generenicTemplate, function (Answer $answer) {

                global $kernel;

                if (!empty($answer->getMessage()->getPayload()['postback'])) {

                    $payload = $answer->getMessage()->getPayload()['postback']['payload'];

                    if (substr($payload, 0, 21) == self::ORDER_PIZZA_ADD_ITEM) {

                        $exp = explode('_', $payload);

                        $pizzaSelected = $kernel->getContainer()->get('doctrine')->getRepository(Pizza::class)
                            ->findOneBy(['id' => $exp[4]]);

                        $this->currentOrdemItem = new OrderItems();
                        $this->currentOrdemItem
                            ->setPizza($pizzaSelected)
                            ->setUnitPrice($pizzaSelected->getPrice());

                        $this->order->addOrderItem($this->currentOrdemItem);

                        $this->askQuantity($pizzaSelected);
                    }
                }
            });
        } else {
            $this->say('Desculpe, não existem pizzas para essa categoria.');
        }
    }

    public function askQuantity(Pizza $pizza)
    {
        $question = Question::create("Escolha a quantidade desejada para " . $pizza->getDescription() . ":");

        for ($i = 1; $i < 6; $i++) {
            $question->addButton(
                Button::create($i)->value($i)
            );
        }

        $this->ask(
            $question,
            function (Answer $answer) use ($question) {

                if ($answer->isInteractiveMessageReply()) {
                    $this->currentOrdemItem
                        ->setQuantity($answer->getValue())
                        ->setTotal($this->currentOrdemItem->getUnitPrice() * $answer->getValue());
                    $this->askOrderCategory(true);
                }
                /*elseif (!empty($answer->getText())) {

                    $validQuantity = false;

                    for ($i = 1; $i < 6; $i++) {
                        if ($i == $answer->getText()) {
                            $validQuantity = true;
                        }
                    }

                    if (!$validQuantity) {
                        $this->say('Quantidade inválida.');
                        $this->repeat($question);
                    } else {
                        $setQuantity($answer->getText());
                        $this->askOrderCategory(true);
                    }
                }*/
            }
        );
    }

    private function selectPizzaFinished()
    {
        $totalOrderItems = 0;

        foreach ($this->order->getOrderItems() as $orderItem) {
            $totalOrderItems += $orderItem->getUnitPrice() * $orderItem->getQuantity();
        }

        $this->order->setItemsTotal($totalOrderItems);

        $this->askAddress();
    }

    public function askAddress()
    {
        $list = '';

        foreach ($this->order->getOrderItems() as $orderItem) {
            $list .= 'R$: ' . number_format($orderItem->getPizza()->getPrice(), 2, ',', '.')
                . ' ' . $orderItem->getPizza()->getDescription() . ' - Quantidade: ' . $orderItem->getQuantity()
                . chr(10);
        }

        $list .= 'Total: ' . number_format($this->order->getItemsTotal(), 2, ',', '.');

        $this->say($list);

        //$this->shareAddress();
        $this->textAddress();
    }

    public function textAddress()
    {
        $question = Question::create('Qual o endereço de entrega? Ex: Rua Paulista, n 128');

        $this->ask(
            $question,
            function (Answer $answer) use ($question) {

                global $kernel;

                $address = $answer->getText();

                $results = $kernel->getContainer()->get('app.helper.address')->validateGoogleMaps($address);

                $this->handlerAddressResults($results, $question);
            }
        );
    }

    public function shareAddress()
    {
        $additionalParameters = [
            'message' => [
                'quick_replies' => json_encode([
                    [
                        'content_type' => 'location'
                    ]
                ])
            ]
        ];

        $this->askForLocation(
            'Envie a localização de entrega do pedido clicando no botão abaixo:',
            function (Location $location) {
                $this->say('Received: ' . print_r($location, true));
                /*global $kernel;
                $results = $kernel->getContainer()->get('app.helper.address')
                    ->validateGoogleMaps('', $location->getLatitude(), $location->getLongitude());
                $kernel->getContainer()->get('logger')->info('Location', [$location, $results]);
                $this->handlerAddressResults($results);*/
            },
            null,
            $additionalParameters
        );
    }

    private function handlerAddressResults($results, $question = null)
    {
        global $kernel;

        if (!$results) {
            $this->say('Endereço não encontrado.');
            if ($question) {
                $this->repeat($question);
            } else {
                $this->repeat();
            }
        } else {

            $country = '';

            foreach ($results['results'][0]['address_components'] as $addressComponent) {
                if (in_array('route', $addressComponent['types'])) {
                    $this->address->setStreet($addressComponent['long_name']);
                }
                if (in_array('street_number', $addressComponent['types'])) {
                    $this->address->setNumber($addressComponent['long_name']);
                }
                if (in_array('sublocality_level_1', $addressComponent['types'])) {
                    $this->address->setDistrict($addressComponent['long_name']);
                }
                if (in_array('administrative_area_level_2', $addressComponent['types'])) {
                    $this->address->setCity($addressComponent['long_name']);
                }
                if (in_array('administrative_area_level_1', $addressComponent['types'])) {
                    $uf = $kernel->getContainer()->get('doctrine')->getRepository(Uf::class)
                        ->findOneBy(['sigla' => $addressComponent['short_name']]);
                    $this->address->setUf($uf);
                }
                if (in_array('postal_code', $addressComponent['types'])) {
                    $this->address->setPostcode($addressComponent['long_name']);
                }
                if (in_array('country', $addressComponent['types'])) {
                    $country = $addressComponent['short_name'];
                }
            }

            if (!empty($country) && $country <> 'BR') {
                $this->say('Desculpe, não atendemos na sua localização.');
            } else {
                $errors = $kernel->getContainer()->get('app.helper.address')->getStringErrorsFromEntity($this->address);

                if (!$errors) {
                    $this->order->setShippingAddress($this->address);
                    $this->say('Ok! Segue o endereço informado:');
                    $this->say($this->address->getFullAddress());
                    $this->askAddressComplement();
                } else {
                    $this->say('Parece que os items abaixo do seu endereço não foram identificados. Pode por gentileza verificar?');
                    $this->say($errors);
                    $this->repeat($question);
                }
            }
        }
    }

    public function askAddressComplement()
    {
        $question = Question::create('Alguma informação adicional ao endereço ou ponto de referência?');

        $this->ask($question, function (Answer $answer) {
            $this->order->getShippingAddress()->setComplement($answer->getText());
            $this->askPhoneNumber();
        });
    }

    public function askPhoneNumber()
    {
        if ($this->userAlreadyRegistered()) {
            $this->orderFinish();
        } else {
            $question = Question::create('Informe seu telefone para contato:');

            $this->ask($question, function (Answer $answer) {
                $this->customer->setPhoneNumber($answer->getText());
                $this->askEmail();
            });
        }
    }

    // verificar se email ja pertence a outro usuário
    public function askEmail()
    {
        $question = Question::create('Informe seu e-mail:');

        $this->ask($question, function (Answer $answer) use ($question) {

            global $kernel;

            $valid = $kernel->getContainer()->get('validator')->validate($answer->getText(), new Email());

            if (count($valid) > 0) {
                $this->say('Me parece que o email informado não é valido.');
                $this->repeat($question);
            } else {
                $this->customer->setEmail($answer->getText());
                $this->orderFinish();
            }
        });
    }

    private function showOrderResume()
    {
        $receipt = ReceiptTemplate::create()
            ->recipientName($this->order->getCustomer()->getFullName())
            ->orderUrl('https://nc-firstchatbot.herokuapp.com/order-pizza')
            ->timestamp($this->order->getCreatedAt()->getTimestamp())
            ->orderNumber($this->order->getId())
            ->currency('USD')
            ->paymentMethod('A VISTA');

        foreach ($this->order->getOrderItems() as $orderItem) {
            $receipt->addElement(
                ReceiptElement::create($orderItem->getPizza()->getDescription())
                    ->subtitle($orderItem->getPizza()->getSubtitle())
                    ->quantity($orderItem->getQuantity())
                    ->price($orderItem->getPizza()->getPrice())
                    ->image($orderItem->getPizza()->getImage())
                    ->currency('USD')
            );
        }

        $receipt->addAddress(
            ReceiptAddress::create()
                ->street1($this->order->getShippingAddress()->getStreet())
                ->city($this->order->getShippingAddress()->getCity())
                ->postalCode($this->order->getShippingAddress()->getPostcode())
                ->state($this->order->getShippingAddress()->getUf()->getSigla())
                ->country('BRAZIL')
        );

        $receipt->addSummary(
            ReceiptSummary::create()
                ->subtotal($this->order->getItemsTotal())
                ->shippingCost(0)
                ->totalTax(0)
                ->totalCost($this->order->getItemsTotal())
        );

        $this->bot->reply($receipt);
    }

    private function orderFinish()
    {
        global $kernel;

        $em = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $this->order = $em->merge($this->order);
        $em->flush();

        $this->showOrderResume();

        $this->say('Pedido processado com sucesso.');

        $this->say(
            ButtonTemplate::create('Veja todos os pedidos clicando na opção abaixo:')
                ->addButton(ElementButton::create('Ver Pedidos')->url('https://nc-firstchatbot.herokuapp.com/order-pizza'))
        );
    }
}