<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\MyAuthProvider;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

//use Auth;
use App\UserLoginLog;
use App\Events\UserEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function authenticated(Request $request, $user)
    {
        if (!Auth::check()) {
            return view('errors.404');
        }
        event(new UserEvent($request, $user));
    }

//    public function login(Request $request){
//        /**
//         *
//         *
//         *
//         */
//
//        $request->validate(
//            [
//                'email'=>['required'],
//                'password'=>['required']
//            ]
//
//        );
//
////        if($request->has(['password']))
//
//
//        $credentials = $request->only('email', 'password');
//        //$user = User::where('email',$request->email)->where('password',Hash::make($request->password))->first();
//        if(\Illuminate\Support\Facades\Auth::attempt($credentials))
//            //\Illuminate\Support\Facades\Auth::login($user);
//            return redirect()->route('home');
//        else
//            throw ValidationException::withMessages([
//                'email'=>["Username or password is incorrect"]
//            ]);
//        return redirect()->route('home');
//    }

    public function redirectToLine()
    {

        return Socialite::driver('line')->redirect();
    }

    public function handleLineCallback()//handleLineCallback
    {
        try {
            $user = Socialite::driver('line')->user();
//dd($user);
            $finduser = MyAuthProvider::where('provider', 'line')->where('providerid', $user->id)->first();
          //  dd($finduser);
            if ($finduser) {
                $user = User::where('id', $finduser->userid)->first();
                Auth::login($user);

                return redirect('/');
            } else {
                $newUser = new User();
                $newUser->name = $user->name ? $user->name : $user->nickname;
                $newUser->email = $user->email;
                $newUser->save();
                $newUser->assignRole('user');

                $new_user = new MyAuthProvider();
                $new_user->userid = $newUser->id;
                $new_user->provider = 'line';
                $new_user->providerid = $user->id;
                $new_user->save();
                Auth::login($newUser);
                return redirect('/');
            }
        } catch (\Exception $e) {
//dd($e->getMessage());
            Log::error($e->getMessage());
            return redirect('/');
        }
    }

}
