<?php
    class User {
        //  Connection
        private $conn;
        //  Table
        private $db_table = "users";
        //  Columns
        public $id;
        public $first_name;
        public $last_name;
        public $email;
        public $password;
        public $age;
        public $gender;
        public $created_at;
        public $updated_at;
        //  DB Connection
        public function __construct($db) {
            $this -> conn = $db;
        }
        //  Get all Users
        public function getUsers() {
            // Create a Query to fetch data from the database
            $sqlQuery = "SELECT id, first_name, last_name, email, password, age, gender, created_at, updated_at
            FROM ".$this->db_table."";
            // Prepare the database connection
            $stmt = $this->conn->prepare($sqlQuery);
            // Execute the statement to the database
            $stmt->execute();
            // Return whatever the statement says
            return $stmt;
        }
    

    //Create

    public function registerUser()
    {
        $sqlQuery = "INSERT INTO
                        ". $this->db_table . "
                        SET 
                            first_name = :first_name,
                            last_name = :last_name,
                            email = :email,
                            password = :password,
                            age = :age,
                            gender = :gender";
        
        $stmt = $this->conn->prepare($sqlQuery);

        //sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        

        // bind data
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":gender", $this->gender);


        try
        {
            $stmt->execute();
            print_r("Succes already executed");
            return true;
        }
        catch (PDOException $error)
        {
            $errorCode = (json_encode($error->errorInfo[1]));
            if($errorCode == 1062)
            {
                http_response_code(400);
                return print_r(['status' => 'false', 'message' => 'Email is already in use.']);
            }
            else
            {
                return print_r(['status' => 'false', 'message' => 'ERROR']);

            }
        }

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }
}
?>
