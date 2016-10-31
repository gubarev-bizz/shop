<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 * @ORM\Table(name="multicurrency")
 */
class MultiCurrency
{
    use IdentifiableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     */
    private $key;

    /**
     * @var float
     *
     * @ORM\Column(name="`value`", type="float", nullable=false)
     * @SymfonyConstraints\NotBlank()
     */
    private $value;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
