<?php
    require_once 'controllers/base_controller.php';
    class UserController extends BaseController {
        private $userRepository;

        public function __construct( $userRepository ) {
            parent::__construct();
            $this->userRepository = $userRepository;
        }

        public function get() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
    
            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $uri = $this->getUriSegments();
                    $id = $uri[4];

                    $fields = array(
                        'id' => $id
                    );

                    $rules = array(
                        'id' => 'required|int|min:1'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->userRepository->getUser($id);
                    $user = $result->fetch_assoc();
                    $responseData = json_encode($user);
                } catch (Error $e) {
                    $strErrorDesc = 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
    
            $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function list() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
    
            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $arrUsers = array();
                    $result = $this->userRepository->getAllUsers();
                    while ($row = $result->fetch_assoc()) {
                        $arrUsers[] = $row;
                    }
                    $responseData = json_encode($arrUsers);
                } catch (Error $e) {                
                    $strErrorDesc = 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
    
            $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function add() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
    
            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $fields = array(
                        'name' => $name,
                        'email' => $email,
                        'password' => $password
                    );

                    $rules = array(
                        'name' => 'required|text|min:3',
                        'email' => 'required|email',
                        'password' => 'required|text|min:6|max:20'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->userRepository->createUser($name, $email, $password);
                    $user = $result->fetch_assoc();
                    $responseData = json_encode($user);
                } catch (Error $e) {                
                    $strErrorDesc = 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function edit() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
    
            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $id = $_POST['id'];
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $fields = array(
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'password' => $password
                    );

                    $rules = array(
                        'id' => 'required|int|min:1',
                        'name' => 'required|text|min:3',
                        'email' => 'required|email',
                        'password' => 'required|text|min:6|max:20'
                    );
                    
                    $result = $this->userRepository->updateUser($id, $name, $email, $password);
                    $user = $result->fetch_assoc();
                    $responseData = json_encode($user);
                } catch (Error $e) {
                    $strErrorDesc = 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }            
            
            $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function delete() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
    
            if (strtoupper($requestMethod) == 'DELETE') {
                try {
                    $uri = $this->getUriSegments();
                    $id = $uri[4];

                    $fields = array(
                        'id' => $id
                    );

                    $rules = array(
                        'id' => 'required|int|min:1'
                    );

                    $this->validate($fields, $rules);

                    $this->userRepository->deleteUser($id);
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
            
            $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }
    }
?>