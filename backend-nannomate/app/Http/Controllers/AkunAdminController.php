<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;

class AkunAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $daftarAkunAdmin = user::where('role', '=', 'admin')
        ->get();
        //->get(['id_user', 'nama_lengkap', 'email', 'password', 'status']);

        return response()->json($daftarAkunAdmin, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
		        return response()->json(['error'=>'Password Wajib minimum 6 Character dan mengandung huruf BESAR, huruf kecil dan angka!(misal : Contoh111)'], 404);
		    } else {

                $validator = Validator::make($request->all(), [
                    'nama_lengkap' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                    'status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 401);
                }

                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $input['role'] = 'admin';
                $user = user::create($input);

                return response()->json(['success'=>$user], 200);
            }
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = user::find($id);

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //check password standart
		$pass=$request->password;
        $uppercase = preg_match('@[A-Z]@', $pass);
        $lowercase = preg_match('@[a-z]@', $pass);
        $number    = preg_match('@[0-9]@', $pass);

        if (!$uppercase || !$lowercase || !$number || strlen($pass)<=6) {
		    return response()->json(['error'=>'Password Wajib minimum 6 Character dan mengandung huruf BESAR, huruf kecil dan angka!(misal : Contoh111)'], 404);
		} else {

            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            $updatedUser = user::where('id_user', $request->id)->update([
                'nama_lengkap' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => $request->status
            ]);

            return response()->json(['success'=>$updatedUser], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = user::find($id);
		if (is_null($user)){
			return response()->json(['error'=>'Data Not Found!'], 404);
		}
			$user = user::find($id);
			$user->delete();
			return response()->json(['success'=>'Delete Data User Success!'], 200);
    }
}
