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

    // NON AUTHENTICATED
    $router->post('user/register', 'UserController@register');
    $router->post('user/login', 'UserController@login');
    $router->get('participant/get-participants', 'ParticipantController@index');
    $router->get('participant/{participant_id:\d+}', 'ParticipantController@find');
    $router->get('participant/participant', 'ParticipantController@nip');
    $router->post('participant/store', 'ParticipantController@store');
    $router->get('event/{event_id:\d+}', 'EventController@find');



    // AUTHENTICATED
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('user/{user_id:\d+}', 'UserController@find');
        $router->get('user/get-users', 'UserController@index');
        $router->post('user/update', 'UserController@update');
        $router->post('user/delete', 'UserController@delete');
        $router->post('user/logout', 'UserController@logout');


        // PARTICIPANTS CONTROL
        $router->post('participant/update', 'ParticipantController@update');
        $router->post('participant/delete', 'ParticipantController@delete');


        // EVENT
        $router->get('event/get-events', 'EventController@getEvent');
        $router->get('event/get-events', 'EventController@getEvent');
        $router->get('event/{event_id:\d+}/attendances', 'EventController@find');
        $router->post('event/create', 'EventController@store');
        $router->post('event/{event_id:\d+}/attendances', 'EventController@registration');
        $router->post('event/update', 'EventController@update');
        $router->post('event/delete', 'EventController@delete');


        // SESSIONS
        $router->get('session/get-sessions', 'SessionController@getSession');
        $router->post('session/post-sessions', 'SessionController@postSession');
        $router->post('session/update-session', 'SessionController@updateSession');
        $router->post('session/delete-session', 'SessionController@deleteSession');


        // EVENT SESSIONS
        $router->get('session/get-event-sessions', 'SessionController@getEventSession');
        $router->post('session/post-event-sessions', 'SessionController@postEventSession');


        // ATTENDANCES
        $router->get('session/get-attendances', 'SessionController@getAttendance');
        $router->post('session/post-attendances', 'SessionController@postAttendance');
    });
});
