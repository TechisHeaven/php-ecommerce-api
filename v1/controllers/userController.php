<?php 

require("db/connection.php");

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

}





?>