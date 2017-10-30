<?php

namespace AppBundle\Entity;


use AppBundle\Resource\Model\TimestampableTrait;
use AppBundle\Resource\Model\ToggleableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Orders
{
    use TimestampableTrait, ToggleableTrait;

    const STATE_NEW = 'new';
    const STATE_CANCELLED = 'cancelled';

    const PAYMENT_STATE_AWAITING = 'awaiting_payment';
    const PAYMENT_STATE_PAID = 'paid';

    const SHIPPING_STATE_READY = 'ready';
    const SHIPPING_STATE_SHIPPED = 'shipped';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $state = self::STATE_NEW;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     * @Assert\LessThan(value="99999999.99")
     */
    private $itemsTotal;

    /**
     * @ORM\Column(type="string")
     */
    private $paymentState = self::PAYMENT_STATE_AWAITING;

    /**
     * @ORM\Column(type="string")
     */
    private $shippingState = self::SHIPPING_STATE_READY;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $shippingAddress;

    /**
     * @var OrderItems
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderItems", mappedBy="orders", cascade={"all"})
     * @Assert\Count(min="1")
     */
    private $orderItems;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Orders
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * @param mixed $itemsTotal
     * @return Orders
     */
    public function setItemsTotal($itemsTotal)
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * @param mixed $paymentState
     * @return Orders
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * @param mixed $shippingState
     * @return Orders
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;
        return $this;
    }

    /**
     * @return Address
     */
    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    /**
     * @param Address $shippingAddress
     * @return Orders
     */
    public function setShippingAddress(Address $shippingAddress): Orders
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * @return ArrayCollection|OrderItems[]
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItems $orderItem)
    {
        if (!$this->orderItems->contains($orderItem)) {
            $orderItem->setOrders($this);
            $this->orderItems->add($orderItem);
        }
    }

    public function removeOrderItem(OrderItems $orderItem)
    {
        $this->orderItems->removeElement($orderItem);
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Orders
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }
}