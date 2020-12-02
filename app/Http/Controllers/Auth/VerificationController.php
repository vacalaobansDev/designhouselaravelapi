<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
//use App\Repositories\Contracts\IUser;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\VerifiesEmails;


class VerificationController extends Controller
{
    //protected $users;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(  )
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        //$this->users = $users;
    }

    public function verify( Request $request, User $user ){

        $user = User::findOrFail($request->id);

        // Check if the url is a valid signed url
        if ( ! URL::hasValidSignature( $request ) ){
            return response()->json(["errors"=>[
                "message"=>"Invalid verification link",
            ]], 422);
        }

        // Check if the user has already verified account
        if ( $user->hasVerifiedEmail() ){
            return response()->json(["errors"=>[
                "message"=>"Email address already verified",
            ]], 422);
        }

        /*if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }*/

        /*$user = User::forceCreate([
            'name' => 'John Doe',
            'email' => john.doe@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now() //Carbon instance
        ]);*/

        $user->markEmailAsVerified();
        event( new Verified( $user ) );




        return response()->json( ["message"=>"Email successfully verified"], 200 );

    }

    public function resend( Request $request ){

        $this->validate( $request, [
            'email' => ['email','required']
        ] );

        $user = User::where( 'email', $request->email )->first();
        //$user = $this->users->findWhereFirst('email', $request->email);

        if( !$user ){
            return response()->json(["errors"=>[
                "email" => "No user could be found with this email address"
            ]], 422);
        }

        if ( $user->hasVerifiedEmail() ){
            return response()->json(["errors"=>[
                "message"=>"Email address already verified",
            ]], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json( ["status" => "verification link resent"] );

    }

}
