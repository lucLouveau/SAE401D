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
use Entity\Stocks;
use Entity\Products;
use Entity\Stores;

// Retrieve repositories for entities
$StoresRepository = $entityManager->getRepository(Stores::class);
$ProductsRepository = $entityManager->getRepository(Products::class);
$myRepository = $entityManager->getRepository(Stocks::class);

// Determine the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Switch based on the request method
switch ($request_method) {
    case "GET":
        // Handle GET requests
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            // Fetch a specific stock by its ID
            $id = (int)$_REQUEST['id'];
            $result = $entityManager->find(Stocks::class, $id);
            if ($result == null) {
                $result = array("status" => 0, "status_message" => 'Aucun stock connu avec cet identifiant');
            }
        } else if (isset($_REQUEST['store']) && !empty($_REQUEST['store'])) {
            // Fetch stocks associated with a specific store
            $result = $myRepository->findBy(['store_id' => $_REQUEST['store']]);
        } else {
            // Fetch all stocks
            $result = $myRepository->findAll();
        }
        echo json_encode($result);
        break;
    
    case "POST":
        // Handle POST requests (Create a new stock)
        if ($myRepository->findOneBy(["store_id" => $_POST["store"], "product_id" => $_POST["product"]]) == null) {
            $stock = new Stocks();
            if (!empty($_POST['store'])) {
                $store = $StoresRepository->find($_POST['store']);
                $stock->setStoreId($store);
            }
            if (!empty($_POST['product'])) {
                $product = $ProductsRepository->find($_POST['product']);
                $stock->setProductId($product);
            }
            if (!empty($_POST['quantity'])) {
                $stock->setQuantity($_POST['quantity']);
            }

            $entityManager->persist($stock);
            $entityManager->flush();
            $result = $stock;
        } else {
            // If the stock already exists
            $result = array("status" => 0, "status_message" => 'stock existant');
        }
        echo json_encode($result);
        break;
    
    case "DELETE":
        // Handle DELETE requests (Delete a stock)
        if (!empty($_REQUEST['id'])) {
            $stock = $myRepository->find($_REQUEST['id']);
            if ($stock != null) {
                $entityManager->remove($stock);
                $entityManager->flush($stock);
                $result = array("status" => 1, "status_message" => 'stock supprimé');
            } else {
                $result = array("status" => 0, "status_message" => 'stock non existant');
            }
        } else {
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez supprimer tous les stocks');
        }
        echo json_encode($result);
        break;
    
    case "PUT":
        // Handle PUT requests (Update a stock)
        $form_data = json_encode(file_get_contents("php://input"));
        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }

        if (!empty($_REQUEST['id'])) {
            $stock = $myRepository->find($_REQUEST['id']);
            if ($stock != null) {
                if (!empty($_PUT['store'])) {
                    $store = $StoresRepository->find($_PUT['store']);
                    $stock->setStoreId($store);
                }
                if (!empty($_PUT['product'])) {
                    $product = $ProductsRepository->find($_PUT['product']);
                    $stock->setProductId($product);
                }
                if (!empty($_PUT['quantity'])) 
                    $stock->setQuantity($_PUT['quantity']);
                $entityManager->flush();
                $result = $stock;
            } else {
                $result = array("status" => 0, "status_message" => 'stock non existant');
            }
        } else {
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez modifier tous les stocks');
        }
        echo json_encode($result);
        break;
}
