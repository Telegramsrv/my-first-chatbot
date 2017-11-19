<?php

namespace AppBundle\Conversations;

use AppBundle\Bot\Menu;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class BaseConversation extends Conversation
{
    public function run()
    {
        // TODO: Implement run() method.
    }

    public function stopsConversation(IncomingMessage $message)
    {
        if (in_array($message->getText(), Menu::$items)) {
            return true;
        }

        if (!empty($message->getPayload()['postback'])) {
            if (in_array($message->getPayload()['postback']['payload'], Menu::$items)) {
                return true;
            }
        }

        return false;
    }

}