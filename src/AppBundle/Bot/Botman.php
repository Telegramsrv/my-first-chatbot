<?php

namespace AppBundle\Bot;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Botman
{
    private $token;
    private $secret;
    private $verification;

    /**
     * Botman constructor.
     * @param $token
     * @param $secret
     * @param $verification
     */
    public function __construct($token, $secret, $verification)
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->verification = $verification;
    }

    public function getBotman()
    {
        $config = [
            'facebook' => [
                'token'        => $this->token,
                'app_secret'   => $this->secret,
                'verification' => $this->verification,
            ],
        ];

        DriverManager::loadDriver(FacebookDriver::class);

        $adapter = new FilesystemAdapter();

        return BotManFactory::create($config, new SymfonyCache($adapter));
    }
}