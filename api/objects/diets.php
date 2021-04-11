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
                meat=:meat, 
                description=:description";

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
            . "    quantityOfProducts int not null, \n"
            . "    numberOfMealsPerDay int not null, \n"
            . "    meat bool DEFAULT true, \n"
            . "    description varchar(300) DEFAULT 'Brak', \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }

    public function checkCredentials(&$message)
    {
        //name
        if (!preg_match('/^([a-z|A-Z|0-9|\s]{4,30})$/', $this->name)) {
            $message = "Nazwa diety musi mieć długość od 4 do 30 znaków, możesz używać tylko liter, cyfr i spacji!";
            return false;
        }

        //quantityOfProducts
        if (!preg_match('/^([0-9]{1,2})$/', $this->quantityOfProducts)) {
            $message = "Ilosc skladnikow musi byc liczba calkowita z przedzialu od 1 do 100!";
            return false;
        }
        //numberOfMealsPerDay
        if (!preg_match('/^([0-9]{1,2})$/', $this->numberOfMealsPerDay)) {
            $message = "Pole 'Ilosc posilkow dziennie' musi byc liczba calkowita z przedzialu od 1 do 100!";
            return false;
        }
        //meat
        if (!preg_match('/^([0|1]{1})$/', $this->meat)) {
            $message = "Zawartosc miesa? Tak czy Nie?";
            return false;
        }

        //description
        if (!preg_match('/^([a-z|A-Z|0-9|\s]{0,300})$/', $this->description)) {
            $message = "Opis musi mieć długość od 0 do 300 znaków, możesz używać tylko liter, cyfr i spacji!";
            return false;
        }

        return true;
    }
}
