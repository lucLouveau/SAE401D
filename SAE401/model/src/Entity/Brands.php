<?php
// src/Brands.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Entity\Products;

/**
 * Brands Entity represents a brand in the application.
 *
 * @ORM\Entity
 * @ORM\Table(name="Brands")
 */
class Brands implements JsonSerializable
{

    /**
     * The unique identifier for the brand.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $brand_id;

    /**
     * The name of the brand.
     *
     * @ORM\Column(type="string")
     */
    private string $brand_name;

    /**
     * Collection of products associated with this brand.
     *
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Products",mappedBy="brand_id")
     */
    private Collection $products;

    /**
     * Constructs a new Brands instance.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Custom JSON serialization for the Brands instance.
     *
     * @return array The array representation of the Brands instance.
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
     * Get the ID of the brand.
     *
     * @return int The brand ID.
     */
    public function getBrandsId()
    {
        return $this->brand_id;
    }

    /**
     * Set the ID of the brand.
     *
     * @param int $id The brand ID.
     * @return Brands The Brands instance.
     */
    public function setBrandsId($id)
    {
        $this->brand_id = $id;
        return $this;
    }

    /**
     * Get the products associated with this brand.
     *
     * @return \Doctrine\Common\Collections\Collection The products collection.
     */
    public function getProduct()
    {
        return $this->products;
    }

    /**
     * Set a product associated with this brand.
     *
     * @param Products $products The product to associate.
     * @return Brands The Brands instance.
     */
    public function setProduct($products)
    {
        $this->products[]=$products;
        return $this;
    }

    /**
     * Get the name of the brand.
     *
     * @return string The brand name.
     */
    public function getBrandsName()
    {
        return $this->brand_name;
    }

    /**
     * Set the name of the brand.
     *
     * @param string $brand The brand name.
     * @return Brands The Brands instance.
     */
    public function setBrandsName($brand)
    {
        $this->brand_name = $brand;
        return $this;
    }
}