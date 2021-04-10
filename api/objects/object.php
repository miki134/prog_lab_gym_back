<?php
    include_once '../config/database.php';

    abstract class Obj{
        protected $conn;
        protected $table_name;

        public function dropTable()
        {
            $sql = "DROP TABLE IF EXISTS ".$this->table_name;
            $stmt = $this->conn->query($sql);     

            $stmt->execute();
            $stmt->closeCursor();
            return $stmt;
        }
    }