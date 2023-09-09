<?php 

require("db/connection.php");
require("functions/error.inc.function.php");
require('functions/jwtHandle.function.php');

class UserController{

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	//get all users
	public function getAllUsers() {
		
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
				$query = "SELECT * FROM `users`";
				$result = $this->mysqli->query($query);
				if ($result) {
					$users = $result->fetch_all(MYSQLI_ASSOC);
					return $users;
				} else {
					$custom =CustomError(404, 'Users Not Found');
					echo json_encode($custom);
					die();
					return false;
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

	//create user 

	public function createUser(){
		//req body get
		$request_body = json_decode(file_get_contents('php://input'));
		if(!isset($request_body)){
			$custom = CustomError(200, 'Null Request Body');
			echo json_encode($custom);
			die();
		};

		// Validate email and password
		if (empty($request_body->email) || empty($request_body->password) || empty($request_body->full_name)) {
			$custom =CustomError(401, 'Email and password are required.');
			echo json_encode($custom);
			die();
        }
		
		$email = customRealEscapeString($this->mysqli, $request_body->email);
		$password = customRealEscapeString($this->mysqli,$request_body->password);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$full_name = customRealEscapeString($this->mysqli,$request_body->full_name);
		$address = isset($request_body->address) && $request_body->address !== null ? customRealEscapeString($this->mysqli,$request_body->address) : '';
		$city = isset($request_body->city) && $request_body->city !== null ? customRealEscapeString($this->mysqli,$request_body->city) : '';
		$state = isset($request_body->state) && $request_body->state !== null ? customRealEscapeString($this->mysqli,$request_body->state) : '';
		$postal_code = isset($request_body->postal_code) && $request_body->postal_code !== null ? customRealEscapeString($this->mysqli,$request_body->postal_code) : '';
		$country =isset($request_body->country) && $request_body->country !== null ? customRealEscapeString($this->mysqli,$request_body->country) : '';
		$phone = isset($request_body->phone) && $request_body->phone !== null ?customRealEscapeString($this->mysqli,$request_body->phone) : '';
		
		$EmailCheckQuery = "SELECT * FROM `users` where `email`='$email'";
		$result = $this->mysqli->query($EmailCheckQuery);

		if($result && $result->num_rows > 0){
			$custom = CustomError(403, 'Email Already Exists');
			echo json_encode($custom);
			die();
		}
		
		$query = "insert into `users` (`email`, `password`, `full_name`, `address`, `city`, `state`, `postal_code`, `country`, `phone`) values ( '$email','$password' , '$full_name', '$address', '$city', '$state', '$postal_code', '$country', '$phone')";
		$result = $this->mysqli->query($query);

		if ($result) {
			$users = $result->fetch_array(MYSQLI_ASSOC);
			$jwtToken = createJwtToken($users['user_id'], 600);
			return array("message" => "Success created user", 'status'=>201 , 'jwttoken'=>$jwtToken);
		} else {
			return false;
		}
	}


    //get specific user for login user

	public function getUserByEmail(){
		$request_body = json_decode(file_get_contents('php://input'));
		// Validate email and password
		if (empty($request_body->email) || empty($request_body->password)) {
			$custom =CustomError(401, 'Email and password are required.');
			echo json_encode($custom);
			die();
		}

		$email = customRealEscapeString($this->mysqli, $request_body->email);
		$password = customRealEscapeString($this->mysqli,$request_body->password);


		$query = "select * from `users` where `email` = '$email'";
		$result = $this->mysqli->query($query);


		if(!$result && $result->num_rows <= 0){
			$custom =CustomError(404, 'Email Not Exists.');
			echo json_encode($custom);
			die();
		}

		if($result){
			$users = $result->fetch_array(MYSQLI_ASSOC);


			$VerifyPassword = password_verify($password, $users['password']);
			if(!$VerifyPassword){
				$custom =CustomError(401, 'Password is not valid');
				echo json_encode($custom);
				die();
			}
			$jwtToken = createJwtToken($users['user_id'], 60*60);
			

			return array("message" => "Success Login", 'status'=>200 ,'jwtToken'=> $jwtToken );
	
		}
		
	}

	//update user
	public function updateUser(){
		$request_body = json_decode(file_get_contents('php://input'));
		// Validate email and password
		if (empty($request_body->user_id)) {
			$custom =CustomError(404, 'user id are required.');
			echo json_encode($custom);
			die();
		}
		//get type of user update type
		$type = $_GET['type'];

		if($type==='address'){
			$user_id = $request_body->user_id;
			$address = isset($request_body->address) ? customRealEscapeString($this->mysqli, $request_body->address): "";
			$city = isset($request_body->city) ? customRealEscapeString($this->mysqli, $request_body->city) : "";
			$state = isset($request_body->state) ? customRealEscapeString($this->mysqli, $request_body->state): "";
			echo $state;
			$postal_code = isset($request_body->postal_code) ? customRealEscapeString($this->mysqli, $request_body->postal_code): "";
			$country = isset($request_body->country) ? customRealEscapeString($this->mysqli, $request_body->country): "";
			//.sql query
			$query = "update `users` set address = '$address', city = '$city', state = '$state', postal_code = '$postal_code', country='$country' where `user_id` = '$user_id'";

			//run sql query 
			$result = $this->mysqli->query($query);
			
			
			if(!$result && $result->num_rows <= 0){
			$custom =CustomError(404, 'Failed to fetch.');
			echo json_encode($custom);
			die();
			}

			if($result){
				$user_query = "select * from `users` where `user_id` = '$user_id'";
				$user_result = $this->mysqli->query($user_query);
				$userData = $user_result->fetch_array(MYSQLI_ASSOC);

				if(!$userData){
					$custom =CustomError(404, 'User not found.');
					echo json_encode($custom);
				}
				
				return array("message" => "Success updated user data", 'status'=>200, 'user_data'=>$userData);
			}
		}

		// $full_name = customRealEscapeString($this->mysqli, $request_body->full_name);




	}

}





?>