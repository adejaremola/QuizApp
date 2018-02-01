<?php 

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

	public function __construct(){

		// $this->middleware('oauth', ['except' => ['index', 'show']]);
		// $this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show']]);
	}

	// public function index(){

	// 	$users = User::all();
	// 	return $this->success($users, 200);
	// }

	public function register(Request $request){

		$this->validateRequest($request);

		$user = User::create([
					'name' => $request->get('name'),
					'email' => $request->get('email'),
					'password'=> Hash::make($request->get('password'))
				]);
		$profile = Profile::create([
					'user_id' => $user->id,
					]);
		return response()->json(['data' => $user], 201);
		// return $this->success("The user with with id {$user->id} has been created", 201);
	}

	public function login(Request $request) {

		$user = User::where('email', $request->get('email'))->get();
		$password = User::where('email', $request->get('email'))->get(['password']);
		// $password = User::where()
		// return response()->json($user[0]['password'], 201);
		if ($user->isEmpty()) {
			# code...
			return response()->json(['data' => "This user doesn't exist"], 302);

		} elseif ($user && Hash::check($request->get('password'), $password[0]['password'])) {
			# code...
			return response()->json(['data' => $user], 200);

		} elseif ($user && !Hash::check($request->get('password'), $password[0]['password'])) {
			# code...
			return response()->json(['data' => "Incorrect password, please retry."], 404);
		}

	}

	public function updateProfile(Request $request, $id){

		$user = User::find($id);

		if(!$user){
			return response()->json(['data' => "This user doesn't exist."], 404);
		}


		$profile = Profile::where('user_id', $user->id)->update([
						'pic_url' => $request->get('pic_url')
					]);

		return response()->json(['data' => 'Profile Updated Successfully'], 200);
	}

}