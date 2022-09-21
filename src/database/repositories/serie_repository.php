<?php
    require_once 'database/index.php';
    class SerieRepository {
        /** @var \mysqli */
        protected $DB;

        public function __construct ( \mysqli $conn ) {
            $this->DB = $conn;
            $this->createTable();
        }

        private function createTable () {
            $sql = "CREATE TABLE IF NOT EXISTS series (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                description VARCHAR(50),
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            if (!$this->DB->query($sql) === TRUE) {
                die("Error creating table: " . $this->DB->error);
            } 
        }

        public function getAllSeries () {
            $query = "SELECT * FROM series";
            $stmt = $this->DB->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }

        public function getSerie ( $id ) {
            $query = "SELECT * FROM series WHERE id = $id";
            $result = $this->DB->query($query);
            return $result;
        }

        public function createSerie ( $name, $description ) {
            $query = "INSERT INTO series (name, description) VALUES (?, ?)";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("ss", $name, $description);
            $stmt->execute();
            $stmt->close();
            return $this->getSerie($this->DB->insert_id);
        }

        public function updateSerie ( $id, $name, $description ) {
            $query = "UPDATE series SET name = ?, description = ? WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("sss", $name, $description, $id);
            $stmt->execute();
            $stmt->close();
            return $this->getSerie($id);
        }

        public function deleteSerie ( $id ) {
            $query = "DELETE FROM series WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
            return $this->getSerie($id);
        }



    }

?>