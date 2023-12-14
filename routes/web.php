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

    // PARTICIPANTS
    $router->get('participant/get-participants', 'ParticipantController@index');
    $router->get('participant/{participant_id:\d+}', 'ParticipantController@find');
    $router->get('participant/participant', 'ParticipantController@nip');
    $router->post('participant/store', 'ParticipantController@store');


    // ABSENSI
    $router->get('event/{event_id:\d+}', 'EventController@find'); //Find event by id params
    $router->get('event/{event_id:\d+}/attendances', 'EventController@findAbsence'); // find attendances by event id with params
    $router->get('event/{event_id:\d+}/session/{session_id:\d+}/attendances', 'SessionController@findAbsence'); // find attendances by session id with params
    $router->post('event/{event_id:\d+}/attendances', 'EventController@registration'); // registration participant to event
    $router->post('event/{event_id:\d+}/session/{session_id:\d+}/attendances', 'SessionController@registration'); // registration participant per session


    // AUTHENTICATED
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('user/{user_id:\d+}', 'UserController@find');
        $router->get('user/get-users', 'UserController@index');
        $router->post('user/update', 'UserController@update');
        $router->post('user/delete', 'UserController@delete');
        $router->post('user/logout', 'UserController@logout');


        // PARTICIPANTS
        $router->post('participant/update', 'ParticipantController@update');
        $router->post('participant/delete', 'ParticipantController@delete');


        // EVENT
        $router->get('event/get-events', 'EventController@getEvent');
        $router->post('event/create', 'EventController@store');
        $router->post('event/update', 'EventController@update');
        $router->post('event/delete', 'EventController@delete');


        // SESSIONS
        $router->get('session/get-sessions', 'SessionController@getSession');
        $router->get('session/{session_id:\d+}/get-sessions', 'SessionController@findSession');
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
