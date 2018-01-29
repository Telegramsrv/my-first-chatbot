<?php

namespace AppBundle\Bot;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\Psr6Cache;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use Doctrine\Common\Cache\SQLite3Cache;
use SQLite3;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Simple\PdoCache;

class Botman
{
    private $token;
    private $secret;
    private $verification;
    private $conversationCacheTime;
    private $cacheDir;

    /**
     * Botman constructor.
     * @param $token
     * @param $secret
     * @param $verification
     * @param $conversationCacheTime
     * @param $cacheDir
     */
    public function __construct($token, $secret, $verification, $conversationCacheTime, $cacheDir)
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->verification = $verification;
        $this->conversationCacheTime = $conversationCacheTime;
        $this->cacheDir = $cacheDir;
    }

    public function getBotman()
    {
        $config = [
            'facebook' => [
                'token' => $this->token,
                'app_secret' => $this->secret,
                'verification' => $this->verification,
            ],
            'botman' => [
                'conversation_cache_time' => $this->conversationCacheTime
            ]
        ];

        DriverManager::loadDriver(FacebookDriver::class);

        $adapter = new FilesystemAdapter('', 0, $this->cacheDir);
        //$adapter = new ApcuAdapter();
        //$adapter = new PhpFilesAdapter();

        //return BotManFactory::create($config, new Psr6Cache($adapter));
        return BotManFactory::create($config, new SymfonyCache($adapter));
    }
}