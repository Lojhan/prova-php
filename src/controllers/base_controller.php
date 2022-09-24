<?php 
    require_once 'misc/validator.php';

    class BaseController {
        private $messages;

        public function __construct() {
            $this->messages = Validator::$messages;
        }

        public function __call($name, $arguments) {
            $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
        }
    
        protected function getUriSegments() {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = explode( '/', $uri );
            return $uri;
        }
    
        protected function getQueryStringParams() {
            return parse_str($_SERVER['QUERY_STRING'], $query);
        }
    
        protected function sendOutput ( $data, $httpHeaders=array() ) {
            header_remove('Set-Cookie');
    
            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }
    
            echo $data;
            exit;
        }

        protected function end($error, $errorHeader, $result) {
            if (!$error) {
                $this->sendOutput(
                    $result,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput($result, 
                    array('Content-Type: application/json', $errorHeader)
                );
            }
        }

        protected function validate($fields, $rules) {
            $validation = Validator::validate($fields, $rules);

            if (count($validation) > 0) {
                $responseData = array();
                foreach ($validation as $key => $value) {
                    $explodedRules = explode('|', $rules[$key]);
                    $messages = array();

                    foreach ($explodedRules as $rule) {
                        $explodedRule = explode(':', $rule);
                        $method = $explodedRule[0];
                        $param = isset($explodedRule[1]) ? $explodedRule[1] : null;
                        $message = $this->messages[$method];
                        $message = str_replace(':attribute', $key, $message);
                        $message = str_replace(':param', $param, $message);
                        $messages[] = $message;

                        str_replace(':param', $param, $messages[count($messages) - 1]);
                    }

                    $responseData[] = array(
                        'field' => $key,
                        'messages' => $messages
                    );
                }
                $this->end(true, 'HTTP/1.1 400 Bad Request', json_encode($responseData));
            }
        }
    }
?>