<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserVerificationRequest;
use App\Models\User;

use App\Mail\ApplicationVerifiedMail;
use App\Mail\ApplicationRejectedMail;

class UserVerificationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_varification_list = UserVerificationRequest::leftjoin('users', 'user_verification_requests.user_id', '=', 'users.id')
        ->select('users.*')->paginate(5);
        return view('userVerificationRequest.index', compact('account_varification_list'));
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
        $account_varification_data = UserVerificationRequest::leftjoin('users', 'user_verification_requests.user_id', '=', 'users.id')
        ->where('user_verification_requests.user_id', $id)
        ->select('users.*')->first();
        return view('userVerificationRequest.edit', compact('account_varification_data'));

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
        
        $user = User::where('id', $id)->first();
        if($request->verify_status =='verify'){
            UserVerificationRequest::where('user_id', $id)->delete();
            User::where('id', $id)->update(['is_verified' => 1]);
            \Mail::to($user->email)->send(new ApplicationVerifiedMail($user));

            return redirect()->route('user-varification-request.index')->with('success', 'Application verified successfully!');
        }elseif($request->verify_status =='reject'){
            UserVerificationRequest::where('user_id', $id)->delete();
            User::where('id', $id)->update(['is_verified' => 0]);
            \Mail::to($user->email)->send(new ApplicationRejectedMail($user));
            return redirect()->route('user-varification-request.index')->with('error', 'Application rejected successfully!');

        }else{
            return redirect()->back()->with('error', 'Verify status required!');
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
        //
    }
}
