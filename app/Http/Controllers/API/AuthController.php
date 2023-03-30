<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
               'success' => false,
               'data' => $validator->errors(),
               'message' => 'ada kesalahan'
            ]);
    
    }
    $input = $request -> all();
    $input ['password'] = Hash::make($request->password);#($input['password']);
    $user = User::create($input);

    $success['token'] = $user->createToken('auth_token')->plainTextToken;
    $success['name'] = $user->name;
    
    return response()->json([
        'success'=> true,
        'message'=> 'sukses register',
        'data'=>$success
    ]);
    }
    public function login(Request $request)

    {
        if (Auth::attempt ($request->only ('email','password')) ){
            $auth = Auth::user();
            $user = User::where('email',$request->email)->first();
            
            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;

            return response()-> json([
                'success' => true,
                'message' => 'Login Sukses',
                'data' => $success
            ]);

        } else{
            return response()-> json([
                'success' => false,
                'message' => 'Check your email and password',
                'data' => null
            ]);
           

            
        }
    }
}