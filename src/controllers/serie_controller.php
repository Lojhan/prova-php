<?php
    require_once 'controllers/base_controller.php';
    class SerieController extends BaseController {
        private $serieRepository;

        public function __construct( $serieRepository ) {
            parent::__construct();
            $this->serieRepository = $serieRepository;
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

                    $result = $this->serieRepository->getSerie($id);
                    $serie = $result->fetch_assoc();
                    $responseData = json_encode($serie);
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
                    $arrSeries = array();
                    $result = $this->serieRepository->getAllSeries();
                    while ($row = $result->fetch_assoc()) {
                        $arrSeries[] = $row;
                    }
                    $responseData = json_encode($arrSeries);
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
                        'name' => 'required|text|min:3',
                        'description' => 'required|text|min:3'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->serieRepository->createSerie($name, $description);
                    $serie = $result->fetch_assoc();
                    $responseData = json_encode($serie);
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
                    $description = $_POST['description'];

                    $fields = array(
                        'id' => $id,
                        'name' => $name,
                        'description' => $description
                    );

                    $rules = array(
                        'id' => 'required|int|min:1',
                        'name' => 'required|text|min:3',
                        'description' => 'required|text|min:3'
                    );

                    $this->validate($fields, $rules);

                    $result = $this->serieRepository->updateSerie($id, $name, $description);
                    $serie = $result->fetch_assoc();
                    $responseData = json_encode($serie);
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

                    $result = $this->serieRepository->deleteSerie($id);
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