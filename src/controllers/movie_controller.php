<?php
    require_once 'controllers/base_controller.php';
    class MovieController extends BaseController {
        private $movieRepository;

        public function __construct( $movieRepository ) {
            $this->movieRepository = $movieRepository;
        }

        public function get() {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $uri = $this->getUriSegments();
                    $id = $uri[4];
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

            $this->end($strErrorDesc, $strErrorHeader, $responseData);
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