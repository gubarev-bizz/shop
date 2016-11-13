<?php

namespace App\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;

/**
 * @ORM\Entity()
 */
class User implements UserInterface
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    private $password;

    /**
     * @var string
     *
     * @SymfonyConstraints\Length(min="6")
     * @SymfonyConstraints\Type(type="string")
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=90)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Type(type="string")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=90)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Type(type="string")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $salt;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json_array")
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Count(min="1")
     * @SymfonyConstraints\All(constraints={
     *     @SymfonyConstraints\Type("string"),
     *     @SymfonyConstraints\NotBlank(),
     * })
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    protected $products;

    /**
     * BaseUser constructor.
     */
    public function __construct()
    {
        $this->roles = [];
        $this->salt = md5(uniqid());
        $this->active = true;
        $this->products = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getEmail();
    }

    /**
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $value
     */
    public function setPlainPassword($value)
    {
        $this->plainPassword = $value;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string[] $value
     * @return $this
     */
    public function setRoles($value)
    {
        $this->roles = $value;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function addRole($value)
    {
        $this->roles[] = $value;

        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     * @return $this
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct($product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function removeProducts($product)
    {
        $this->products->remove($product);
        $product->setUser(null);

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}
