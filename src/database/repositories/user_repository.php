<?php 
    require_once 'database/index.php';
    class UserRepository {
        /** @var \mysqli */
        protected $DB;

        public function __construct ( \mysqli $conn ) {
            $this->DB = $conn;
            $this->createTable();
        }

        private function createTable () {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                email VARCHAR(50),
                password VARCHAR(50),
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            if (!$this->DB->query($sql) === TRUE) {
                die("Error creating table: " . $this->DB->error);
            } 
        }

        public function getAllUsers () {
            $query = "SELECT * FROM users";
            $stmt = $this->DB->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }

        public function getUser ( $id ) {
            $query = "SELECT * FROM users WHERE id = $id";
            $result = $this->DB->query($query);
            return $result;
        }

        public function createUser ( $username, $usermail, $password ) {
            $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("sss", $username, $usermail, $password);
            $stmt->execute();
            $stmt->close();
        }

        public function updateUser ( $id, $username, $usermail, $password ) {
            $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("ssss", $username, $usermail, $password, $id);
            $stmt->execute();
            $stmt->close();
            return $this->getUser($id);
        }

        public function deleteUser ( $id ) {
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $this->DB->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>