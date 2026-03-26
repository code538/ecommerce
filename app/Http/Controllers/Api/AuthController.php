<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
            'role' => 'in:admin,user'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role ?? 'user',
        ]);

        return $this->success([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user'  => $user
        ], 'User registered successfully', 201);
    }

    public function addEmployee(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
            'role' => 'in:sales,accounts'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role ?? 'user',
        ]);

        return $this->success([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user'  => $user
        ], 'Employee add successfully', 201);
    }

    //Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();
        //dd($user);
        if (! $user || $request->password !== $user->password) {
            return $this->error('Invalid credentials', null, 401);
        }

        return $this->success([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user'  => $user,
        ], 'Login successful');
    }


    //Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'Logged out successfully');
    }

    //Reset password
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }
       
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return $this->error('Unable to send reset link', null, 400);
        }

        return $this->success(null, 'Password reset link sent to your email');
    }

    //Update password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = $password; 
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error('Invalid token or email', null, 400);
        }

        return $this->success(null, 'Password reset successful');
    }

    public function profile(Request $request)
    {   //dd('okk');
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role
        ], 'User profile fetched');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        $newToken = null;

        if ($request->filled('password')) {

            $user->password = $request->password;

            // delete current token
            $request->user()->currentAccessToken()->delete();

            // create new token
            $newToken = $user->createToken('auth_token')->plainTextToken;
        }

        $user->save();

        return $this->success([
            'user' => $user,
            'token' => $newToken
        ], 'Profile updated successfully');
    }

    public function employeeDetails()
    {
        $users = User::whereIn('role', ['sales', 'accounts'])->get();

        return $this->success($users, 'Employees fetched successfully');
    }
    

}

