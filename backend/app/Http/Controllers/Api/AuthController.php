<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        // 1. Validar os dados que chegam (email e senha são obrigatórios)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Pegar apenas as credenciais validadas
        $credentials = $request->only('email', 'password');

        // 3. Tentar autenticar usando o 'guard' da 'api'
        //    O 'Auth::guard('api')' diz ao Laravel para usar o driver 'jwt'
        //    que configuramos no config/auth.php
        $token = Auth::guard('api')->attempt($credentials);

        // 4. Verificar se a tentativa falhou
        if (!$token) {
            // Se email/senha estiverem errados, retorna erro 401
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        // 5. Se deu certo, retorna o token formatado
        return $this->respondWithToken($token);
    }

    /**
     * Formata a resposta JSON com o token.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // O 'expires_in' informa ao frontend quando o token expira
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

    /**
     * Faz o logout do usuário (Invalida o token).
     */
    public function logout()
    {

        Auth::guard('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function me()
    {
        // Retorna os dados do usuário que o token representa
        return response()->json(Auth::guard('api')->user());
    }
}
