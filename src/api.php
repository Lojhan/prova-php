<?php
    require_once 'database/index.php';

    require_once 'database/repositories/user_repository.php';
    require_once 'database/repositories/serie_repository.php';
    require_once 'database/repositories/movie_repository.php';

    require_once 'controllers/user_controller.php';
    require_once 'controllers/serie_controller.php';
    require_once 'controllers/movie_controller.php';
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $connection = new DatabaseAdaptor();

    $userRepository = new UserRepository($connection->DB);
    $serieRepository = new SerieRepository($connection->DB);
    $movieRepository = new MovieRepository($connection->DB);

    $userController = new UserController($userRepository);
    $serieController = new SerieController($serieRepository);
    $movieController = new MovieController($movieRepository);

    $methods = array('get', 'list', 'add', 'edit', 'delete');

    $controllers = array(
        'users' => $userController,
        'series' => $serieController,
        'movies' => $movieController
    );

    $controller = $controllers[$uri[2]];
    $method = $uri[3];

    if (isset($method) && in_array($method, $methods)) {
        $controller->{$method}();
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

?>