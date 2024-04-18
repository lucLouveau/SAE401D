<?php
// Allow cross-origin requests and define allowed methods and headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, X-Requested-Width");

// Include bootstrap file to initialize the entity manager
require __DIR__ . '/../model/bootstrap.php';

use Entity\Employees;
use Entity\Stores;

// Get repository for Employees entity
$myRepository = $entityManager->getRepository(Employees::class);

// Get the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Switch based on the request method
switch ($request_method) {
    case "GET":
        // Handle GET requests
        if (isset($_REQUEST['id']) && (!empty($_REQUEST['id']) || $_REQUEST['id'] == 0)) {
            // If ID parameter is provided, fetch employee by ID
            $id = (int)$_REQUEST['id'];
            $result = $entityManager->find(Employees::class, $id);
            if ($result == null) {
                // If employee does not exist, return an error message
                $result = array("status" => 0, "status_message" => 'No employee found with this ID');
            }
        } elseif (isset($_REQUEST['email']) && (!empty($_REQUEST['email']))) {
            // If email parameter is provided, search for employee by email
            $employee = $myRepository->findBy(['employee_email' => $_REQUEST['email']]);
            $result = $employee;
        } elseif (isset($_REQUEST['store']) && (!empty($_REQUEST['store']))) {
            // If store parameter is provided, search for employees by store ID and role
            $employee = $myRepository->findBy(['store_id' => $_REQUEST['store'], "employee_role" => "employee"]);
            $result = $employee;
        } else {
            // If no specific parameters are provided, fetch all employees
            $result = $myRepository->findAll();
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "POST":
        // Handle POST requests to create a new employee
        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role']) && $myRepository->findOneBy(["employee_email" => $_POST["email"]]) == null) {
            // If all required parameters are provided and email is unique, create a new employee entity
            $employee = new Employees();
            if (!empty($_POST['store'])) {
                // If store parameter is provided, associate employee with a store
                $repositoryStore = $entityManager->getRepository(Stores::class);
                $store = $repositoryStore->find($_POST['store']);
                $employee->setStoreId($store);
            };
            // Set employee details
            $employee->setEmployeeName($_POST['name']);
            $employee->setEmployeeEmail($_POST['email']);
            $employee->setEmployeePassword($_POST['password']);
            $employee->setEmployeeRole($_POST['role']);

            // Persist and flush the employee entity
            $entityManager->persist($employee);
            $entityManager->flush();
            $result = $employee;
        } else {
            // If any required parameter is missing or email is not unique, return an error message
            if ($myRepository->findOneBy(["employee_email" => $_POST["email"]]) != null) {
                $result = array("status" => 0, "status_message" => 'Employee already exists');
            } else $result = array("status" => 0, "status_message" => 'Missing parameter');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "DELETE":
        // Handle DELETE requests to delete an employee
        if (!empty($_REQUEST['id'])) {
            // If ID parameter is provided, attempt to delete the employee
            $employee = $myRepository->find($_REQUEST['id']);
            if ($employee != null) {
                // If employee exists, delete it
                $entityManager->remove($employee);
                $entityManager->flush($employee);
                $result = array("status" => 1, "status_message" => 'Employee deleted');
            } else {
                // If employee does not exist, return an error message
                $result = array("status" => 0, "status_message" => 'Employee does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot delete all employees');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;

    case "PUT":
        // Handle PUT requests to update an employee
        $form_data = json_encode(file_get_contents("php://input"));
        $form_data = substr($form_data, 1, strlen($form_data) - 2);
        $param = explode("&", $form_data);
        foreach ($param as $item) {
            $value = explode("=", $item);
            $_PUT[$value[0]] = $value[1];
        }
        if (!empty($_REQUEST['id'])) {
            // If ID parameter is provided, fetch the employee
            $employee = $myRepository->find($_REQUEST['id']);
            if ($employee != null) {
                // If employee exists, update its details
                if (!empty($_PUT['store'])) {
                    // If store parameter is provided, associate employee with a store
                    $repositoryStore = $entityManager->getRepository(Stores::class);
                    $store = $repositoryStore->find($_PUT['store']);
                    $employee->setStoreId($store);
                };
                if (!empty($_PUT['name'])) $employee->setEmployeeName(urldecode($_PUT['name']));
                if (!empty($_PUT['email'])) $employee->setEmployeeEmail(urldecode($_PUT['email']));
                if (!empty($_PUT['password'])) $employee->setEmployeePassword(urldecode($_PUT['password']));
                if (!empty($_PUT['role'])) $employee->setEmployeeRole(urldecode($_PUT['role']));
                // Flush the updated employee entity
                $entityManager->flush();
                $result = $employee;
            } else {
                // If employee does not exist, return an error message
                $result = array("status" => 0, "status_message" => 'Employee does not exist');
            }
        } else {
            // If ID parameter is missing, return an error message
            $result = array("status" => 0, "status_message" => 'You cannot modify all employees');
        }
        // Output the result as JSON
        echo json_encode($result);
        break;
}
?>
