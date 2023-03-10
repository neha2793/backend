<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserNFT;
use App\Models\UserBoughtNFT;
use Validator;
use Illuminate\Support\Facades\File;

class NFTManagementController extends Controller
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
        $number = 1;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinconvert.net/convert/matic/usd?amount='.$number,// your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            // CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $usd = json_decode($response)->USD;
        $usersNFT = UserNFT::orderBy('updated_at', 'desc')->paginate(5);
        foreach($usersNFT as $key => $value){
           $value->UsdPrice = $usd * $value->price;
           
        }
       
        // dd($usersNFT);
        return view('nftManagement.index', compact('usersNFT'));
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
        $number = 1;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinconvert.net/convert/matic/usd?amount='.$number,// your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            // CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $usd = json_decode($response)->USD;

        $userNFT = UserNFT::where('id', $id)->first();
        $userNFT->UsdPrice = $usd * $userNFT->price;
        return view('nftManagement.edit', compact('userNFT'));
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

        $destinationPath = env('IMAGE_FULL_PATH');
        $image_delete_Path = env('IMAGE_PUBLIC_PATH');
        $profilePath ='';
        $preUser = Users::where('id', $id)->first();
        if($request->file('profile_image')){
            if($preUser->profile_image){
                File::delete($image_delete_Path.'/'.$preUser->profile_image);
            } 
            $imageName = $id.'_'.time().'_'.$request->profile_image->getClientOriginalName(); 
            $request->profile_image->move($destinationPath, $imageName);
            $profilePath = 'uploads/users/'.$imageName;
        }
        Users::where('id', $id)->update([
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
        $users = UserNFT::where('id', $id)->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}
