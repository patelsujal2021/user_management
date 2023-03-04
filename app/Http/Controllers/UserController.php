<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserRegistrationNotification;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('parent',Auth::user()->id)->get();
        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $toDay = Carbon::now()->format('d-m-Y');
        $request->validate([
            'first_name' => 'required|string|max:190',
            'last_name' => 'required|string|max:190',
            'dob' => 'required|date|before:'.$toDay,
            'email' => 'required|email|max:190|unique:users,email',
            'page_limit' => 'required|numeric|min_digits:1|max_digits:3'
        ]);

        try{
            DB::beginTransaction();
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->dob = $request->dob;
            $user->email = $request->email;
            $user->parent = Auth::id();
            $user->page_limit = $request->page_limit;
            $user->password = Hash::make(123456789);
            $user->remember_token = Carbon::now()->addHour()->timestamp;
            $remember_token = $user->remember_token;
            if($user->save()){
                $data['name'] = $user->first_name." ".$user->last_name;
                $data['email'] = $user->email;
                $data['verify_link'] = route('auth.verify.register',['email'=>$user->email,'token'=>$remember_token]);
                $user->notify(new UserRegistrationNotification($data));

                $notification = ['message'=>"New User details stored successfully",'type'=>'success'];
                return redirect()->route('user.index')->with($notification);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $notification = ['message'=>"Something wrong!",'type'=>'warning'];
            return redirect()->route('user.index')->with($notification);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $toDay = Carbon::now()->format('d-m-Y');
        $request->validate([
            'first_name' => 'required|string|max:190',
            'last_name' => 'required|string|max:190',
            'dob' => 'required|date|before:'.$toDay,
            'email' => 'required|email|max:190|unique:users,email,'.$id,
            'page_limit' => 'required|numeric|min_digits:1|max_digits:3'
        ]);

        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->dob = $request->dob;
            $user->email = $request->email;
            $user->page_limit = $request->page_limit;
            if($user->save()){
                $notification = ['message'=>"User details updated successfully",'type'=>'success'];
                return redirect()->route('user.index')->with($notification);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $notification = ['message'=>"Something wrong!",'type'=>'warning'];
            return redirect()->route('user.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if ($user->delete()) {
                DB::commit();
                $notification = ['toastr' => "User delete successfully", 'type' => 'success'];
                return redirect()->route('user.index')->with($notification);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $notification = ['message'=>"Something wrong!",'type'=>'warning'];
            return redirect()->route('user.index')->with($notification);
        }
    }
}
