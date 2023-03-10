<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\TBL_slime_seats;
use App\Models\SlimeTour;
use App\Models\SCVideos;
use App\Models\TBL_Slimeseat_Images;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserNFT;


class SlimeSeatController extends Controller
{
    public function AddSlimeSeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'Price' => 'required',
            'Description' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        $destinationPath = public_path();
        $preUser = User::where('id', $request->user()->id)->first();

        if($request->file('featured_image')){
            // if($preUser->profile_image){
            //     File::delete($destinationPath.'/'.$preUser->profile_image);
            // } 
            $imageName = $request->user()->id.'_'.time().'_'.$request->featured_image->getClientOriginalName(); 
            $request->featured_image->move(public_path('uploads/users/featuredImage'), $imageName);
            $imagePath = 'uploads/users/featuredImage/'.$imageName;
        }

        $TBL_slime_seats = TBL_slime_seats::create([
            'User_ID' => $request->user()->id,
            'name' => $request->name,
            'Description' => $request->Description,
            'Price' => $request->Price,
            'Date_created' => Carbon::now(),
            'Status' => 1,
            'product_id' => $request->product_id,
            'featured_image' => $imagePath,
        ]);


        if($request->Image_path){
            foreach($request->Image_path as $key => $value){
                if($value){
                    // if($preUser->Image_path){
                    //     File::delete($destinationPath.'/'.$preUser->Image_path);
                    // } 
                    $ext = explode('.', $value->getClientOriginalName())[1];
                    $index = $key+1;
                    $imageName = $TBL_slime_seats->id.'_'.$index.'.'.$ext; 
                    $value->move(public_path('uploads/slimeseat'), $imageName);
                    $slimeseatPath = 'uploads/slimeseat/'.$imageName;
                }
                $TBL_Slimeseat_Images = TBL_Slimeseat_Images::create([
                    'S_ID' => $TBL_slime_seats->id,
                    'Image_path' => $imageName,
                    'Date_created' => Carbon::now(),
                    'Status' => 1,
                ]);
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Slime Seat added successfully!',
            'base_url' => url('/').'/uploads/slimeseat/',
        ]);
       
    }

    public function GetSlimeSeat(Request $request)
    {
        $TBL_slime_seats = TBL_slime_seats::where('User_ID', $request->user()->id)->where('Status', 1)->get();
        foreach($TBL_slime_seats as $key => $value){           
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://api.coinconvert.net/convert/matic/usd?amount='.$value->Price,// your preferred link
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => "",
            //     CURLOPT_TIMEOUT => 30000,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => "GET",
            //     CURLOPT_HTTPHEADER => array(
            //         // Set Here Your Requesred Headers
            //         'Content-Type: application/json',
            //     ),
            // ));
            // $response = curl_exec($curl);
            // $err = curl_error($curl);
            // curl_close($curl);
            // if ($err) {
            //     return response()->json([
            //         'status' => 500,
            //         'message' => "cURL Error #:" . $err
            //     ]);
            // } else {
            //     $TBL_slime_seats[$key]->USDPrice = json_decode($response)->USD;
            // }
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('S_ID', $value->S_ID)->where('Status', 1)->first();
            $TBL_slime_seats[$key]['Image_path'] = $TBL_Slimeseat_Images ?$TBL_Slimeseat_Images->Image_path: '';

        }
        return response()->json([
            'status' => 200,
            'message' => 'Slime Seat added successfully!',
            'data' => $TBL_slime_seats,
            'base_url' => url('/').'/uploads/slimeseat/',
            
        ]);

    }

    public function DeleteSlimeSeat(Request $request)
    {
        if($request->S_ID){
            TBL_slime_seats::where('S_ID',$request->S_ID)->where('User_ID', $request->user()->id)->update(['Status' => 0]);
            TBL_Slimeseat_Images::whereIn('S_ID', [$request->S_ID])->update(['Status' => 0]);

            return response()->json([
                'status' => 200,
                'message' => 'Slime Seat Deleted successfully!',            
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'S ID Required!',
            ]);
        }
    }

    public function GetSlimeSeatWithID(Request $request)
    {
        if($request->S_ID){
            $TBL_slime_seats =  TBL_slime_seats::where('S_ID',$request->S_ID)->where('User_ID', $request->user()->id)->first();
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::whereIn('S_ID', [$request->S_ID])->get();
            $product = UserNFT::where('id', $TBL_slime_seats->product_id)->first();
            return response()->json([
                'status' => 200,
                'message' => 'Slime Seat Get successfully!', 
                'TBL_slime_seats'   => $TBL_slime_seats,         
                'TBL_Slimeseat_Images'   => $TBL_Slimeseat_Images, 
                'product_image' => $product->image, 
                'base_url' => url('/').'/uploads/slimeseat/',       
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'S ID Required!',
            ]);
        }
    }

    public function UpdateSlimeSeat(Request $request)
    {
       
        // return response($request->all());
      
        // Image 1
        if($request->img_1){
            if($request->file('img_1')){
                $key = 1;
                $imageName = $request->S_ID.'_'.$key.'.'.$request->img_1->extension();
                $request->img_1->move(public_path('uploads/slimeseat'), $imageName);
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
                        'S_ID' => $request->S_ID,
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
                $imageName = $request->S_ID.'_'.$key.'.'.$request->img_2->extension();
                $request->img_2->move(public_path('uploads/slimeseat'), $imageName);
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
                        'S_ID' => $request->S_ID,
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
                $imageName = $request->S_ID.'_'.$key.'.'.$request->img_3->extension();
                $request->img_3->move(public_path('uploads/slimeseat'), $imageName);
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
                        'S_ID' => $request->S_ID,
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
                $imageName = $request->S_ID.'_'.$key.'.'.$request->img_4->extension();
                $request->img_4->move(public_path('uploads/slimeseat'), $imageName);
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
                        'S_ID' => $request->S_ID,
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
                $imageName = $request->S_ID.'_'.$key.'.'.$request->img_5->extension();
                $request->img_5->move(public_path('uploads/slimeseat'), $imageName);
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
                        'S_ID' => $request->S_ID,
                        'Date_created' => Carbon::now(),
                    ]);
                }
            }
        }else{
            $TBL_Slimeseat_Images = TBL_Slimeseat_Images::where('id', $request->img_id5)->update([
                'Status' => 0,
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Slime Seat updated successfully!',
            'base_url' => url('/').'/uploads/slimeseat/',
        ]);
    }

    public function MapSlimeSeat(Request $request)
    {
        if($request->SC_ID){
            $sc_ids = explode(',', $request->SC_ID);
            
            // For remove maped Slime Seat
            $SC_not_checked = TBLShippingContainerPlacements::whereNotIn('SC_ID', $sc_ids)->where('Item_ID', $request->Item_ID)->where('Item_type','Slime Seat')->get();
            if($SC_not_checked){
                foreach($SC_not_checked as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->where('Item_ID', $request->Item_ID)->where('Item_type','Slime Seat')->delete();
                }
            }

            // Add Map Slime Seat
            foreach($sc_ids as $key => $value){
                
                $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::where('SC_ID', $value)->where('Item_ID', $request->Item_ID)->where('Item_type','Slime Seat')->first();
                if($TBLShippingContainerPlacementsID == null){
                    TBLShippingContainerPlacements::create([
                        'SC_ID' => $value,
                        'Item_ID' => $request->Item_ID,
                        'Item_type' => $request->Item_type,
                        'status' => 1,
                    ]);
                }else if($TBLShippingContainerPlacementsID->SC_ID == $value){
                    continue;
                }else if($TBLShippingContainerPlacementsID->SC_ID != $value){
                    TBLShippingContainerPlacements::create([
                        'SC_ID' => $value,
                        'Item_ID' => $request->Item_ID,
                        'Item_type' => $request->Item_type,
                        'status' => 1,
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'User Map Slime Seat successfully!',
                'base_url' => url('/'),
            ]);
            
        }else{

            // Remove All maped slime seat
            $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::whereIn('Item_ID', [$request->Item_ID])->where('Item_type','Slime Seat')->get();
            if($TBLShippingContainerPlacementsID){
                foreach($TBLShippingContainerPlacementsID as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->delete();
                }
            }
            return response()->json([
                'status' => 204,
                'message' => 'User Map Slime Seat Delete successfully!',
                'base_url' => url('/'),
            ]);

        }
    }

    public function GetSlimeSeatContainer(Request $request)
    {
        if($request->shipping_container_id){

            $slime_seat = [];
            $slime_seat_images = [];
            $shippping_container = TBLShippingContainer::leftjoin('users', 'TBL_Shipping_container.User_ID', '=', 'users.id')
            ->where('TBL_Shipping_container.Sc_ID', $request->shipping_container_id)
            ->select(
                'TBL_Shipping_container.Name', 
                'TBL_Shipping_container.Description', 
                'TBL_Shipping_container.Featured_Image', 
                'TBL_Shipping_container.Visit_count', 
                'users.name',
                'users.id as user_id',
                'users.lastname',
                'users.profile_image'
            )
            ->first();
            if($shippping_container){
                $shippping_container->profile_image = url('/').'/public/'.$shippping_container->profile_image;
                $shippping_container->Featured_Image = url('/').'/public/'.$shippping_container->Featured_Image;
            }
            $TBLShippingContainerPlacements = TBLShippingContainerPlacements::whereIn('SC_ID', [$request->shipping_container_id])->where('Item_type', 'Slime Seat')->select('SC_ID', 'Item_type', 'Item_ID')->get();
            
            
            
            if($TBLShippingContainerPlacements){
                foreach($TBLShippingContainerPlacements as $key => $value){
                    $slime_seat_data = TBL_slime_seats::where('S_ID', $value->Item_ID)->first();
                    
                    
                    if($slime_seat_data){
                        $slime_seat_data->featured_image =  url('/').'/public/'.$slime_seat_data->featured_image;
                        $slime_seat_images = TBL_Slimeseat_Images::whereIn('S_ID', [$slime_seat_data->S_ID])->get();
                        $slime_seat[$key] = $slime_seat_data;
                        $slime_seat[$key]->images = $slime_seat_images;
                    }
                }
            }
           
           $Shipping_container_video = TBLShippingContainerPlacements::whereIn('SC_ID', [$request->shipping_container_id])->where('Item_type', 'Video')->get();
           if($Shipping_container_video){
                foreach($Shipping_container_video as $key => $value){
                    $sc_video = SCVideos::where('id', $value->Item_ID)->first();
                    if($sc_video){
                        
                        $Shipping_container_video[$key]->name = $sc_video->name;
                        $Shipping_container_video[$key]->description = $sc_video->description;
                        $Shipping_container_video[$key]->video = url('/').'/public/'.$sc_video->video;
                        $Shipping_container_video[$key]->redirection_link = $sc_video->redirection_link;
                        $Shipping_container_video[$key]->type = $sc_video->type;
                    }
                }
           }
           

            return response()->json([
                'status' => 200,
                'message' => 'Get Slime Seat Container successfully!',
                'shippping_container' => $shippping_container,
                'slime_seat' => $slime_seat,
                'sc_video' => $Shipping_container_video,
                'image_folder_path' => url('/').'/public/uploads/slimeseat/',
                'base_url' => url('/'),
                
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Shipping Container id required!'
            ]);
        }
    }


    public function GetSlimeTour(Request $request)
    {
       $SlimeTour =  SlimeTour::orderBy('updated_at', 'desc')->get();
       foreach($SlimeTour as $key => $value){
           $value->image = url('/').'/public/'. $value->image;
       }
       return response()->json([
            'status' => 200,
            'data' => $SlimeTour,
            'message' => 'Slime Tour Get successfully!',
            'base_url' => url('/'),
        ]);
    }
}
