<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register' , [authController::class , 'register']);
Route::get('/login' , [authController::class , 'login']);


// Route::group(['middleware' => 'auth:api'], function(){

  Route::get('/Categories' , [CategoryController::class , 'index']);
  Route::get('/Categories/{id}' , [CategoryController::class , 'show']);
  Route::post('/Categories' , [CategoryController::class , 'store']);
  Route::put('/Categories/{id}' , [CategoryController::class , 'update']);
  Route::delete('/Categories/{id}' , [CategoryController::class , 'destroy']);


  Route::get('/posts' , [PostController::class , 'index']);
  Route::get('/posts/{id}' , [PostController::class , 'show']);
  Route::post('/posts' , [PostController::class , 'store']);
  Route::put('/posts/{id}' , [PostController::class , 'update']);
  Route::delete('/posts/{id}' , [PostController::class , 'destroy']);


  Route::get('/logout' , [authController::class , 'logout']);


// });
