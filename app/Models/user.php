<?php

class UserModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT id, user_name, user_email, user_password, user_role 
             FROM userData WHERE user_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createUser($fname, $lname, $email, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO userData (user_name, user_email, user_password, user_role) 
             VALUES (?, ?, ?, ?)"
        );

        $fullName = $fname . " " . $lname;
        $stmt->bind_param("ssss", $fullName, $email, $hashedPassword, $role);

        return $stmt->execute();
    }
}?>