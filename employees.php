<?php
include("connection.php");
include("auth.php");

//Auth Start
$auth = new authObj();
$isAuthorized = $auth->authenticate();
if(!$isAuthorized)
{
	header("HTTP/1.0 401");
	exit;
}
//Auth End


$db = new dbObj();
$connection = $db->getConnstring();

$request_method = $_SERVER["REQUEST_METHOD"];
switch($request_method)
{
	case 'GET':
		if(!empty($_GET["id"]))
		{
			$id = intval($_GET["id"]);
			getEmployee($id);
		}
		else
		{
			getEmployees();
		}
		break;
	case 'POST':
		$data = json_decode(file_get_contents('php://input'), true);
		insertEmployee($data["name"], $data["email"], $data["profilePic"]);
		break;
	case 'PUT':
		$id = intval($_GET["id"]);
		$data = json_decode(file_get_contents('php://input'), true);
		updateEmployee($id, $data["name"]);
		break;
	case 'DELETE':
		$id = intval($_GET["id"]);
		deleteEmployee($id);
		break;
	default:
		header("HTTP/1.0 405 Method Not Implemented");
		break;
}
	
function getEmployees()
{
	global $connection;
	$sql = "SELECT * from employees";
	$result = $connection->query($sql);
	$response = array();
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
	        array_push($response, $row);
	    }
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function getEmployee($id)
{
	global $connection;
	$sql = "SELECT * from employees WHERE id='".$id."'";
	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	header('Content-Type: application/json');
	echo json_encode($row);
}


function insertEmployee($name, $email, $profilePic)
{
	global $connection;
	$sql = "INSERT INTO employees (name, email, profile_pic) VALUES ('".$name."','".$email."','".$profilePic."')";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		header("HTTP/1.0 201");
		$response = array(
	            'status' => 1,
	            'status_message' => 'Employee Added Successfully.'
        	);		
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'Employee Addition Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function updateEmployee($id, $name)
{
	global $connection;
	$sql = "UPDATE employees SET name='".$name."' WHERE id='".$id."'";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		$response = array(
	            'status' => 1,
	            'status_message' => 'Employee Updated Successfully.'
        	);		
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'Employee Updation Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}


function deleteEmployee($id)
{
	global $connection;
	$sql = "DELETE from employees WHERE id='".$id."'";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		header("HTTP/1.0 204");	
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'Employee Deletion Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}










?>