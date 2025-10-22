<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
| Rota de Login (Pública)
*/
Route::post('login', [AuthController::class, 'login']);


/*
| Rotas Protegidas (Requerem token JWT)
| Usamos o middleware 'auth:api' que configuramos em config/auth.php
*/
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']); // Rota para o frontend saber quem está logado

    /*
    | CRUD de Usuários
    | O comando 'apiResource' cria todas as rotas (index, store, show, update, destroy).
    | A permissão de Admin (para store, update, destroy) será
    | controlada *dentro* do UserController.
    */
    Route::apiResource('users', UserController::class);
});


