<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MessageController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/validate_email', [AuthController::class, 'validateEmail']);

Route::post('/validate_user', [AuthController::class, 'validateUser']);

Route::get('/get_user',[AuthController::class,'getUser']);

Route::post('/update_user',[AuthController::class,'updateUser'])->middleware('auth:sanctum');

Route::post('/delete_users',[AuthController::class,'delete_users'])->middleware('auth:sanctum');

Route::get('/get_image',[AuthController::class,'get_image']);

Route::get('/get_likes',[ClusterController::class,'getLikes']);

Route::post('/create_cluster', [ClusterController::class, 'createCluster'])->middleware('auth:sanctum');

Route::get('/get_clusters',[ClusterController::class,'getClusters']);

Route::post('/get_a_cluster',[ClusterController::class,'getACluster'])->middleware('auth:sanctum');


Route::post('/update_cluster', [ClusterController::class, 'update_cluster'])->middleware('auth:sanctum');

Route::get('/delete_cluster', [ClusterController::class, 'delete_cluster']);

Route::post('/create_clusters', [ClusterController::class, 'createClusters'])->middleware('auth:sanctum');


Route::get('/get_messages',[MessageController::class,'getMessages']);

Route::post('/create_message', [MessageController::class, 'createMessage'])->middleware('auth:sanctum');

Route::get('/delete_messages', [MessageController::class, 'deleteMessages']);

Route::post('/like', [LikeController::class, 'like'])->middleware('auth:sanctum');



