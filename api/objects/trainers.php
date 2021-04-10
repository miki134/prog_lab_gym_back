<?php
require_once 'object.php';
class Trainers extends Obj
{
    protected $table_name = "trainers";

    public $id;
    public $name;
    public $surname;
    public $birthday;
    public $phone;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTrainers()
    {
        $sql = "SELECT `name`, `surname`, `birthday`, `phone` ,`description` FROM "
            . $this->table_name;

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $arr = array();
        if ($stmt->rowCount() >= 1) {

            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $rowArr = array(
                    $row['name'],
                    $row['surname'],
                    $row['birthday'],
                    $row['phone'],
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
                name=:name, surname=:surname, birthday=:birthday, phone=:phone, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->birthday = htmlspecialchars(strip_tags($this->birthday));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":birthday", $this->birthday);
        $stmt->bindParam(":phone", $this->phone);
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
            . "    surname varchar(30) not null, \n"
            . "    birthday date not null, \n"
            . "    phone varchar(11) not null, \n"
            . "    description varchar(300) DEFAULT 'Brak', \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }
}
