<?php
    require_once 'database/index.php';
    require_once 'database/repositories/user_repository.php';
    require_once 'controllers/user_controller.php';
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $connection = new DatabaseAdaptor();

    $userRepository = new UserRepository($connection->DB);
    $userController = new UserController($userRepository);

    $methods = array('get', 'list', 'add', 'edit', 'delete');

    $controllers = array(
        'users' => $userController
    );

    if (isset($uri[3]) && in_array($uri[3], $methods)) {
        $controller = $controllers[$uri[2]];
        $controller->{$uri[3]}();
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

?>