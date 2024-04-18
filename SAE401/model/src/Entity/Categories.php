<?php
// src/Categories.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Entity\Products;

/**
 * Categories Entity represents a category in the application.
 *
 * @ORM\Entity
 * @ORM\Table(name="Categories")
 */
class Categories implements JsonSerializable
{

    /**
     * The unique identifier for the category.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $category_id;

    /**
     * The name of the category.
     *
     * @ORM\Column(type="string")
     */
    private string $category_name;

    /**
     * Collection of products associated with this category.
     *
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Products", mappedBy="category_id")
     */
    private Collection $products;

    /**
     * Constructs a new Categories instance.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Custom JSON serialization for the Categories instance.
     *
     * @return array The array representation of the Categories instance.
     */
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $key => $value) {
            if ($key == "products") {
                $res[$key] = array();
                foreach ($value as $cle => $valeur) {
                    $res[$key][] = $valeur->getProductId();
                }
            }
            else $res[$key] = $value;
        }
        return $res;
    }

    /**
     * Get the ID of the category.
     *
     * @return int The category ID.
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the ID of the category.
     *
     * @param int $id The category ID.
     * @return Categories The Categories instance.
     */
    public function setCategoryId($id)
    {
        $this->category_id = $id;
        return $this;
    }

    /**
     * Get the products associated with this category.
     *
     * @return \Doctrine\Common\Collections\Collection The products collection.
     */
    public function getProduct()
    {
        return $this->products;
    }

    /**
     * Set a product associated with this category.
     *
     * @param Products $products The product to associate.
     * @return Categories The Categories instance.
     */
    public function setProduct($products)
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * Get the name of the category.
     *
     * @return string The category name.
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * Set the name of the category.
     *
     * @param string $category The category name.
     * @return Categories The Categories instance.
     */
    public function setCategoryName($category)
    {
        $this->category_name = $category;
        return $this;
    }
}