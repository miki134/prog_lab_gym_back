<?php
require_once 'object.php';
class Workouts extends Obj
{
    protected $table_name = "workouts";

    public $id;
    public $name;
    public $lengthOfTime;
    public $quantityOfExercises;
    public $difficulty;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getWorkouts()
    {
        $sql = "SELECT `name`, `lengthOfTime`, `quantityOfExercises`, `difficulty`, `description` FROM "
            . $this->table_name;

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $arr = array();
        if ($stmt->rowCount() >= 1) {

            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $rowArr = array(
                    $row['name'],
                    $row['lengthOfTime'],
                    $row['quantityOfExercises'],
                    $row['difficulty'],
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
                name=:name, lengthOfTime=:lengthOfTime, quantityOfExercises=:quantityOfExercises, difficulty=:difficulty, weight=:weight, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->lengthOfTime = htmlspecialchars(strip_tags($this->lengthOfTime));
        $this->quantityOfExercises = htmlspecialchars(strip_tags($this->quantityOfExercises));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":lengthOfTime", $this->lengthOfTime);
        $stmt->bindParam(":quantityOfExercises", $this->quantityOfExercises);
        $stmt->bindParam(":difficulty", $this->difficulty);
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
            . "    lengthOfTime varchar(10) not null, \n"
            . "    quantityOfExercises int not null, \n"
            . "    difficulty int(1) not null, \n"
            . "    description varchar(300) DEFAULT 'Brak', \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }
}
