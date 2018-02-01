<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// Register route
$router->post('/user/register', 'UserController@register');

// Login/Register route
$router->post('/user/login', 'UserController@login');

//Upload Picture
$router->post('/user/{id}/profile', 'UserController@updateProfile');

