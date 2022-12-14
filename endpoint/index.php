<?php
session_start();
/*
* Designed and programmed by
* @Author: Francis A. Anlimah
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../bootstrap.php";

use Src\Controller\AdminController;

$expose = new AdminController();

$data = [];
$errors = [];

// All GET request will be sent here
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if ($_GET["url"] == "programs") {
        if (isset($_GET["type"])) {
            $t = 0;
            if ($_GET["type"] != "All") {
                $t = (int) $_GET["type"];
            }
            $result = $expose->fetchPrograms($t);
            if (!empty($result)) {
                $data["success"] = true;
                $data["message"] = $result;
            } else {
                $data["success"] = false;
                $data["message"] = "No result found!";
            }
        }
        die(json_encode($data));
    } elseif ($_GET["url"] == "get") {
    }

    // All POST request will be sent here
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_GET["url"] == "applicants") {
        if (isset($_POST["country"]) && isset($_POST["type"]) && isset($_POST["program"])) {
            $result = $expose->fetchApplicants($_POST["country"], $_POST["type"], $_POST["program"]);
            if (!empty($result)) {
                $data["success"] = true;
                $data["message"] = $result;
            } else {
                $data["success"] = false;
                $data["message"] = "No result found!";
            }
        } else {
        }
        die(json_encode($data));
    } elseif ($_GET["url"] == "get") {
    }
    // All PUT request will be sent here
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    die(json_encode($data));

    // All DELETE request will be sent here
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    die(json_encode($data));
} else {
    http_response_code(405);
}
