<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderPizzaController extends Controller
{

    /**
     * @Route("/order-pizza", name="order_pizza")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->get('app.helper.pagination')->handle($request, Orders::class);

        $orders = $this->getDoctrine()->getRepository(Orders::class)->findLatest($pagination);

        return $this->render('orderPizza/cards.html.twig', [
            'orders' => $orders
        ]);
    }
}
