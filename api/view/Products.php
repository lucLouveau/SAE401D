<?php
// Set necessary HTTP headers for CORS and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, X-Requested-Width");

// Require the bootstrap file to initialize necessary components
require __DIR__ . '/../model/bootstrap.php';

// Import necessary entity classes
use Entity\Products;
use Entity\Brands;
use Entity\Categories;
use Entity\Stocks;

// Retrieve repositories for entities
$brandRepository = $entityManager->getRepository(Brands::class);
$categoryRepository = $entityManager->getRepository(Categories::class);
$stockRepository = $entityManager->getRepository(Stocks::class);
$myRepository = $entityManager->getRepository(Products::class);

// Determine the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Switch based on the request method
switch ($request_method) {
    case "GET":
        // Handle GET requests
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            if (isset($_REQUEST['reche']) && !empty($_REQUEST['reche'])) {
                // Fetch related stock data for a product
                $result = $stockRepository->findBy(["product_id" => $_REQUEST['id']]);
            } else {
                // Fetch a specific product by its ID
                $id = (int)$_REQUEST['id'];
                $result = $entityManager->find(Products::class, $id);
                if ($result == null) {
                    $result = array("status" => 0, "status_message" => 'Aucun produit connu avec cet identifiant');
                }
            }
        } else if (isset($_REQUEST['name']) && (!empty($_REQUEST['name']))) {

            $result = $myRepository->createQueryBuilder('P')
                ->where('P.product_name LIKE :product')
                ->setParameter('product','%'.$_REQUEST['name'].'%')
                ->getQuery()
                ->getResult();
        } else {
            // Fetch all products
            $result = $myRepository->findAll();
        }
        echo json_encode($result);
        break;

    case "POST":
        // Handle POST requests (Create a new product)
        if (!empty($_POST['name']) && !empty($_POST['brand']) && !empty($_POST['category']) && !empty($_POST['year']) && !empty($_POST['price'])) {
            $product = new Products();
            $product->setProductName($_POST['name']);
            $brand = $brandRepository->find($_POST['brand']);
            $product->setBrandsId($brand);
            $category = $categoryRepository->find($_POST['category']);
            $product->setcategoryId($category);
            $product->setmodelYear($_POST['year']);
            $product->setListPrice($_POST['price']);
            $entityManager->persist($product);
            $entityManager->flush();
            $result = $product;
        } else {
            // If any required parameter is missing
            $result = array("status" => 0, "status_message" => 'paramètre manquant');
        }
        echo json_encode($result);
        break;

    case "DELETE":
        // Handle DELETE requests (Delete a product)
        if (!empty($_REQUEST['id'])) {
            $product = $myRepository->find($_REQUEST['id']);
            if ($product != null && $stockRepository->findOneBy(['product_id' => $_REQUEST['id']]) == null) {
                $entityManager->remove($product);
                $entityManager->flush($product);
                $result = array("status" => 1, "status_message" => 'Produit supprimé');
            } else {
                if ($stockRepository->findOneBy(['product_id' => $_REQUEST['id']]) != null) {
                    $result = array("status" => 0, "status_message" => 'Vous ne pouvez supprimer un produit qui est encore lié à un stocks');
                } else
                    $result = array("status" => 0, "status_message" => 'produit non existant');
            }
        } else {
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez supprimer tous les produits');
        }
        echo json_encode($result);
        break;

    case "PUT":
        // Handle PUT requests (Update a product)
        $form_data = json_encode(file_get_contents("php://input"));

        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }
        if (!empty($_REQUEST['id'])) {
            $product = $myRepository->find($_REQUEST['id']);
            if ($product != null) {
                if (!empty($_PUT['name']))
                    $product->setProductName(urldecode($_PUT['name']));
                if (!empty($_PUT['brand'])) {
                    $brand = $brandRepository->find($_PUT['brand']);
                    $product->setBrandsId($brand);
                }
                if (!empty($_PUT['category'])) {
                    $category = $categoryRepository->find($_PUT['category']);
                    $product->setcategoryId($category);
                }
                if (!empty($_PUT['year']))
                    $product->setmodelYear($_PUT['year']);
                if (!empty($_PUT['price']))
                    $product->setListPrice(urldecode($_PUT['price']));
                $entityManager->flush();
                $result = $product;
            } else {
                $result = array("status" => 0, "status_message" => 'produit non existant');
            }
        } else {
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez modifier tous les produits');
        }
        echo json_encode($result);
        break;
}
