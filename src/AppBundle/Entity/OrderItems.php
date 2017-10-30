<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     * @Assert\LessThan(value="99999999.99")
     */
    private $unitPrice;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     * @Assert\LessThan(value="99999999.99")
     */
    private $total;

    /**
     * @var Pizza
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pizza", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pizza;

    /**
     * @var Orders
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Orders", inversedBy="orderItems", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $orders;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return OrderItems
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return OrderItems
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     * @return OrderItems
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     * @return OrderItems
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return Pizza
     */
    public function getPizza(): Pizza
    {
        return $this->pizza;
    }

    /**
     * @param Pizza $pizza
     * @return OrderItems
     */
    public function setPizza(Pizza $pizza): OrderItems
    {
        $this->pizza = $pizza;
        return $this;
    }

    /**
     * @return Orders
     */
    public function getOrders(): Orders
    {
        return $this->orders;
    }

    /**
     * @param Orders $orders
     * @return OrderItems
     */
    public function setOrders(Orders $orders): OrderItems
    {
        $this->orders = $orders;
        return $this;
    }

}