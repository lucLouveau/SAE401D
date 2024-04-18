<?php
// src/Entity/Stores.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Entity\Stocks;
use Entity\Employees;

/**
 * Stores Entity represents a store in the application.
 *
 * This class defines a store entity with properties such as store_id, store_name, phone, email, street, city, state, and zip_code.
 * It includes relationships with other entities Stocks and Employees using Doctrine's ORM mapping for one-to-many relationships.
 * Additionally, it implements the JsonSerializable interface to customize JSON serialization.
 *
 * @ORM\Entity
 * @ORM\Table(name="Stores")
 */
class Stores implements JsonSerializable
{

    /**
     * The unique identifier for the store.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $store_id;

    /**
     * The name of the store.
     *
     * @ORM\Column(type="string")
     */
    private string $store_name;

    /**
     * The phone number of the store.
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private ?string $phone;

    /**
     * The email of the store.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $email;

    /**
     * The street address of the store.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $street;

    /**
     * The city of the store.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $city;

    /**
     * The state of the store.
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $state;

    /**
     * The zip code of the store.
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private ?string $zip_code;

    /**
     * Collection of stocks associated with this store.
     *
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Stocks", mappedBy="store_id")
     */
    private ?Collection $stocks;

    /**
     * Collection of employees associated with this store.
     *
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Employees", mappedBy="store_id")
     */
    private ?Collection $employees;

    /**
     * Constructs a new Stores instance.
     */
    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->stocks = new ArrayCollection();
    }

    /**
     * Custom JSON serialization for the Stores instance.
     *
     * @return array The array representation of the Stores instance.
     */
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $key => $value) {

            if ($key == "employees") {
                $res[$key] = array();
                foreach ($value as $cle => $valeur) {
                    $res[$key][] = $valeur->getEmployeeId();
                }
            } else if ($key == "stocks") {
                $res[$key] = array();
                foreach ($value as $cle => $valeur) {
                    $res[$key][] = $valeur->getStockId();
                }
            } else {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    /**
     * Get the ID of the store.
     *
     * @return int The store ID.
     */
    public function getStoresId()
    {
        return $this->store_id;
    }

    /**
     * Set the ID of the store.
     *
     * @param int $id The store ID.
     * @return Stores The Stores instance.
     */
    public function setStoresId($id)
    {
        $this->store_id = $id;
        return $this;
    }

    /**
     * Get the name of the store.
     *
     * @return string The store name.
     */
    public function getStoresName()
    {
        return $this->store_name;
    }

    /**
     * Set the name of the store.
     *
     * @param string $name The store name.
     * @return Stores The Stores instance.
     */
    public function setStoresName($name)
    {
        $this->store_name = $name;
        return $this;
    }

    /**
     * Get the phone number of the store.
     *
     * @return string|null The store phone number.
     */
    public function getStoresPhone()
    {
        return $this->phone;
    }

    /**
     * Set the phone number of the store.
     *
     * @param string|null $phone The store phone number.
     * @return Stores The Stores instance.
     */
    public function setStoresPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get the email of the store.
     *
     * @return string|null The store email.
     */
    public function getStoresEmail()
    {
        return $this->email;
    }

    /**
     * Set the email of the store.
     *
     * @param string|null $email The store email.
     * @return Stores The Stores instance.
     */
    public function setStoresEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the street address of the store.
     *
     * @return string|null The store street address.
     */
    public function getStoresStreet()
    {
        return $this->street;
    }

    /**
     * Set the street address of the store.
     *
     * @param string|null $name The store street address.
     * @return Stores The Stores instance.
     */
    public function setStoresStreet($name)
    {
        $this->street = $name;
        return $this;
    }

    /**
     * Get the city of the store.
     *
     * @return string|null The store city.
     */
    public function getStoresCity()
    {
        return $this->city;
    }

    /**
     * Set the city of the store.
     *
     * @param string|null $name The store city.
     * @return Stores The Stores instance.
     */
    public function setStoresCity($name)
    {
        $this->city = $name;
        return $this;
    }

    /**
     * Get the state of the store.
     *
     * @return string|null The store state.
     */
    public function getStoresState()
    {
        return $this->state;
    }

    /**
     * Set the state of the store.
     *
     * @param string|null $name The store state.
     * @return Stores The Stores instance.
     */
    public function setStoresState($name)
    {
        $this->state = $name;
        return $this;
    }

    /**
     * Get the zip code of the store.
     *
     * @return string|null The store zip code.
     */
    public function getStoresCode()
    {
        return $this->zip_code;
    }

    /**
     * Set the zip code of the store.
     *
     * @param string|null $code The store zip code.
     * @return Stores The Stores instance.
     */
    public function setStoresCode($code)
    {
        $this->zip_code = $code;
        return $this;
    }

    /**
     * Get the stocks associated with this store.
     *
     * @return \Doctrine\Common\Collections\Collection The stocks collection.
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * Set the stocks associated with this store.
     *
     * @param Stocks $stocks The stocks to associate.
     * @return Stores The Stores instance.
     */
    public function setStocks($stocks)
    {
        $this->stocks[] = $stocks;
        return $this;
    }

    /**
     * Get the employees associated with this store.
     *
     * @return \Doctrine\Common\Collections\Collection The employees collection.
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Set the employees associated with this store.
     *
     * @param Employees $employees The employees to associate.
     * @return Stores The Stores instance.
     */
    public function setEmployees($employees)
    {
        $this->employees[] = $employees;
        return $this;
    }
}
