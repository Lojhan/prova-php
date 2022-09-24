<?php
    require_once 'controllers/base_controller.php';

    class MovieController extends BaseController {
        private $movieRepository;

        public function __construct( $movieRepository ) {
            parent::__construct();
            $this->movieRepository = $movieRepository;
            $this->messages = Validator::$messages;
        }

        public function get() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $validation = array();

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

                    $result = $this->movieRepository->getMovie($id);
                    $movie = $result->fetch_assoc();
                    $responseData = json_encode($movie);

                } catch (Error $e) {
                    $strErrorDesc = 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            return $this->end($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function list() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $arrMovies = array();
                    $result = $this->movieRepository->getAllMovies();
                    while ($row = $result->fetch_assoc()) {
                        $arrMovies[] = $row;
                    }
                    $responseData = json_encode($arrMovies);
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
                    $description = $_POST['description'];

                    $fields = array(
                        'name' => $name,
                        'description' => $description
                    );

                    $rules = array(
                        'name' => 'required|string|min:3',
                        'description' => 'required|string|min:3'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->movieRepository->createMovie($name, $description);
                    $movie = $result->fetch_assoc();
                    $responseData = json_encode($movie);
                } catch (Error $e) {
                    var_dump($e);
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
                    $description = $_POST['description'];

                    $fields = array(
                        'id' => $id,
                        'name' => $name,
                        'description' => $description
                    );

                    $rules = array(
                        'id' => 'required|int|min:1',
                        'name' => 'required|string|min:3',
                        'description' => 'required|string|min:3'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->movieRepository->updateMovie($id, $name, $description);
                    $movie = $result->fetch_assoc();
                    $responseData = json_encode($movie);
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

                    $result = $this->movieRepository->deleteMovie($id);
                    $responseData = json_encode($result);
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
    }
?>