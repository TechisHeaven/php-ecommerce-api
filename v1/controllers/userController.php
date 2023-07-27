<?php 

require("db/connection.php");
require("functions/error.inc.function.php");

class UserController{

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}

	public function getAllUsers() {
		$query = "SELECT * FROM `users`";
		$result = $this->mysqli->query($query);

		if ($result) {
			$users = $result->fetch_all(MYSQLI_ASSOC);
			return $users;
		} else {
			return false;
		}
	}



	public function createUser(){
		$request_body = json_decode(file_get_contents('php://input'));
		if(!isset($request_body)){
			$custom = CustomError(200, 'Null Request Body');
			echo json_encode($custom);
			die();
		};

		// Validate email and password
		if (empty($request_body->email) || empty($request_body->password)) {
            return array("error" => "Email and password are required.");
        }
		
		$email = customRealEscapeString($this->mysqli, $request_body->email);
		$password = customRealEscapeString($this->mysqli,$request_body->password);
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
			return array("message" => "Success created user", 'status'=>201 );
		} else {
			return false;
		}
	}

}





?>