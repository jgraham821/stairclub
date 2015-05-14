<?php
// Set default timezone.
date_default_timezone_set('America/New_York');

// Create the db connection.
$connection = null;
try {
    require 'init_config.php';

    $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // run queries...
} catch (PDOException $ex) {
    error_log("Error: " . $ex->getMessage());
}

require 'StairClub/User.php';
require 'StairClub/UserRepository.php';
require 'StairClub/Result.php';
require 'StairClub/ResultRepository.php';
require 'StairClub/Route.php';
require 'StairClub/RouteRepository.php';

$userRepo   = new \Repository\UserRepsository($connection);
$resultRepo = new \Repository\ResultRepository($connection);
$routeRepo  = new \Repository\RouteRepository($connection);

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/', function() {
    require 'results.php';
});

$app->get('/routes/', function() use ($routeRepo) {
    $results = $routeRepo->findAll();
    $routes = array();
    foreach ($results as $result)
    {
        $routes[] = $result->jsonSerialize();
    }
    echo json_encode($routes);
});

$app->get('/times/', function() use ($app, $resultRepo) {
    $req = $app->request();
    $params = $req->params();

    $results = $resultRepo->findAll($params);
    $times = array();
    foreach ($results as $result)
    {
        $times[] = $result->jsonSerialize();
    }
    echo json_encode($times);
});

$app->get('/times/top/', function() use ($resultRepo) {
    $results = $resultRepo->findTop5();
    echo json_encode($results);
});

$app->get('/users/', function() use ($userRepo) {
    $results = $userRepo->findAll();
    $users = array();
    foreach ($results as $result)
    {
        $users[] = $result->jsonSerialize();
    }
    echo json_encode($users);
});

$app->post('/users/', function() use ($app, $userRepo) {
    $body = $app->request->getBody();
    $userData = json_decode($body, true);
    $user = new \User($userData);
    $success = $userRepo->save($user);
    $response = array('success' => $success);
    echo json_encode($response);
});

$app->get('/users/:username/', function($username) use ($userRepo) {
    $user = $userRepo->find($username);
    echo json_encode($user->jsonSerialize());
});

$app->get('/users/:username/times/', function($username) use ($resultRepo) {
    $results = $resultRepo->findAll(array('username' => $username));
    $times = array();
    foreach ($results as $result)
    {
        $times[] = $result->jsonSerialize();
    }
    echo json_encode($times);
});

$app->post('/users/:username/times/', function($username) use ($app, $userRepo, $resultRepo) {
    $body = $app->request->getBody();
    $resultData = json_decode($body, true);
    $user = $userRepo->find($username);
    $result = new \Result($username, $resultData);
    $success = $resultRepo->save($user, $result);
    $response = array('success' => $success);
    echo json_encode($response);
});

$app->run();
