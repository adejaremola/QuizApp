<?php 

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller{

	public function __construct() {

	}

	public function update(Request $request, $id){

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