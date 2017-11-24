<?php

namespace AppBundle\Controller;

use AppBundle\Bot\Menu;
use AppBundle\Conversations\LocationConversation;
use AppBundle\Conversations\OrderPizzaConversation;
use AppBundle\Conversations\StartConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BotController extends Controller
{
    /**
     * @var BotMan
     */
    private $botman;

    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $this->botman = $this->get('app.botman')->getBotman();

        $this
            ->start()
            ->orderPizza()
            ->info()
            ->location()
            ->fallback();

        $this->botman->listen();

        return new Response('');
        //return new Response('<html><body></body></html>');
    }

    private function start()
    {
        $this->botman->hears(
            Menu::CALLBACK_START . '|' . Menu::CALLBACK_MAIN_MENU,
            function (Botman $bot) {
                $bot->startConversation(new StartConversation());
            }
        );

        return $this;
    }

    private function orderPizza()
    {
        $this->botman->hears(
            Menu::CALLBACK_ORDER_PIZZA,
            function (Botman $bot) {
                $bot->startConversation(new OrderPizzaConversation());
            }
        );

        return $this;
    }

    private function info()
    {
        $this->botman->hears(
            Menu::CALLBACK_INFO,
            function (Botman $bot) {
                $bot->reply('Estamos na Rua Coronel Mariano de Mello 671 JD Anhanguera, Ribeirão Preto. Tel: (11) 93847829.');
            }
        );

        return $this;
    }

    private function location()
    {
        $this->botman->hears(
            Menu::CALLBACK_LOCATION,
            function (Botman $bot) {
                $bot->startConversation(new LocationConversation());
            }
        );

        return $this;
    }

    private function fallback()
    {
        $this->botman->fallback(
            function (Botman $bot) {
                $bot->reply('Desculpe, não pude compreender a opção desejada.');
                $bot->startConversation(new StartConversation());
            }
        );

        return $this;
    }

    /**
     * @Route("/valid-address")
     */
    public function testValidAddress()
    {
        $address = urlencode('Rua orminda machado duarte n 11 vila velha');
        //$address = urlencode('gama');

        $addressHelper = $this->get('app.helper.address');

        $results = $addressHelper->validateGoogleMaps($address);

        if ($results) {

            var_dump($results['results'][0]['address_components']);

            foreach ($results['results'][0]['address_components'] as $addressComponent) {
                if (in_array('route', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
                if (in_array('street_number', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
                if (in_array('sublocality_level_1', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
                if (in_array('administrative_area_level_2', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
                if (in_array('administrative_area_level_1', $addressComponent['types'])) {
                    var_dump($addressComponent['short_name']);
                }
                if (in_array('postal_code', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
                if (in_array('country', $addressComponent['types'])) {
                    var_dump($addressComponent['long_name']);
                }
            }
        }

        return new Response('');
    }
}
