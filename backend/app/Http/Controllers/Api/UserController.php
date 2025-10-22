<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendWelcomeEmailJob;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Busca todos os usuários no banco de dados.
        $users = User::all();

        // 2. Retorna a lista de usuários como JSON.
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Força email único
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user', // Garante que a role seja 'admin' ou 'user'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Erro de validação
        }

        // 3. Cria um novo usuário no banco de dados.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografa a senha
            'role' => $request->role,
        ]);

        // 4. DISPARAR O JOB: Envia o e-mail de boas-vindas
        SendWelcomeEmailJob::dispatch($user);

        // Retorna o usuário recém-criado com o status 201 (Created)
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * O Laravel automaticamente encontra o usuário pelo ID na URL
     * e o injeta como a variável $user (Route Model Binding).
     */
    public function show(User $user)
    {
        // Como o usuário já foi encontrado, apenas o retornamos como JSON.
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Verifica se o usuário logado é admin
        Gate::authorize('manage-users');

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id, // Ignora o ID do próprio usuário na verificação
            'password' => 'sometimes|nullable|string|min:6', // Permite atualizar a senha (se enviada)
            'role' => 'sometimes|required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Atualiza apenas os campos que foram enviados
        $user->update($request->only('name', 'email', 'role'));

        // Trata a senha separadamente, pois ela só deve ser
        // atualizada se uma nova senha for enviada.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-users');
        
        $user->delete();

        //Padrão HTTP para uma exclusão bem-sucedida.
        return response()->json(null, 204);
    }
}
