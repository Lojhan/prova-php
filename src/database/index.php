<?php
    class DatabaseAdaptor {
        public $DB;
        public function __construct() {
            $host = 'mysql-prova';
            $db = 'prova';
            $user = 'root';
            $password = 'root';
            try {
                $this->DB = mysqli_connect($host, $user, $password, $db) or die("Connection failed: " . mysqli_connect_error());
            } catch ( PDOException $e ) {
                die();
            }
        }
    }
?>