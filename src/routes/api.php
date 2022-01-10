<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;
use \App\Laravue\Acl;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function() {
    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Auth routes
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');

        Route::get('/user', function (Request $request) {
            return new UserResource($request->user());
        });

        // Api resource routes
        Route::apiResource('roles', 'RoleController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('users', 'UserController')->middleware('permission:' . Acl::PERMISSION_USER_MANAGE);
        Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);

        Route::post('run-python', 'PythonscriptController@run');

        // Custom routes
        Route::put('users/{user}', 'UserController@update');
        Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' .Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
    });

    Route::post('learners/import', 'LearnerController@import');
    Route::post('learners/import_to_lrs', 'LearnerController@importToLrs');
    Route::post('learners/template', 'LearnerController@createTemplate');
    Route::get('learners/template', 'LearnerController@getTemplates');

    Route::get('imports', 'LearnerController@getImports');
    Route::post('imports/delete', 'LearnerController@deleteImports');

    Route::get('learners/lrs_users', 'LearnerController@getLrsUsers');
    Route::post('learners/lrs_users', 'LearnerController@saveLrsUsers');
    Route::get('learners/obj_avg_score', 'LearnerController@objAvgScore');
    Route::get('learners/obj_learners_count', 'LearnerController@objLearnersCount');
    Route::get('learners/learners_country_count', 'LearnerController@LearnersCountryCount');
    Route::get('learners/groups_with_learners', 'LearnerController@GroupsWithLearners');

    Route::apiResource('learners', 'LearnerController');
    Route::apiResource('learner_countries', 'LearnerCountriesController');
    Route::apiResource('learner_groups', 'LearnerGroupsController');

    Route::apiResource('visualizations', 'VisualizationController');
    Route::post('visualizations/all', 'VisualizationController@updateAll');
    Route::get('authorities', 'VisualizationController@getAuthorities');
});
