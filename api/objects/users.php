<?php
require_once 'object.php';
class Users extends Obj
{
    protected $table_name = "users";

    public $id;
    public $name;
    public $surname;
    public $email;
    public $password;
    public $role;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsers()
    {
        $sql = "SELECT `name`, `surname`, `email`, `role` FROM "
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
                    $row['email'],
                    //$row['password'],
                    $row['role']
                );
                array_push($arr, $rowArr);
            }
            $stmt->closeCursor();
            return $arr;
        }
        return false;
    }

    public function getUser()
    {
        $sql = "SELECT `name`, `surname`, `email`, `password`, `role` FROM "
            . $this->table_name .
            " WHERE `email` LIKE '" . $this->email . "'";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        if ($stmt->rowCount() >= 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];

            $rowArr = array(
                $row['name'],
                $row['surname'],
                $row['email'],
                //$row['password'],
                $row['role']
            );

            $stmt->closeCursor();
            return $rowArr;
        }
        return false;
    }

    public function create()
    {

        $query = "INSERT INTO "
            . $this->table_name .
            " SET
                name=:name, surname=:surname, email=:email, password=:password, role=:role";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            $stmt->closeCursor();
            return true;
        }
        return false;
    }

    public function ifExists()
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE `email` LIKE '" . $this->email . "'";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        // echo $sql;
        if ($stmt->rowCount() >= 1) {
            return true;
        }
        return false;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS  ".$this->table_name." ( \n"
            . "    id int AUTO_INCREMENT, \n"
            . "    name varchar(30) not null, \n"
            . "    surname varchar(30) not null, \n"
            . "    email varchar(30) unique not null, \n"
            . "    password varchar(32) not null,\n"
            . "    role ENUM('admin', 'client', 'employee') default 'client', \n"
            . "    PRIMARY Key(id)\n"
            . ")";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }

    public function checkCredentials(&$message, $password = true)
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

        //email
        if (!preg_match('/^[a-z\d]+[\w\d.-]*@(?:[a-z\d]+[a-z\d-]+\.){1,5}[a-z]{2,6}$/i', $this->email)) {
            $message = "Nie poprawny email!";
            return false;
        }

        //password
        if ($password) {
            if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?=.*[A-Z])(?!.*\s).{8,30}$/', $this->password)) {
                $message = "Haslo musi mieć długość od 8 do 30 znaków! "
                    . "Nie możesz używać spacji! Musi posiadać: "
                    . "co najmniej jedną cyfrę, co najmniej jedną dużą literę, "
                    . "co najmniej jedną małą literę i co najmniej jeden znak specjalny!";
                return false;
            }
        }

        return true;


        // $result = mysql_query("SELECT Count(user_id) FROM `users` WHERE `user_nick` = '{$_POST['nick']}' OR `user_email` = '{$_POST['email']}'");
        // $row = mysql_fetch_row($result);
        // // if($row[0] > 0) {
        // echo '<p><b><span style="color:red">Użytkownik o takim nicku lub adresie email jest już zarejestrowany w bazie!</span></b></p>';
    }

    public function update()
    {
        $sql = "UPDATE " . $this->table_name . " SET "
            . "`name` = '" . $this->name . "', "
            . "`surname` = '" . $this->surname . "', ";

        $this->password === "" ? $addPass = '' : $addPass = "`password` = '" . md5($this->password) . "', ";

        $sql = $sql . $addPass
            . "`role` = '" . $this->role . "' "
            . "WHERE `email` = '" . $this->email . "'";

        $stmt = $this->conn->exec($sql);
        return $stmt;
    }

    public function getRole()
    {
        $sql = "SELECT `role` FROM " . $this->table_name . " WHERE `email` LIKE '" . $this->email . "'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->role = $row['role'];

            $stmt->closeCursor();
            return $this->role;
        }
        return false;
    }
}
