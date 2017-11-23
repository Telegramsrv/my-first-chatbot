<?php

namespace AppBundle\Bot;

class Menu
{
    const CALLBACK_START = 'olá|oi|ei|hi';
    const CALLBACK_MAIN_MENU = 'main_menu';
    const CALLBACK_ORDER_PIZZA = 'order_pizza';
    const CALLBACK_INFO = 'info';

    /**
     * @var array
     */
    public static $items = [
        self::CALLBACK_START,
        self::CALLBACK_MAIN_MENU,
        self::CALLBACK_ORDER_PIZZA,
        self::CALLBACK_INFO
    ];
}