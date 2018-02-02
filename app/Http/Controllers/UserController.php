<?php 

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Socialite;

class UserController extends Controller{

	public function __construct(){

	}

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
	}

	public function login(Request $request) {

		$user = User::where('email', $request->get('email'))->get();
		
		$password = User::where('email', $request->get('email'))->get(['password']);
		
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

	// Social Auth
	public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    // CallBack function
    public function handleProviderCallback($provider)
    {
        //Exeption handling to avoid display of error, if the auth provider is unaccessible
        try
        {
            //User credentials from the provider is passed into the $socialUser variable
            $socialUser = Socialite::driver($provider)->stateless()->user();
        }
        catch(\Exception $e)
        {
            //Redirects if the auth provider isnt accessible
            return redirect('/');
        }

        /**
            *Check in the database if the user that owns the credentials from the provider has been registered in the database already
            *That is done by checking for the userId from the provider, in the provider_id column
        */

        $socialProvider = SocialProvider::where('provider_id', $socialUser->getId())->first();

        if(!$socialProvider)
        {

            $user = User::firstOrCreate(
                /**
                    *firstOrCreate() method takes two parameters, 
                    *First is an array of credentials to check if they are in the database already
                    *If any is found, the user is object is returned, therefore a new entry isnt created
                    *Second is an array of new entries into the database, i.e if the first array isnt there in the database
                */
                ['email' => $socialUser->getEmail()],
                [
                	'name' => $socialUser->getName(),
	            	'email' => $socialUser->getEmail()
            	]
            );
             
            /**
                *Also save the userId from the provider, and the provider in the database
                *This is important so that multiple acounts aren't created for one user
            */
            $user->socialProvider()->create(
                [
                    'provider_id' => $socialUser->getId(),
                    'provider' => $provider
                ]
            );
        }
        else {
            /**
                If credentials from the provider is found in the database, means the user has already registered, so the registered user is retrieved
            */
            $user = $socialProvider->user;
        }
        // Once retrieved, or registered, the user is logged in
        return response()->json(['data' => $user], 201);
    }

}