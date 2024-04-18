<?php
// src/Entity/Stocks.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Entity\Stores;
use Entity\Products;

/**
 * Stocks Entity represents a stock in the application.
 *
 * This class defines a stock entity with properties such as stock_id, quantity.
 * It includes relationships with other entities Stores and Products using Doctrine's ORM mapping for many-to-one relationships.
 * Additionally, it implements the JsonSerializable interface to customize JSON serialization.
 *
 * @ORM\Entity
 * @ORM\Table(name="Stocks")
 */
class Stocks implements JsonSerializable
{

    /**
     * The unique identifier for the stock.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $stock_id;

    /**
     * The store to which the stock belongs.
     *
     * @ORM\ManyToOne(targetEntity="Stores", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private ?Stores $store_id;

    /**
     * The product associated with the stock.
     *
     * @ORM\ManyToOne(targetEntity="Products", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     */
    private ?Products $product_id;

    /**
     * The quantity of the stock.
     *
     * @ORM\Column(type="integer")
     */
    private string $quantity;

    /**
     * Constructs a new Stocks instance.
     */
    public function __construct()
    {
        // No initialization required for this entity.
    }

    /**
     * Custom JSON serialization for the Stocks instance.
     *
     * @return array The array representation of the Stocks instance.
     */
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $key => $value) {
            if ($key == "store_id") {
                $res["store"] = array("store_id" => $value->getStoresId(), "store_name" => $value->getStoresName());
            } else if ($key == "product_id") {
                $res["product"] = array("product_id" => $value->getProductId(), "product_name" => $value->getProductName(), "price" => $value->getListPrice());
            } else {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    /**
     * Get the ID of the stock.
     *
     * @return int The stock ID.
     */
    public function getStockId()
    {
        return $this->stock_id;
    }

    /**
     * Set the ID of the stock.
     *
     * @param int $id The stock ID.
     * @return Stocks The Stocks instance.
     */
    public function setStockId($id)
    {
        $this->stock_id = $id;
        return $this;
    }

    /**
     * Get the store to which the stock belongs.
     *
     * @return Stores|null The store entity or null if not set.
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * Set the store to which the stock belongs.
     *
     * @param Stores|null $id The store entity or null if not set.
     * @return Stocks The Stocks instance.
     */
    public function setStoreId($id)
    {
        $this->store_id = $id;
        return $this;
    }

    /**
     * Get the product associated with the stock.
     *
     * @return Products|null The product entity or null if not set.
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set the product associated with the stock.
     *
     * @param Products|null $id The product entity or null if not set.
     * @return Stocks The Stocks instance.
     */
    public function setProductId($id)
    {
        $this->product_id = $id;
        return $this;
    }

    /**
     * Get the quantity of the stock.
     *
     * @return string The stock quantity.
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the quantity of the stock.
     *
     * @param string $quantity The stock quantity.
     * @return Stocks The Stocks instance.
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }
}