<?php
class Users{
  
    private $conn;
    private $table_name = "users";
  
    public $id;
    public $surname;
    public $name;
    // public $description;
    // public $price;
    // public $category_id;
    // public $category_name;
    // public $created;
  
    public function __construct($db){
        $this->conn = $db;
    }

    public function getUsers(){
        $query = "SELECT * FROM `users`";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        
        return $stmt;
    }

    public function readOne(){
        $sql = "SELECT `name`, `surname` FROM `users` WHERE id = ".$this->id;
    
        // prepare query statement
        $stmt = $this->conn->prepare( $sql );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->name = $row['name'];
        $this->surname = $row['surname'];
        // $this->price = $row['price'];
        // $this->description = $row['description'];
        // $this->category_id = $row['category_id'];
        // $this->category_name = $row['category_name'];
    }

    public function create(){
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, surname=:surname ";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        // $this->price=htmlspecialchars(strip_tags($this->price));
        // $this->description=htmlspecialchars(strip_tags($this->description));
        // $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        // $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        // $stmt->bindParam(":price", $this->price);
        // $stmt->bindParam(":description", $this->description);
        // $stmt->bindParam(":category_id", $this->category_id);
        // $stmt->bindParam(":created", $this->created);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false; 
    }
}