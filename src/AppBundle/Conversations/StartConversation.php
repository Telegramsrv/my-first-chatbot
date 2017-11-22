<?php

namespace AppBundle\Conversations;

use AppBundle\Bot\Menu;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;

class StartConversation extends BaseConversation
{
    public function run()
    {
        $image = 'https://marketingland.com/wp-content/ml-loads/2016/04/facebook-bots-messenger1-1920.jpg';

        $this->bot->reply(GenericTemplate::create()
            ->addImageAspectRatio(GenericTemplate::RATIO_HORIZONTAL)
            ->addElements([
                Element::create('Pedidos')
                    ->subtitle('Adicione items para o seu pedido através de nosso cardápio')
                    ->image($image)
                    ->addButtons([
                        ElementButton::create('Ver Cardápio')
                            ->type('postback')
                            ->payload(Menu::CALLBACK_ORDER_PIZZA)
                    ]),
                Element::create('Pedidos Realizados')
                    ->subtitle('Veja a lista dos pedidos realizados')
                    ->image($image)
                    ->addButtons([
                        ElementButton::create('Ver Pedidos')
                            ->url('https://nc-firstchatbot.herokuapp.com/order-pizza')
                    ]),
                Element::create('Informações')
                    ->subtitle('Saiba mais informações sobre nós')
                    ->image($image)
                    ->addButtons([
                        ElementButton::create('Ver Informações')
                            ->type('postback')
                            ->payload(Menu::CALLBACK_INFO)
                    ])
            ])
        );
    }

}