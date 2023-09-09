<?php 

require("db/connection.php");
require("functions/error.inc.function.php");
require('functions/jwtHandle.function.php');

class BillboardController{

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	//create billboard
    public function createBillboard(){
        
		$header = apache_request_headers();
		if(isset($header['Authorization'])&& $header['Authorization']){
			$authHeader = $header['Authorization'];
			$token = str_replace('Bearer ', '', $authHeader);
			try {
				$jwtDecoded = verifyJwtToken($token);
				if(!$jwtDecoded || $jwtDecoded===null){
					$custom =CustomError(401, 'Token Expired or Invalid');
					echo json_encode($custom);
					die();
				}
				if($jwtDecoded){
					
					//req body get
					$request_body = json_decode(file_get_contents('php://input'));
					if(!isset($request_body)){
					$custom = CustomError(200, 'Null Request Body');
					echo json_encode($custom);
					die();
					};

				// Validate email and password
				if (empty($request_body->billboard_name) || empty($request_body->image_url)) {
					$custom =CustomError(401, 'billboard_name and image_url are required.');
					echo json_encode($custom);
					die();
        		}
		
					$billboard_name = customRealEscapeString($this->mysqli, $request_body->billboard_name);
					$image_url = customRealEscapeString($this->mysqli, $request_body->image_url);


                    $query = "insert into billboards (billboard_name, image_url)
					values ('$billboard_name', '$image_url')";
					$result = $this->mysqli->query($query);
					if($result){
						return array("message" => "Success created billboards", 'status'=>201);
					}

			}
				// ... Your further code logic ...
			} catch (Exception $e) {
				// If the custom exception is caught (token expired), handle the error here
				if ($e->getCode() === 401) {
					echo 'Error: ' . $e->getMessage();
					// Perform any specific actions for token expiration, e.g., redirect to login page
				} else {
					// Handle other exceptions (if any) here
					echo 'Error: Something went wrong';
				}
			}

		
			
		}
		else{
			$custom =CustomError(401, 'Authorized User.');
			echo json_encode($custom);
			die();
		}
    }
	//get all billboard with auth id
	public function getAllBillboard(){
		$header = apache_request_headers();
		if(isset($header['Authorization'])&& $header['Authorization']){
			$authHeader = $header['Authorization'];
			$token = str_replace('Bearer ', '', $authHeader);
			try {
				$jwtDecoded = verifyJwtToken($token);
				if(!$jwtDecoded || $jwtDecoded===null){
					$custom =CustomError(401, 'Token Expired or Invalid');
					echo json_encode($custom);
					die();
				}
				if($jwtDecoded){

                    $query = "select * from billboards";
					$result = $this->mysqli->query($query);
					if($result){
						$billboards = $result->fetch_all(MYSQLI_ASSOC);
						return array("message" => "Success get billboards", 'status'=>201, 'billboards'=>$billboards);
					}

			}
				// ... Your further code logic ...
			} catch (Exception $e) {
				// If the custom exception is caught (token expired), handle the error here
				if ($e->getCode() === 401) {
					echo 'Error: ' . $e->getMessage();
					// Perform any specific actions for token expiration, e.g., redirect to login page
				} else {
					// Handle other exceptions (if any) here
					echo 'Error: Something went wrong';
				}
			}

		
			
		}
		else{
			$custom =CustomError(401, 'Authorized User.');
			echo json_encode($custom);
			die();
		}
	}

	//get billboard by id
	public function getBillboardById(){
		// $header = apache_request_headers();
		// if(isset($header['Authorization'])&& $header['Authorization']){
		// 	$authHeader = $header['Authorization'];
		// 	$token = str_replace('Bearer ', '', $authHeader);
			try {
				// $jwtDecoded = verifyJwtToken($token);
				// if(!$jwtDecoded || $jwtDecoded===null){
				// 	$custom =CustomError(401, 'Token Expired or Invalid');
				// 	echo json_encode($custom);
				// 	die();
				// }
				// if($jwtDecoded){
					// $billboard_id = customRealEscapeString($this->mysqli, $request_body->billboard_id);
					$billboard_id = isset($_GET['id']) ? $_GET['id'] : '';
					if($billboard_id != null && isset($billboard_id)){
						$query = "select * from billboards where billboard_id = '$billboard_id'";
						$result = $this->mysqli->query($query);
						if($result){
							$billboards = $result->fetch_assoc();
							return array("message" => "Success get billboards", 'status'=>201, 'billboard'=>$billboards);
						}
					}
					else{
						$custom =CustomError(401, 'Billboard ID not found');
						echo json_encode($custom);
						die();
					}
                 

			// }
				// ... Your further code logic ...
			} catch (Exception $e) {
				// If the custom exception is caught (token expired), handle the error here
				if ($e->getCode() === 401) {
					echo 'Error: ' . $e->getMessage();
					// Perform any specific actions for token expiration, e.g., redirect to login page
				} else {
					// Handle other exceptions (if any) here
					echo 'Error: Something went wrong';
				}
			}

		
			
		// }
		// else{
		// 	$custom =CustomError(401, 'Authorized User.');
		// 	echo json_encode($custom);
		// 	die();
		// }
	}

	//update billboard with id by query and data by body
	public function updateBillboard(){
		$header = apache_request_headers();
		if(isset($header['Authorization'])&& $header['Authorization']){
			$authHeader = $header['Authorization'];
			$token = str_replace('Bearer ', '', $authHeader);
			try {
				$jwtDecoded = verifyJwtToken($token);
				if(!$jwtDecoded || $jwtDecoded===null){
					$custom =CustomError(401, 'Token Expired or Invalid');
					echo json_encode($custom);
					die();
				}
				if($jwtDecoded){
					// $billboard_id = customRealEscapeString($this->mysqli, $request_body->billboard_id);
					$billboard_id = isset($_GET['id']) ? $_GET['id'] : '';
					$request_body = json_decode(file_get_contents('php://input'));
					//request body error caught
					if(!$request_body || $request_body===null){
						$custom =CustomError(404, "Null Request Body id $billboard_id");
						echo json_encode($custom);
						die();
					}

					$billboard_name = $request_body->billboard_name;
					$image_url = $request_body->image_url;

					if($billboard_id != null && isset($billboard_id)){
						$query = "update billboards set billboard_name='$billboard_name', image_url='$image_url' where billboard_id = '$billboard_id'";
						$result = $this->mysqli->query($query);
						if($result){
							$billboardQuery = "select * from billboards where billboard_id='$billboard_id'";
							$resultData = $this->mysqli->query($billboardQuery);
							$billboards = $resultData->fetch_assoc();
							return array("message" => "Success Updated billboards", 'status'=>200, 'billboard'=>$billboards);
						}
					}
					else{
						$custom =CustomError(401, 'Billboard ID not found');
						echo json_encode($custom);
						die();
					}
                 

			}
				// ... Your further code logic ...
			} catch (Exception $e) {
				// If the custom exception is caught (token expired), handle the error here
				if ($e->getCode() === 401) {
					echo 'Error: ' . $e->getMessage();
					// Perform any specific actions for token expiration, e.g., redirect to login page
				} else {
					// Handle other exceptions (if any) here
					echo 'Error: Something went wrong';
				}
			}

		
			
		}
		else{
			$custom =CustomError(401, 'UnAuthorized User.');
			echo json_encode($custom);
			die();
		}
	}



}

?>