<?php
// src/Entity/Products.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Entity\Brands;
use Entity\Stocks;
use Entity\Categories;

/**
 * Products Entity represents a product in the application.
 *
 * This class defines a product entity with properties such as product_id, product_name, model_year, and list_price.
 * It includes relationships with other entities Brands, Categories, and Stocks using Doctrine's ORM mapping for many-to-one and one-to-many relationships.
 * Additionally, it implements the JsonSerializable interface to customize JSON serialization.
 *
 * @ORM\Entity
 * @ORM\Table(name="Products")
 */
class Products implements JsonSerializable
{

    /**
     * The unique identifier for the product.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $product_id;

    /**
     * The name of the product.
     *
     * @ORM\Column(type="string")
     */
    private string $product_name;

    /**
     * The brand of the product.
     *
     * @ORM\ManyToOne(targetEntity="Brands", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id")
     */
    private ?Brands $brand_id;

    /**
     * The category of the product.
     *
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     */
    private ?Categories $category_id;

    /**
     * The model year of the product.
     *
     * @ORM\Column(type="smallint")
     */
    private string $model_year;

    /**
     * The list price of the product.
     *
     * @ORM\Column(type="decimal")
     */
    private int $list_price;

    /**
     * Collection of stocks associated with this product.
     *
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Stocks", mappedBy="product_id")
     */
    private Collection $stocks;

    /**
     * Constructs a new Products instance.
     */
    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }

    /**
     * Custom JSON serialization for the Products instance.
     *
     * @return array The array representation of the Products instance.
     */
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $key => $value) {
            if ($key == 'brand_id') {
                $res["brand"] = array("brand_id" => $value->getBrandsId(), "brand_name" => $value->getBrandsName());
            }else if ($key == "stocks") {
                $res[$key] = array();
                foreach ($value as $cle => $valeur) {
                    $res[$key][] = $valeur->getStockId();
                }
            } else if ($key == "category_id") {
                $res["category"] = array("category_id" => $value->getCategoryId(), "category_name" => $value->getCategoryName());
            } else {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    /**
     * Get the ID of the product.
     *
     * @return int The product ID.
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set the ID of the product.
     *
     * @param int $id The product ID.
     * @return Products The Products instance.
     */
    public function setProductId($id)
    {
        $this->product_id = $id;
        return $this;
    }

    /**
     * Get the name of the product.
     *
     * @return string The product name.
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set the name of the product.
     *
     * @param string $name The product name.
     * @return Products The Products instance.
     */
    public function setProductName($name)
    {
        $this->product_name = $name;
        return $this;
    }

    /**
     * Get the brand of the product.
     *
     * @return Brands|null The brand entity or null if not set.
     */
    public function getBrandsId()
    {
        return $this->brand_id;
    }

    /**
     * Set the brand of the product.
     *
     * @param Brands|null $id The brand entity or null if not set.
     * @return Products The Products instance.
     */
    public function setBrandsId($id)
    {
        $this->brand_id = $id;
        return $this;
    }

    /**
     * Get the category of the product.
     *
     * @return Categories|null The category entity or null if not set.
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the category of the product.
     *
     * @param Categories|null $id The category entity or null if not set.
     * @return Products The Products instance.
     */
    public function setCategoryId($id)
    {
        $this->category_id = $id;
        return $this;
    }

    /**
     * Get the model year of the product.
     *
     * @return string The model year.
     */
    public function getModelYear()
    {
        return $this->model_year;
    }

    /**
     * Set the model year of the product.
     *
     * @param string $year The model year.
     * @return Products The Products instance.
     */
    public function setModelYear($year)
    {
        $this->model_year = $year;
        return $this;
    }

    /**
     * Get the list price of the product.
     *
     * @return int The list price.
     */
    public function getListPrice()
    {
        return $this->list_price;
    }

    /**
     * Set the list price of the product.
     *
     * @param int $price The list price.
     * @return Products The Products instance.
     */
    public function setListPrice($price)
    {
        $this->list_price = $price;
        return $this;
    }

    /**
     * Get the stocks associated with the product.
     *
     * @return \Doctrine\Common\Collections\Collection The stocks collection.
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * Set the stocks associated with the product.
     *
     * @param Stocks $stocks The stock to associate.
     * @return Products The Products instance.
     */
    public function setStocks($stocks)
    {
        $this->stocks[] = $stocks;
        return $this;
    }
}