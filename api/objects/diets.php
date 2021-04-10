<?php
require_once 'object.php';
class Diets extends Obj
{
    protected $table_name = "diets";

    public $id;
    public $name;
    public $quantityOfProducts;
    public $numberOfMealsPerDay;
    public $meat;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getDiets()
    {
        $sql = "SELECT `name`, `quantityOfProducts`, `numberOfMealsPerDay`, `meat`, `description` FROM "
            . $this->table_name;

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $arr = array();
        if ($stmt->rowCount() >= 1) {

            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $rowArr = array(
                    $row['name'],
                    $row['quantityOfProducts'],
                    $row['numberOfMealsPerDay'],
                    $row['meat'],
                    $row['description']
                );
                array_push($arr, $rowArr);
            }
            $stmt->closeCursor();
            return $arr;
        }
        return false;
    }

    public function create()
    {

        $query = "INSERT INTO "
            . $this->table_name .
            " SET
                name=:name, 
                quantityOfProducts=:quantityOfProducts, 
                numberOfMealsPerDay=:numberOfMealsPerDay, 
                meat=:meat, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantityOfProducts = htmlspecialchars(strip_tags($this->quantityOfProducts));
        $this->numberOfMealsPerDay = htmlspecialchars(strip_tags($this->numberOfMealsPerDay));
        $this->meat = htmlspecialchars(strip_tags($this->meat));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":quantityOfProducts", $this->quantityOfProducts);
        $stmt->bindParam(":numberOfMealsPerDay", $this->numberOfMealsPerDay);
        $stmt->bindParam(":meat", $this->meat);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            $stmt->closeCursor();
            return true;
        }
        return false;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS  " . $this->table_name . " ( \n"
            . "    id int AUTO_INCREMENT, \n"
            . "    name varchar(30) not null, \n"
            . "    quantityOfProducts varchar(10) not null, \n"
            . "    numberOfMealsPerDay varchar(10) not null, \n"
            . "    meat bool DEFAULT true, \n"
            . "    description varchar(300) DEFAULT 'Brak', \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }
}
