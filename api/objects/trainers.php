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

    public function checkCredentials(&$message)
    {
        //name
        if (!preg_match('/^([a-z|A-Z]{4,30})$/', $this->name)) {
            $message = "Imie musi mieć długość od 4 do 30 znaków, możesz używać tylko liter!";
            return false;
        }

        //surname
        if (!preg_match('/^([a-z|A-Z]{4,30})$/', $this->surname)) {
            $message = "Nazwisko musi mieć długość od 4 do 30 znaków, możesz używać tylko liter!";
            return false;
        }

        //birthday
        if (!preg_match('/^([0-9]{4}-[0-9]{2}-[0-9]{2})$/', $this->birthday)) {
            $message = "Data urodzenia musi być w formacie rrrr-mm-dd!";
            return false;
        }

        $now  = date("Y-m-d", time());
        $then = date( "Y-m-d", strtotime( "$now -110 years" ) );

        if (strtotime($this->birthday) > strtotime($now) || strtotime($this->birthday) < strtotime($then) ) {
            $message = "Data urodzenia musi być z przedzialu: od $then do $now!";
            return false;
        }

        //phone
        if (!preg_match('/^([0-9]{7,11})$/', $this->phone)) {
            $message = "Telefon musi musi mieć długość od 7 do 11 znaków i skladac sie z liczb calkowitch z przedzialu od 0 do 9!";
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
