<?php 
    class Validator {
        public static function validate($data, $rules) {
            $errors = array();
            foreach ($rules as $key => $rule) {
                $value = $data[$key];
                $rules = explode('|', $rule);
                foreach ($rules as $rule) {
                    $rule = explode(':', $rule);
                    $method = $rule[0];
                    $param = isset($rule[1]) ? $rule[1] : null;
                    if (!self::$method($value, $param)) {
                        $errors[$key] = $method;
                    }
                }
            }
            return $errors;
        }

        public static function required($value) {
            return !empty($value);
        }

        public static function email($value) {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }

        public static function min($value, $param) {
            return strlen($value) >= $param;
        }

        public static function text($value) {
            return is_string($value);
        }

        public static function max($value, $param) {
            return strlen($value) <= $param;
        }

        public static function match($value, $param) {
            return $value == $param;
        }

        public static function  numeric ($value) {
            return is_numeric($value);
        }

        public static function alpha ($value) {
            return ctype_alpha($value);
        }

        public static function alphaNumeric ($value) {
            return ctype_alnum($value);
        }

        public static function alphaDash ($value) {
            return preg_match('/^[a-zA-Z0-9_-]+$/', $value);
        }

        public static function alphaSpace ($value) {
            return preg_match('/^[a-zA-Z ]+$/', $value);
        }

        public static function url ($value) {
            return filter_var($value, FILTER_VALIDATE_URL);
        }

        public static function ip ($value) {
            return filter_var($value, FILTER_VALIDATE_IP);
        }

        public static function ipv4 ($value) {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        public static function ipv6 ($value) {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        }

        public static function boolean ($value) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        public static function float ($value) {
            return filter_var($value, FILTER_VALIDATE_FLOAT);
        }

        public static function int ($value) {
            return filter_var($value, FILTER_VALIDATE_INT);
        }

        public static function json ($value) {
            return is_array(json_decode($value, true));
        }

        public static function regex ($value, $param) {
            return preg_match($param, $value);
        }

        public static function contains ($value, $param) {
            return strpos($value, $param) !== false;
        }

        public static function startsWith ($value, $param) {
            return strpos($value, $param) === 0;
        }

        public static function endsWith ($value, $param) {
            return substr($value, -strlen($param)) === $param;
        }

        public static function date ($value, $param) {
            $d = DateTime::createFromFormat($param, $value);
            return $d && $d->format($param) == $value;
        }

        public static function dateFormat ($value, $param) {
            return self::date($value, $param);
        }

        public static function dateBefore ($value, $param) {
            return strtotime($value) < strtotime($param);
        }

        public static function dateAfter ($value, $param) {
            return strtotime($value) > strtotime($param);
        }

        public static function accepted ($value) {
            return in_array($value, array('yes', 'on', 1, '1', true));
        }

        public static function in ($value, $param) {
            $haystack = explode(',', $param);
            return in_array($value, $haystack);
        }

        public static function notIn ($value, $param) {
            $haystack = explode(',', $param);
            return !in_array($value, $haystack);
        }

        public static function same ($value, $param) {
            return $value == $param;
        }

        public static function different ($value, $param) {
            return $value != $param;
        }

        public static function digits ($value, $param) {
            return strlen($value) == $param;
        }

        public static function digitsBetween ($value, $param) {
            list($min, $max) = explode(',', $param);
            return strlen($value) >= $min && strlen($value) <= $max;
        }

        public static function size ($value, $param) {
            return strlen($value) == $param;
        }

        public static function between ($value, $param) {
            list($min, $max) = explode(',', $param);
            return strlen($value) >= $min && strlen($value) <= $max;
        }

        public static function before ($value, $param) {
            return strtotime($value) < strtotime($param);
        }

        public static function after ($value, $param) {
            return strtotime($value) > strtotime($param);
        }

        public static function image ($value) {
            return in_array(mime_content_type($value), array('image/jpeg', 'image/png', 'image/gif'));
        }

        public static function mimes ($value, $param) {
            $allowed = explode(',', $param);
            return in_array(mime_content_type($value), $allowed);
        }

        public static function extension ($value, $param) {
            $allowed = explode(',', $param);
            return in_array(pathinfo($value, PATHINFO_EXTENSION), $allowed);
        }

        public static $messages = array(
            'required' => 'The :attribute field is required.',
            'text' => 'The :attribute field must be a string.',
            'numeric' => 'The :attribute field must be numeric.',
            'int' => 'The :attribute field must be an integer.',
            'min' => 'The :attribute field must be at least :param characters long.',
            'max' => 'The :attribute field must be less than :param characters long.',
            'match' => 'The :attribute field must match the :param field.',
            'email' => 'The :attribute field must be a valid email address.',
            'url' => 'The :attribute field must be a valid URL.',
            'ip' => 'The :attribute field must be a valid IP address.',
            'ipv4' => 'The :attribute field must be a valid IPv4 address.',
            'ipv6' => 'The :attribute field must be a valid IPv6 address.',
            'boolean' => 'The :attribute field must be a boolean.',
            'float' => 'The :attribute field must be a float.',
            'alpha' => 'The :attribute field must be alphabetic characters.',
            'alphaNumeric' => 'The :attribute field must be alpha-numeric characters.',
            'alphaDash' => 'The :attribute field must be alpha-numeric characters, underscores, and dashes only.',
            'alphaSpace' => 'The :attribute field must be alphabetic characters and spaces only.',
            'contains' => 'The :attribute field must contain the :param string.',
            'startsWith' => 'The :attribute field must start with the :param string.',
            'endsWith' => 'The :attribute field must end with the :param string.',
            'date' => 'The :attribute field must be a valid date.',
            'dateFormat' => 'The :attribute field must be a valid date in the format :param.',
            'dateBefore' => 'The :attribute field must be a date before :param.',
            'dateAfter' => 'The :attribute field must be a date after :param.',
            'accepted' => 'The :attribute field must be yes, on, 1, or true.',
            'in' => 'The :attribute field must be one of the following: :param.',
            'notIn' => 'The :attribute field must not be one of the following: :param.',
            'same' => 'The :attribute field must be the same as :param.',
            'different' => 'The :attribute field must be different than :param.',
            'digits' => 'The :attribute field must be exactly :param digits.',
            'digitsBetween' => 'The :attribute field must be between :param digits.',
            'size' => 'The :attribute field must be exactly :param characters.',
            'between' => 'The :attribute field must be between :param characters.',
            'before' => 'The :attribute field must be a date before :param.',
            'after' => 'The :attribute field must be a date after :param.',
            'image' => 'The :attribute field must be an image.',
            'mimes' => 'The :attribute field must be a file of type: :param.',
            'extension' => 'The :attribute field must be a file with the following extension: :param.'
        );
    }
?>