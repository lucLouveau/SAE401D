<?php
// Allow cross-origin requests and define allowed methods and headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, X-Requested-Width");

// Include bootstrap file to initialize the entity manager
require __DIR__ . '/../model/bootstrap.php';

use Entity\Categories;
use Entity\Products;

// Get repositories for Products and Categories entities
$ProductsRepository = $entityManager->getRepository(Products::class);
$myRepository = $entityManager->getRepository(Categories::class);

// Get the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Switch based on the request method
switch ($request_method) {
    case "GET":
        // Handle GET requests
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            // If ID parameter is provided
            if (isset($_REQUEST['reche']) && !empty($_REQUEST['reche'])) {
                // If 'reche' parameter is provided, search for products by category ID
                $result = $ProductsRepository->findBy(["category_id" => $_REQUEST['id']]);
            } else {
                // Otherwise, fetch category by ID
                $id = (int)$_REQUEST['id'];
                $result = $entityManager->find(Categories::class, $id);
                if ($result == null) {
                    // If category does not exist, return an error message
                    $result = array("status" => 0, "status_message" => 'No category found with this ID');
                }
            }
        } else {
            // If no ID parameter is provided, fetch all categories
            $result = $myRepository->findAll();
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "POST":
        // Handle POST requests to create a new category
        if (!empty($_POST['name'])) {
            // If name parameter is provided, create a new category entity
            $category = new Categories();
            $category->setCategoryName($_POST['name']);
            $entityManager->persist($category);
            $entityManager->flush();
            $result = $category;
        } else {
            // If name parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'Missing parameter');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "DELETE":
        // Handle DELETE requests to delete a category
        if (!empty($_REQUEST['id'])) {
            // If ID parameter is provided, attempt to delete the category
            $category = $myRepository->find($_REQUEST['id']);
            if ($category != null && $ProductsRepository->findOneBy(['category_id' => $_REQUEST['id']]) == null) {
                // If category exists and is not associated with any products, delete it
                $entityManager->remove($category);
                $entityManager->flush($category);
                $result = array("status" => 1, "status_message" => 'Category deleted');
            } else {
                if ($ProductsRepository->findOneBy(['category_id' => $_REQUEST['id']]) != null) {
                    // If category is associated with products, return an error message
                    $result = array("status" => 0, "status_message" => 'Cannot delete a category that is still linked to products');
                } else $result = array("status" => 0, "status_message" => 'Category does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot delete all categories');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "PUT":
        // Handle PUT requests to update a category
        $form_data = json_encode(file_get_contents("php://input"));
        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }
        if (!empty($_REQUEST['id'])) {
            // If ID parameter is provided, fetch the category
            $category = $myRepository->find($_REQUEST['id']);
            if ($category != null) {
                // If category exists, update its name
                if (!empty($_PUT['name'])) $category->setCategoryName(urldecode($_PUT['name']));
                $entityManager->flush();
                $result = $category;
            } else {
                // If category does not exist, return an error message
                $result = array("status" => 0, "status_message" => 'Category does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot modify all categories');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;
}
?>
