<?php

namespace AppBundle\Resource\Model;

use AppBundle\Entity\Uf;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $street;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $number;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $district;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $postcode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mainAddress = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $complement;

    /**
     * @var Uf
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Uf", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Assert\NotNull()
     */
    private $uf;

    /**
     * Set street
     *
     * @param string $street
     *
     * @return $this;
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return $this;
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return $this;
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return $this;
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set mainAddress
     *
     * @param string $mainAddress
     *
     * @return $this;
     */
    public function setMainAddress($mainAddress)
    {
        $this->mainAddress = $mainAddress;

        return $this;
    }

    /**
     * Get mainAddress
     *
     * @return string
     */
    public function getMainAddress()
    {
        return $this->mainAddress;
    }

    /**
     * Set complement
     *
     * @param string $complement
     *
     * @return $this;
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @return Uf
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param Uf $uf
     * @return $this;
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return AddressTrait
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getFullAddress()
    {
        return $this->getStreet() . ', '
            . $this->getNumber() . ', '
            . $this->getDistrict() . ', '
            . $this->getPostcode() . ', '
            . $this->getUf()->getSigla();
    }
}