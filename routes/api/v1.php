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
$api = app('Dingo\Api\Routing\Router');

// v1 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'cors',
        'serializer',
//        'serializer:array', // if you want to remove data wrap
        'api.throttle',
    ],
    // each route have a limit of 20 of 1 minutes
    'limit' => 20,
    'expires' => 1,
], function ($api) {
    $api->get('articles', 'ArticleController@index');
    $api->get('article/show', 'ArticleController@show');
    $api->get('categories', 'CategoryController@index');
    $api->get('category/articles', 'ArticleController@getArticleByCateId');
    $api->get('article/hot', 'ArticleController@hotArticle');
    $api->get('article/random', 'ArticleController@randomArticle');
    $api->get('tags/hot', 'TagController@hotTags');
    $api->get('test', 'TagController@test');
});
