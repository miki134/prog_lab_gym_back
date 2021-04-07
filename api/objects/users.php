<?php
require_once 'object.php';
class Users extends Obj {
    protected $table_name = "users";

    public $id;
    public $name;
    public $surname;
    public $email;
    public $password;
    public $role;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getUsers(){
        $query = "SELECT * FROM `users`";
        // echo $this->conn;
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        
        return $stmt;
    }

    public function getUser(){
        $sql = "SELECT `name`, `surname`, `email`, `password`, `role` FROM "
        .$this->table_name.
        " WHERE `email` LIKE '".$this->email."'";

        $stmt = $this->conn->prepare( $sql );
    
        $stmt->bindParam(1, $this->id);
    
        $stmt->execute();

        if($stmt->rowCount() >= 1){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            return true;
        }
        return false; 
    }

    public function create(){

        $query = "INSERT INTO " 
            .$this->table_name.
            " SET
                name=:name, surname=:surname, email=:email, password=:password, role=:role";
    
        $stmt = $this->conn->prepare($query);
        
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->role=htmlspecialchars(strip_tags($this->role));
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
    
        if($stmt->execute()){
            return true;
        }
        return false; 
    }

    public function ifExists(){
        $sql = "SELECT * FROM " .$this->table_name. " WHERE `email` LIKE '".$this->email."'";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        
        if($stmt->rowCount() >= 1){
            return true;
        }
        return false; 
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS  `users`( \n"
        . "    id int AUTO_INCREMENT, \n"
        . "    name varchar(30), \n"
        . "    surname varchar(30), \n"
        . "    email varchar(30), \n"
        . "    password varchar(32),\n"
        . "    role ENUM('admin', 'trainer', 'client', 'employee'), \n"
        . "    PRIMARY Key(id)\n"
        . ")";

        $stmt = $this->conn->exec($sql);
        
        return $stmt;
    }
}