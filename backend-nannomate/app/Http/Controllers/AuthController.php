<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\user;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

		//find email same or new
		$hit = user::where('email', '=', $request->email)->get();
        $hit = $hit->count();

        if ($hit > 0) {
            return response()->json(['error'=>'Email already exists!'], 404);
        } else {

            //check password standart
            $pass = $request->password;
            $uppercase = preg_match('@[A-Z]@', $pass);
            $lowercase = preg_match('@[a-z]@', $pass);
            $number    = preg_match('@[0-9]@', $pass);

            if (!$uppercase || !$lowercase || !$number || strlen($pass)<=6) {
                return response()->json(['error'=>'Password Wajib minimum 6 Character dan mengandung huruf BESAR, huruf kecil dan angka!(misal : Contoh111)'], 400);
            } else {
                $input = $request->all();
                $input['email'] = strtolower($input['email']);
                $input['password'] = Hash::make($input['password']);
                $input['role'] = 'user login';
                $input['status'] = 'aktif';
                $user = user::create($input);
                $success['token'] = $user->createToken('nApp')->accessToken;
                $success['nama_lengkap'] = $user->nama_lengkap;
                $success['role'] = $user->role;
                $success['status'] = $user->status;

                return response()->json(['success'=>$success], 200);
            }
        }
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if ($user->status != 'aktif') {
                return response()->json(['error'=>'Unauthorised'], 401);
            } else {
                $success['token'] = $user->createToken('nApp')->accessToken;
                $success['nama_lengkap'] = $user->nama_lengkap;
                $success['role'] = $user->role;
                $success['status'] = $user->status;
                return response()->json(['success' => $success], 200);
            }
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function details()
    {
        $user = Auth::user();
        $details = [
            'id_user' => $user['id_user'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email' => $user['email'],
            'role' => $user['role'],
            'status' => $user['status']
        ];
        return response()->json(['success' => $details], 200);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->json(['message' => 'Successfully logged out'], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $user = Auth::user();
        //find email same or new
		$hit = user::where('email', '=', $request->email)->get();
        $hit = $hit->count();

        if ($hit > 0 && strtolower($user['email']) != strtolower($request->email)) {
            return response()->json(['error'=>'Email already exists!'], 404);
        } else {

            //check password standart
            $pass = $request->password;
            $uppercase = preg_match('@[A-Z]@', $pass);
            $lowercase = preg_match('@[a-z]@', $pass);
            $number    = preg_match('@[0-9]@', $pass);

            if (!$uppercase || !$lowercase || !$number || strlen($pass)<=6) {
                return response()->json(['error'=>'Password Wajib minimum 6 Character dan mengandung huruf BESAR, huruf kecil dan angka!(misal : Contoh111)'], 400);
            } else {

                user::where('id_user', $user['id_user'])->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($request->password)
                ]);

                return response()->json(['success' => 'Data successfully updated'], 200);
            }
        }
    }
}
