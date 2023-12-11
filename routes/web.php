<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    // AUTH
    $router->get('user/get-users', 'UserController@index');
    $router->post('user/register', 'UserController@register');
    $router->post('user/login', 'UserController@login');
    $router->post('user/update', 'UserController@update');
    $router->post('user/delete', 'UserController@delete');
    // $router->post('user/logout', 'UserController@logout');

    // PARTICIPANT
    $router->get('participant/get-participants', 'ParticipantController@index');
    $router->post('participant/store', 'ParticipantController@store');
    $router->post('participant/update', 'ParticipantController@update');
    $router->post('participant/delete', 'ParticipantController@delete');

    // EVENT
    $router->get('event/get-events', 'EventController@getEvent');
    $router->post('event/create', 'EventController@store');
    $router->post('event/update', 'EventController@update');
    $router->post('event/delete', 'EventController@delete');
});
