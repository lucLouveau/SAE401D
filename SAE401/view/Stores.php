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
use Entity\Stores;
use Entity\Employees;
use Entity\Stocks;

// Retrieve repositories for entities
$employeeRepository = $entityManager->getRepository(Employees::class);
$stockRepository = $entityManager->getRepository(Stocks::class);
$myRepository = $entityManager->getRepository(Stores::class);

// Determine the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Switch based on the request method
switch ($request_method) {
    case "GET":
        // Handle GET requests
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            // Fetch a specific store by its ID
            if (isset($_REQUEST['reche']) && !empty($_REQUEST['reche'])) {
                $result = $stockRepository->findBy(["store_id" => $_REQUEST['id']]);
            } 
            else {
                $id = (int)$_REQUEST['id'];
                $result = $entityManager->find(Stores::class, $id);
                if ($result == null) {
                    $result = array("status" => 0, "status_message" => 'Aucun magasin connu avec cet identifiant');
                }
            }
        } else {
            // Fetch all stores
            $result = $myRepository->findAll();
        }
        echo json_encode($result);
        break;
    
    case "POST":
        // Handle POST requests (Create a new store)
        if (!empty($_POST['name'])) {
            $store = new Stores();
            $store->setStoresName($_POST['name']);
            if (!empty($_POST['phone'])) $store->setStoresPhone($_POST['phone']);
            if (!empty($_POST['email'])) $store->setStoresEmail($_POST['email']);
            if (!empty($_POST['street'])) $store->setStoresStreet($_POST['street']);
            if (!empty($_POST['city'])) $store->setStoresCity($_POST['city']);
            if (!empty($_POST['state'])) $store->setStoresState($_POST['state']);
            if (!empty($_POST['code'])) $store->setStoresCode($_POST['code']);
            $entityManager->persist($store);
            $entityManager->flush();
            $result = $store;
        } else {
            // If required parameters are missing
            $result = array("status" => 0, "status_message" => 'paramètre manquant');
        }
        echo json_encode($result);
        break;
    
    case "DELETE":
        // Handle DELETE requests (Delete a store)
        if (!empty($_REQUEST['id'])) {
            $store = $myRepository->find($_REQUEST['id']);
            if ($store != null && $employeeRepository->findOneBy(['store_id' => $_REQUEST['id']]) == null && $stockRepository->findOneBy(['store_id' => $_REQUEST['id']]) == null) {
                $entityManager->remove($store);
                $entityManager->flush($store);
                $result = array("status" => 1, "status_message" => 'store supprimé');
            } else {
                if ($employeeRepository->findOneBy(['store_id' => $_REQUEST['id']]) != null || $stockRepository->findOneBy(['store_id' => $_REQUEST['id']]) != null) {
                    // If store is linked to employees or stocks
                    $result = array("status" => 0, "status_message" => 'Vous ne pouvez supprimer un store qui est encore lié à des employées et/ou des stocks');
                } else 
                    $result = array("status" => 0, "status_message" => 'store non existant');
            }
        } else {
            // If no ID is provided
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez supprimer tous les stores');
        }
        echo json_encode($result);
        break;
    
    case "PUT":
        // Handle PUT requests (Update a store)
        $form_data = json_encode(file_get_contents("php://input"));
        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }
        if (!empty($_REQUEST['id'])) {
            $store = $myRepository->find($_REQUEST['id']);
            if ($store != null) {
                if (!empty($_PUT['name'])) $store->setStoresName(urldecode($_PUT['name']));
                if (!empty($_PUT['phone'])) $store->setStoresPhone($_PUT['phone']);
                if (!empty($_PUT['email'])) $store->setStoresEmail(urldecode($_PUT['email']));
                if (!empty($_PUT['street'])) $store->setStoresStreet(urldecode($_PUT['street']));
                if (!empty($_PUT['city'])) $store->setStoresCity(urldecode($_PUT['city']));
                if (!empty($_PUT['state'])) $store->setStoresState(urldecode($_PUT['state']));
                if (!empty($_PUT['code'])) $store->setStoresCode(urldecode($_PUT['code']));
                $entityManager->flush();
                $result = $store;
            } else {
                $result = array("status" => 0, "status_message" => 'store non existant');
            }
        } else {
            // If no ID is provided
            $result = array("status" => 0, "status_message" => 'Vous ne pouvez modifier tous les stores');
        }
        echo json_encode($result);
        break;
}
