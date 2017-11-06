<?php

namespace AppBundle\Bot;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use Doctrine\Common\Cache\SQLite3Cache;
use SQLite3;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Botman
{
    private $token;
    private $secret;
    private $verification;
    private $conversationCacheTime;

    /**
     * Botman constructor.
     * @param $token
     * @param $secret
     * @param $verification
     * @param $conversationCacheTime
     */
    public function __construct($token, $secret, $verification, $conversationCacheTime)
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->verification = $verification;
        $this->conversationCacheTime = $conversationCacheTime;
    }

    public function getBotman()
    {
        $config = [
            'facebook' => [
                'token' => $this->token,
                'app_secret' => $this->secret,
                'verification' => $this->verification,
            ],
            'config' => [
                'conversation_cache_time' => $this->conversationCacheTime
            ],
            'botman' => [
                'conversation_cache_time' => $this->conversationCacheTime
            ]
        ];

        DriverManager::loadDriver(FacebookDriver::class);

        $adapter = new FilesystemAdapter();

        /*$provider = new SQLite3Cache(new SQLite3(__DIR__.'/../../../var/cache/data.sqlite'), 'chatbot_fb');

        $adapter = new DoctrineAdapter($provider, $namespace = '', $defaultLifetime = 0);*/

        return BotManFactory::create($config, new SymfonyCache($adapter));
    }
}