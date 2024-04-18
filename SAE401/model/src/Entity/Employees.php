<?php
// src/Employees.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Entity\Stores;

/**
 * Employees Entity represents an employee in the application.
 *
 * This class defines an employee entity with properties such as employee_id, employee_name, employee_email, employee_password, and employee_role.
 * It includes a relationship with another entity Stores using Doctrine's ORM mapping for a many-to-one relationship.
 * Additionally, it implements the JsonSerializable interface to customize JSON serialization.
 *
 * @ORM\Entity
 * @ORM\Table(name="Employees")
 */
class Employees implements JsonSerializable
{

    /**
     * The unique identifier for the employee.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $employee_id;

    /**
     * The store to which the employee belongs.
     *
     * @ORM\ManyToOne(targetEntity="Stores", inversedBy="employees", cascade={"persist"})
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private ?Stores $store_id;

    /**
     * The name of the employee.
     *
     * @ORM\Column(type="string")
     */
    private string $employee_name;

    /**
     * The email of the employee.
     *
     * @ORM\Column(type="string")
     */
    private string $employee_email;

    /**
     * The password of the employee.
     *
     * @ORM\Column(type="string")
     */
    private string $employee_password;

    /**
     * The role of the employee.
     *
     * @ORM\Column(type="string")
     */
    private string $employee_role;

    /**
     * Constructs a new Employees instance.
     */
    public function __construct()
    {
        // No initialization required for this entity.
    }

    /**
     * Custom JSON serialization for the Employees instance.
     *
     * @return array The array representation of the Employees instance.
     */
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $key => $value) {
            if ($key == "store_id" && $value != null) {
                $res["store"] = array("store_id" => $value->getStoresId(), "store_name" => $value->getStoresName());
            } else {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    /**
     * Get the ID of the employee.
     *
     * @return int The employee ID.
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * Set the ID of the employee.
     *
     * @param int $id The employee ID.
     * @return Employees The Employees instance.
     */
    public function setEmployeeId($id)
    {
        $this->employee_id = $id;
        return $this;
    }

    /**
     * Get the store to which the employee belongs.
     *
     * @return Stores|null The store entity or null if not set.
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * Set the store to which the employee belongs.
     *
     * @param Stores|null $id The store entity or null if not set.
     * @return Employees The Employees instance.
     */
    public function setStoreId($id)
    {
        $this->store_id = $id;
        return $this;
    }

    /**
     * Get the name of the employee.
     *
     * @return string The employee name.
     */
    public function getEmployeeName()
    {
        return $this->employee_name;
    }

    /**
     * Set the name of the employee.
     *
     * @param string $name The employee name.
     * @return Employees The Employees instance.
     */
    public function setEmployeeName($name)
    {
        $this->employee_name = $name;
        return $this;
    }

    /**
     * Get the email of the employee.
     *
     * @return string The employee email.
     */
    public function getEmployeeEmail()
    {
        return $this->employee_email;
    }

    /**
     * Set the email of the employee.
     *
     * @param string $email The employee email.
     * @return Employees The Employees instance.
     */
    public function setEmployeeEmail($email)
    {
        $this->employee_email = $email;
        return $this;
    }

    /**
     * Get the password of the employee.
     *
     * @return string The employee password.
     */
    public function getEmployeePassword()
    {
        return $this->employee_password;
    }

    /**
     * Set the password of the employee.
     *
     * @param string $password The employee password.
     * @return Employees The Employees instance.
     */
    public function setEmployeePassword($password)
    {
        $this->employee_password = $password;
        return $this;
    }

    /**
     * Get the role of the employee.
     *
     * @return string The employee role.
     */
    public function getEmployeeRole()
    {
        return $this->employee_role;
    }

    /**
     * Set the role of the employee.
     *
     * @param string $role The employee role.
     * @return Employees The Employees instance.
     */
    public function setEmployeeRole($role)
    {
        $this->employee_role = $role;
        return $this;
    }
}