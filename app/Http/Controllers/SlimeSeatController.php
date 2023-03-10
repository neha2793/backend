<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TBL_slime_seats;
use App\Models\TBL_Slimeseat_Images;
use App\Models\Transactions;
use App\Models\Users;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SlimeSeatController extends Controller
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

        $TBL_slime_seats = TBL_slime_seats::orderBy('updated_at', 'desc')->where('Status', 1)->paginate(5);
        foreach($TBL_slime_seats as $key => $value){
            $value->USDPrice = $usd * $value->Price;
        }
        return view('slimeSeatManagement.index', compact('TBL_slime_seats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slimeSeatManagement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        // dd($request->all());

        $destinationPath = env('IMAGE_FULL_PATH');
        $image_delete_Path = env('IMAGE_PUBLIC_PATH');
        $imagePath ='';
        if($request->file('video')){
          
            $imageName = '_'.time().'_'.$request->video->getClientOriginalName(); 
            $request->video->move($destinationPath, $imageName);
            $imagePath = 'uploads/users/'.$imageName;
        }
        SCVideos::create([
            'video' => $imagePath,
            'name' => $request->name,
            'description' => $request->description,
            'redirection_link' => $request->redirection_link,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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

        $TBL_slime_seats = TBL_slime_seats::where('S_ID', $id)->where('Status', 1)->first();
        $TBL_slime_seats->UsdPrice = $usd * $TBL_slime_seats->Price;
        $TBL_Slimeseat_Images = TBL_Slimeseat_Images::whereIn('S_ID', [$id])->get();
        // dd($TBL_Slimeseat_Images);
        // dd($TBL_Slimeseat_Images);
        return view('slimeSeatManagement.edit', compact('TBL_Slimeseat_Images', 'TBL_slime_seats'));
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

        // dd($request->all()); 
        // Image 1
        $destinationPath = public_path();
        $full_path = public_path().'/uploads/users/';
        if($request->img_1){
            if($request->file('img_1')){
                $key = 1;
                $imageName = $id.'_'.$key.'.'.$request->img_1->extension();
        
                $request->img_1->move($destinationPath.'/uploads/slimeseat', $imageName);
                $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                if($request->img_id1){
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id1)->update([
                        'Image_path' => $imageName,
                        'Status' => 1,
                    ]);
                }else{
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                        'Image_path' => $imageName,
                        'Status' => 1,
                        'S_ID' => $id,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id1)->update([
                'Status' => 0,
            ]);
        }
        // Image 2
        if($request->img_2){
            if($request->file('img_2')){
                $key = 2;
                $imageName = $id.'_'.$key.'.'.$request->img_2->extension();
                $request->img_2->move($destinationPath.'/uploads/slimeseat', $imageName);
                $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                if($request->img_id2){
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id2)->update([
                        'Image_path' => $imageName,
                        'Status' => 1,
                    ]);
                }else{
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                        'Image_path' => $imageName,
                        'Status' => 1,
                        'S_ID' => $id,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id2)->update([
                'Status' => 0,
            ]);
        }

        // Image 3
        if($request->img_3){
            if($request->file('img_3')){
                $key = 3;
                $imageName = $id.'_'.$key.'.'.$request->img_3->extension();
                $request->img_3->move($destinationPath.'/uploads/slimeseat', $imageName);
                $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                if($request->img_id3){
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id3)->update([
                        'Image_path' => $imageName,
                        'Status' => 1,
                    ]);
                }else{
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                        'Image_path' => $imageName,
                        'Status' => 1,
                        'S_ID' => $id,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id3)->update([
                'Status' => 0,
            ]);
        }

        // Image 4
        if($request->img_4){
            if($request->file('img_4')){
                $key = 4;
                $imageName = $id.'_'.$key.'.'.$request->img_4->extension();
                $request->img_4->move($destinationPath.'/uploads/slimeseat', $imageName);
                $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                if($request->img_id4){
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id4)->update([
                        'Image_path' => $imageName,
                        'Status' => 1,
                    ]);
                }else{
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                        'Image_path' => $imageName,
                        'Status' => 1,
                        'S_ID' => $id,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id4)->update([
                'Status' => 0,
            ]);
        }

        // Image 5
        if($request->img_5){
            if($request->file('img_5')){
                $key = 5;
                $imageName = $id.'_'.$key.'.'.$request->img_5->extension();
                $request->img_5->move($destinationPath.'/uploads/slimeseat', $imageName);
                $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                if($request->img_id5){
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id5)->update([
                        'Image_path' => $imageName,
                        'Status' => 1,
                    ]);
                }else{
                    $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                        'Image_path' => $imageName,
                        'Status' => 1,
                        'S_ID' => $id,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id5)->update([
                'Status' => 0,
            ]);
        }
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
   
        TBL_slime_seats::where('S_ID', $id)->update(['Status' => '0']);
        TBL_Slimeseat_Images::whereIn('S_ID', [$id])->update(['Status' => '0']);
        return back()->with('success', 'Deleted Successfully!');
    }
}

