<?php
    
    require_once 'libs/router.php';
    require_once 'controllers/user_api_controller.php';
    require_once 'middleware/jwt_auth_middleware.php';
    require_once 'controllers/albums_api_controller.php';

    $router = new Router();

    $router->addMiddleware(new JWTAuthMiddleware());
    
    #                       endpoint                verb                       controller                      method
    $router->addRoute('albums',         'GET',      'AlbumsApiController',   'getAllAlbums');
    $router->addRoute('albums/:id',     'GET',      'AlbumsApiController',   'getAlbum');
    $router->addRoute('albums',         'POST',     'AlbumsApiController',   'createAlbum');
    $router->addRoute('albums/:id',     'PUT',      'AlbumsApiController',   'editAlbum');
    $router->addRoute('albums/:id',     'DELETE',   'AlbumsApiController',   'deleteAlbum');

    $router->addRoute('users/token',    'GET',      'UserApiController',    'getToken');
    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
?>