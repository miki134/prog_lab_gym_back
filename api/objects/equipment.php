<?php
require_once 'object.php';
class Equipment extends Obj
{
    protected $table_name = "equipment";

    public $id;
    public $name;
    public $length;
    public $height;
    public $width;
    public $weight;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getEquipment()
    {
        $sql = "SELECT `name`, `length`, `height`, `width`, `weight` ,`description` FROM "
            . $this->table_name;

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $arr = array();
        if ($stmt->rowCount() >= 1) {

            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $rowArr = array(
                    $row['name'],
                    $row['length'],
                    $row['height'],
                    $row['width'],
                    $row['weight'],
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
                name=:name, length=:length, height=:height, width=:width, weight=:weight, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->length = htmlspecialchars(strip_tags($this->length));
        $this->height = htmlspecialchars(strip_tags($this->height));
        $this->width = htmlspecialchars(strip_tags($this->width));
        $this->weight = htmlspecialchars(strip_tags($this->weight));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":length", $this->length);
        $stmt->bindParam(":height", $this->height);
        $stmt->bindParam(":width", $this->width);
        $stmt->bindParam(":weight", $this->weight);
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
            . "    length varchar(10) not null, \n"
            . "    height varchar(10) not null, \n"
            . "    width varchar(10) not null, \n"
            . "    weight varchar(10) not null, \n"
            . "    description varchar(300) null, \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }
}
