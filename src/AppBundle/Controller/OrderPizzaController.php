<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OrderPizzaController extends Controller
{

    /**
     * @Route("/order-pizza", name="order_pizza")
     */
    public function indexAction()
    {
        return new Response('<html><body>Order Pizza List (em progresso...)</body>');
    }
}
