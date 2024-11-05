<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            'c_password' => 'required| min:6 |string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone ?? null;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $query = $user->save();
        if ($query) {

            // $maildata = [
            //     'name' => $user->name ,
            //     'email' => $user->email,
            //     'password' => $request->password
            // ];

            // $headers = "From: webmaster@example.com\r\n";
            // $headers .= "Reply-To: webmaster@example.com\r\n";
            // $headers .= "Content-Type: text/html\r\n";
            // $subject = 'Welcome to Laravel 11';
            // $emailTemplate = view('emails.welcome', compact(['maildata']))->render();
            // $sendMail = mail($request->email, $subject, $emailTemplate, $headers);
            // if (!$sendMail) {
                // return response()->json(['msg' => 'error', 'response' => 'Could not send email.'], 400);
            // }
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['msg' => 'error', 'response' => 'Unauthorized'], 401);
            }
            $user = Auth::user();
            return response()->json(['msg' => 'success', 'response' => 'Account created successfully.', 'user' => $user, 'token' => $this->respondWithToken($token)], 200);
        }
        return response()->json(['msg' => 'error', 'response' => 'Something went wrong. Could not create an account.'], 400);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['msg' => 'error', 'response' => 'Incorrect email or password.'], 401);
        }

        $user = Auth::user();

        if ($user->status == 0) {
            auth()->logout();
            return response()->json(['msg' => 'error', 'response' => 'Your account has been blocked by administration. Please contact support.'], 401);
        }

        return response()->json(['msg' => 'success', 'response' => 'Login successful.', 'user' => $user, 'token' => $this->respondWithToken($token)], 200);
    }
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['msg' => 'error', 'response' => 'User not found.'], 404);
        }

        $newPassword = $user->username . rand(100000, 999999);

        $user->password = bcrypt($newPassword);
        $query = $user->save();

        if ($query) {
            $maildata = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $newPassword
            ];

            $headers = "From: webmaster@example.com\r\n";
            $headers .= "Reply-To: webmaster@example.com\r\n";
            $headers .= "Content-Type: text/html\r\n";
            $subject = 'Password Reset Request';
            $emailTemplate = view('emails.reset', compact(['maildata']))->render();
            $sendMail = mail($user->email, $subject, $emailTemplate, $headers);

            if (!$sendMail) {
                return response()->json(['msg' => 'error', 'response' => 'Could not send email.'], 400);
            }

            return response()->json(['msg' => 'success', 'response' => 'Please check your inbox on registered email address to access your account and further instructions.'], 200);
        }
    }
    public function me()
    {
        $user = Auth::user();
        return response()->json(['msg' => 'success', 'response' => 'User details.', 'user' => $user], 200);
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['msg' => 'success', 'response' => 'Successfully logged out']);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 10800
        ]);
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'c_new_password' => 'required| min:6 |string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['msg' => 'error', 'response' => 'Incorrect old password.'], 400);
        }

        $user->password = bcrypt($request->new_password);
        $query = $user->save();

        if ($query) {
            return response()->json(['msg' => 'success', 'response' => 'Password updated successfully.'], 200);
        }
        return response()->json(['msg' => 'error', 'response' => 'Could not update password.'], 400);
    }
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $authUser = Auth::user();


        $authUser->name = $request->name;
        $authUser->phone = $request->phone ?? $authUser->phone;
        $query = $authUser->save();

        if ($query) {
            return response()->json(['msg' => 'success', 'response' => 'Profile updated successfully.', 'user' => $authUser], 200);
        }
    }
}
