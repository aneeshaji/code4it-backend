<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    /**
     * Login API.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('codeFactory')->accessToken;
            $success['name'] = $user->name;
            $success['token'] = $token;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }


    /**
     * User Registration API.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $success['name'] = $user->name;
            $message = 'Yay! A user has been successfully created.';
            $success['token'] = $user->createToken('codeFactory')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }


    /**
     * User Details API.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userDetails(Request $request)
    {
       // if ( $request->has('Authorization') || $request->header('Authorization') ) {
            $user = \Auth::guard('api')->user();
            return sendResponse($user, 'User Details retrieved successfully.');
              
        //} 
    }
}