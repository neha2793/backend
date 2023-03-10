<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\File;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::orderBy('updated_at', 'desc')->paginate(5);
        return view('userManagement.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('userManagement.edit', compact('user'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'wallet_address' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $destinationPath = public_path();
        $full_path = public_path().'/uploads/users/';
        
   
       
        $profilePath ='';
        $preUser = User::where('id', $id)->first();
        if($request->file('profile_image')){
            if($preUser->profile_image){
                File::delete($destinationPath.'/'.$preUser->profile_image);
            } 
            $imageName = $id.'_'.time().'_'.$request->profile_image->getClientOriginalName(); 
            $request->profile_image->move($full_path, $imageName);
            $profilePath = 'uploads/users/'.$imageName;
            
        }
        User::where('id', $id)->update([
            'profile_image' => $profilePath ? $profilePath : $preUser->profile_image,
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'wallet_address' => $request->wallet_address,
        ]);
       
        return back()->with('success', 'Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = User::where('id', $id)->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}
