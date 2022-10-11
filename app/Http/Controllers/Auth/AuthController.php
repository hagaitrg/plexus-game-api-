<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Mail\SendResetPassword;
use App\Models\ResetCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends ResponseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string|min:12',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }

        $user = new User();
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->name = $request->username;
        $user->phone = $request->phone;
        $user->email_verified_at = Carbon::now();

        if ($user->save()) {
            return $this->sendSuccess($user,"Succesfully registered user",200);
        }else{
            return $this->sendError("Failed to register user", 400);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }
        if (Auth::attempt(['name'=> $request->username, 'password'=>$request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyToken')->plainTextToken;
            $success['data'] = $user;

            return $this->sendSuccess($success, "Succesfully Login", 200);
        }else{
            return $this->sendError("Unauthorized", 401);
        }
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }

        $user = User::where('email', $request->email)->first();

        $code = mt_rand(0000,9999);

        $resetCode = ResetCode::updateOrCreate(
            [
                "user_id" => $user->id,
            ],
            [
                "code" => $code,
                "valid_until" => Carbon::now()->addMinutes(30)
            ]
            );

            if (!$resetCode) {
                return $this->sendError("Failed create reset code",400);
            }

            Mail::to($request->email)->send(new SendResetPassword($code, $user->name));

            return $this->sendSuccess(null,'OTP send successfully to'.$request->email,200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:reset_codes',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }

        $passwordReset = ResetCode::firstWhere('code',$request->code);

        if (Carbon::now()->lessThanOrEqualTo($passwordReset->valid_until)) {
            $user = User::firstWhere('id', $passwordReset->user_id);
            $user->password = bcrypt($request->password);
            if ($user->save()) {
                return $this->sendSuccess($user,"Password has been updated!", 200);
            }

            return $this->sendError("Failde to update password", 400);
        }else{
            return $this->sendError("code is expired",400);
        }
    }

    public function getMyProfile()
    {
        $user = User::firstWhere('id',Auth::id());

        if ($user == null) {
            return $this->sendError("User not found!", 404);
        }

        return $this->sendSuccess($user, "Succesfully get profile", 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string',
            'email' => 'email',
            'phone' => 'string|min:12',
            'avatar' => 'image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }

        $user = User::firstWhere('id', Auth::id());
        $user->name = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            if (file_exists($user->avatar)) {
                try {
                    unlink($user->avatar);
                } catch (\Throwable $th) {
                    return $this->sendError($th->getMessage(), 400);
                }
            }
            $file = $request->file('avatar');
            $eks = $file->getClientOriginalExtension();
            $name = 'ava_'.$request->username.'_'.uniqid().'.'.$eks;
            $path = public_path('storage/uploads/avatars/');
            try {
                $request->avatar->move($path,$name);
                $user->avatar = $path.$name;
            } catch (\Throwable $th) {
                return $this->sendError($th->getMessage(), 400);
            }
        }

        if ($user->save()) {
            return $this->sendSuccess($user,"Profile updated successfully!", 200);
        }else{
            return $this->sendError("Failed to update profile",400);
        }
    }
}
