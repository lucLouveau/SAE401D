<?php
// Start a session
session_start();

// Require the bootstrap file to initialize necessary components
require __DIR__ . '/../model/bootstrap.php';

// Import the Employees entity
use Entity\Employees;

// Check if an action is specified
if (!empty($_REQUEST['action'])) {
    // Check if the request method is GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Capitalize the action name
        $action = ucfirst($_REQUEST['action']);

        // Define an array of all possible actions
        $allAction = array("Products", "Stocks", "Brands", "Stores", "Employees", "Categories");

        // Check if the action is 'Employees'
        if ($action == "Employees") {
            // Check if an API key is provided and valid
            if (!empty($_GET['apiKey']) && $_GET['apiKey'] == "e8f1997c763") {
                // Include the corresponding view file for Employees
                include_once('view/' . $action . '.php');
            } else {
                // Respond with an error message if the API key is missing or invalid
                $result = array("status" => 0, "status_message" => 'You need an api key to access employees');
                echo json_encode($result);
            }
        } elseif (in_array($action, $allAction) && $action != "Employees") {
            // For other actions, include the corresponding view file if it exists
            include_once('view/' . $action . '.php');
        } else {
            // Respond with an error message if the action does not exist
            $result = array("status" => 0, "status_message" => 'This action does not exist');
            echo json_encode($result);
        }
    } else {
        // For non-GET requests
        // Check if an API key is provided and valid
        if (!empty($_GET['apiKey']) && $_GET['apiKey'] == "e8f1997c763") {
            // Capitalize the action name
            $action = ucfirst($_REQUEST['action']);

            // Define an array of all possible actions
            $allAction = array("Products", "Stocks", "Brands", "Stores", "Employees", "Categories");

            // Check if the action is valid
            if (in_array($action, $allAction)) {
                // Include the corresponding view file for the action
                include_once('view/' . $action . '.php');
            } else {
                // Respond with an error message if the action does not exist
                $result = array("status" => 0, "status_message" => 'This action does not exist');
                echo json_encode($result);
            }
        } else {
            // Respond with an error message if the API key is missing or invalid
            $result = array("status" => 0, "status_message" => 'You need an API key to make modifications');
            echo json_encode($result);
        }
    }
} else {
    // Respond with an error message if no action is specified
    $result = array("status" => 0, "status_message" => 'No action specified');
    echo json_encode($result);
}
