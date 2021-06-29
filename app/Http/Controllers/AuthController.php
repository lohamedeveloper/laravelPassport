<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        //validação do input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //
        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials))
        {
            //salvando os dados do usuário
            $user = Auth::user();
            $sucess['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['sucess' => $sucess], 200);

        }
        else
        {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }
    }



    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $sucess['name'] = $user->name;
        $sucess['email'] = $user->email;
        $sucess['token'] = $user->createToken('MyApp')->accessToken;

        return response()->json(['success' => $sucess], 200);
    }

    public function all()
    {
        return response()->json(['usuarios' => User::all(), 200]);
    }

    public function user($id)
    {
        $user = User::find($id);

        if($user == false)
        {
            return response()->json(['error' => []], 201);
        }
        return response()->json(['sucess' => $user], 200);
    }

    public function edit(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json(['error' =>$validator->errors()], 201);
        }

        $user = User::find($id);

        if($user == false)
            return response()->json(['erro' => 'error usuário não existe'], 201);

        $input = $request->all();

        $user->update($input);
        return response()->json(['sucess' => $user], 200);
    }

    public function delete($id)
    {

        if( User::find($id) == false)
          return response()->json(['error' => 'usuário não existe'], 201);

        $user = User::find($id);
        $user->delete($id);

        return response()->json(['sucess' => 'usuário excluido com sucesso'], 200);
    }
}
