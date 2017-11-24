<?php

namespace AppBundle\Conversations;

use BotMan\BotMan\Messages\Attachments\Location;

class LocationConversation extends BaseConversation
{
    public function run()
    {
        $this->askLocation();
    }

    public function askLocation()
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
            },
            null,
            $additionalParameters
        );
    }
}