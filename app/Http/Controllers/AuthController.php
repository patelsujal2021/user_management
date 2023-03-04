<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\UserRegistrationNotification;


class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:190',
            'password' => 'required|min:8|max:10',
        ]);

        $userList = User::where(['email'=>$request->email])->get();
        if(!empty($userList) && count($userList)>0)
        {
            $user = $userList->first();
            if(!empty($user->email_verified_at) && !is_null($user->email_verified_at) && $user->is_active == 1)
            {
                if(Hash::check($request->password, $user->password))
                {
                    if( Auth::attempt(['email' => $request->email, 'password' => $request->password]) )
                    {
                        if(Auth::check())
                        {
                            $notification = ['message'=>"user authenticate successfully",'type'=>'danger'];
                            return redirect()->route('account.dashboard');
                        }
                    }
                    else
                    {
                        $notification = ['message'=>"authentication failed",'type'=>'danger'];
                        return redirect()->route('auth.login.page')->with($notification);
                    }
                }
                else
                {
                    $notification = ['message'=>"credentials wrong",'type'=>'danger'];
                    return redirect()->route('auth.login.page')->with($notification);
                }
            }
            else
            {
                $notification = ['message'=>"your account is not confirm yet",'type'=>'danger'];
                return redirect()->route('auth.login.page')->with($notification);
            }
        }
        else
        {
            $notification = ['message'=>"record not found",'type'=>'danger'];
            return redirect()->route('auth.login.page')->with($notification);
        }
    }

    public function registrationPage()
    {
        return view('auth.registration');
    }

    public function registrationProcess(Request $request)
    {
        $toDay = Carbon::now()->format('d-m-Y');
        $request->validate([
            'first_name' => 'required|string|max:190',
            'last_name' => 'required|string|max:190',
            'dob' => 'required|date|before:'.$toDay,
            'email' => 'required|email|max:190|unique:users,email',
            'password' => 'required|min:8|max:10|confirmed',
            'g-recaptcha-response' => 'recaptcha'
        ]);

        try{
            DB::beginTransaction();
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->dob = $request->dob;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->remember_token = Carbon::now()->addHour()->timestamp;
            $remember_token = $user->remember_token;
            if($user->save()){
                $data['name'] = $user->first_name." ".$user->last_name;
                $data['email'] = $user->email;
                $data['verify_link'] = route('auth.verify.register',['email'=>$user->email,'token'=>$remember_token]);
                $user->notify(new UserRegistrationNotification($data));

                $notification = ['message'=>"Your registration completed successfully. Please check you inbox to verify your account",'type'=>'success'];
                return redirect()->route('auth.register.page')->with($notification);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $notification = ['message'=>"Something wrong!",'type'=>'warning'];
            return redirect()->route('auth.register.page')->with($notification);
        }
    }

    public function verifyRegistrationProcess($email,$token)
    {
        $users = User::where('email',$email)->get();

        if(count($users) > 0){
            $user = $users->first();
            $currentDateTime = Carbon::now();
            $tokenDateTime = Carbon::createFromTimestamp($token);
            $diff_hours = $tokenDateTime->diffInHours($currentDateTime);

            if(!empty($user) && $user->remember_token != "" && $diff_hours < 2){
                if($user->remember_token == $token){
                    $user->email_verified_at = now();
                    $user->is_active = 1;
                    $user->remember_token = null;
                    if($user->save()){
                        $notification = ['message'=>"your email is verified thank you for registration",'type'=>'success'];
                        return redirect()->route('auth.login.page')->with($notification);
                    }
                } else {
                    $notification = ['message'=>"token is not valid",'type'=>'warning'];
                    return redirect()->route('auth.login.page')->with($notification);
                }
            } else {
                $notification = ['message'=>"this link is expired",'type'=>'warning'];
                return redirect()->route('auth.login.page')->with($notification);
            }
        } else {
            $notification = ['message'=>"no user with this email",'type'=>'warning'];
            return redirect()->route('auth.login.page')->with($notification);
        }
    }

    public function logoutProcess(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('auth.login.page');
    }
}
