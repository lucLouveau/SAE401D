<?php
// Set CORS headers to allow cross-origin requests and define allowed methods and headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, X-Requested-Width");

// Include the bootstrap file to initialize the entity manager
require __DIR__ . '/../model/bootstrap.php';

// Import the necessary entity classes
use Entity\Brands;
use Entity\Products;

// Get repositories for Brands and Products entities
$ProductsRepository = $entityManager->getRepository(Products::class);
$myRepository = $entityManager->getRepository(Brands::class);

// Get the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Handle different request methods
switch ($request_method) {
    // Handle GET requests
    case "GET":
        // Check if an ID is provided
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            // If ID provided, fetch either a specific brand or products by brand ID
            if (isset($_REQUEST['reche']) && !empty($_REQUEST['reche'])) {
                $result = $ProductsRepository->findBy(["brand_id" => $_REQUEST['id']]);
            } else {
                $id = (int)$_REQUEST['id'];
                $result = $entityManager->find(Brands::class, $id);
                // Check if brand with provided ID exists
                if ($result == null) {
                    $result = array("status" => 0, "status_message" => 'No brand known with this ID');
                }
            }
        } else {
            // If no ID provided, fetch all brands
            $result = $myRepository->findAll();
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    // Handle POST requests
    case "POST":
        // Check if name parameter is provided
        if (!empty($_POST['name'])) {
            // Create a new brand entity, set its name, persist it to the database, and flush
            $brand = new Brands();
            $brand->setBrandsName($_POST['name']);
            $entityManager->persist($brand);
            $entityManager->flush();
            $result = $brand;
        } else {
            // If name parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'missing parameter');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    // Handle DELETE requests
    case "DELETE":
        // Check if ID parameter is provided
        if (!empty($_REQUEST['id'])) {
            // Find the brand entity by ID
            $brand = $myRepository->find($_REQUEST['id']);
            // Check if brand exists and if it is not associated with any products
            if ($brand != null && $ProductsRepository->findOneBy(['brand_id' => $_REQUEST['id']]) == null) {
                // Remove the brand entity and flush changes
                $entityManager->remove($brand);
                $entityManager->flush($brand);
                $result = array("status" => 1, "status_message" => 'brand deleted');
            } else {
                // If brand is associated with products, return an error message
                if ($ProductsRepository->findOneBy(['brand_id' => $_REQUEST['id']]) != null) {
                    $result = array("status" => 0, "status_message" => 'You cannot delete a brand that is still linked to products');
                } else $result = array("status" => 0, "status_message" => 'brand does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot delete all brands');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;
    // Handle PUT requests
    case "PUT":
        // Parse form data from request body
        $form_data = json_encode(file_get_contents("php://input"));
        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }
        // Check if ID parameter is provided
        if (!empty($_REQUEST['id'])) {
            // Find the brand entity by ID
            $brand = $myRepository->find($_REQUEST['id']);
            // Check if brand exists
            if ($brand != null) {
                // Update brand name if provided in the request
                // Decode the brand name and set it
                if (!empty($_PUT['name'])) $brand->setBrandsName(urldecode($_PUT['name']));
                // Flush changes to the database
                $entityManager->flush();
                $result = $brand;
            } else {
                // If brand does not exist, return an error message
                $result = array("status" => 0, "status_message" => 'brand does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot modify all brands');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;
}