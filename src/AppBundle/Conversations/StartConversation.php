<?php

namespace AppBundle\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class StartConversation extends Conversation
{
    public function run()
    {
        $this->bot->reply(
            ButtonTemplate::create('Clique em uma das opções abaixo:')
                ->addButton(ElementButton::create('Cardápio de Pizzas')->type('postback')->payload('order_pizza'))
                ->addButton(ElementButton::create('Pedidos Realizados')->url('https://nc-firstchatbot.herokuapp.com/order-pizza'))
                ->addButton(ElementButton::create('Informações')->type('postback')->payload('info'))
        );
    }

}