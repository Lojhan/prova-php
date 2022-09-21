<?php 
    require_once 'database/index.php';
    class MovieRepository {
        /** @var \mysqli */
        protected $DB;

        public function __construct ( \mysqli $conn ) {
            $this->DB = $conn;
            $this->createTable();
        }

        private function createTable () {
            $sql = "CREATE TABLE IF NOT EXISTS movies (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                description VARCHAR(50),
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            if (!$this->DB->query($sql) === TRUE) {
                die("Error creating table: " . $this->DB->error);
            } 
        }

        public function getAllMovies () {
            $query = "SELECT * FROM movies";
            $stmt = $this->DB->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }

        public function getMovie ( $id ) {
            $query = "SELECT * FROM movies WHERE id = $id";
            $result = $this->DB->query($query);
            return $result;
        }

        public function createMovie ( $name, $description ) {
            $query = "INSERT INTO movies (name, description) VALUES (?, ?)";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("ss", $name, $description);
            $stmt->execute();
            $stmt->close();
            return $this->getMovie($this->DB->insert_id);
        }

        public function updateMovie ( $id, $name, $description ) {
            $query = "UPDATE movies SET name = ?, description = ? WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("sss", $name, $description, $id);
            $stmt->execute();
            $stmt->close();
            return $this->getMovie($id);
        }

        public function deleteMovie ( $id ) {
            $query = "DELETE FROM movies WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
            return 1;
        }
    }

?>