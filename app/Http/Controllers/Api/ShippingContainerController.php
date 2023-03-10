<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\SCVideos;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use App\Models\UserNFT;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ShippingContainerController extends Controller
{
    public function SCVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'video' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        
        $destinationPath = public_path();
        $videoPath ='';
        $type = '';
        
        if($request->file('video')){
            
            $videoName = $request->user()->id.'_'.time().'_'.$request->video->getClientOriginalName(); 
            $request->video->move(public_path('uploads/users'), $videoName);
            $videoPath = 'uploads/users/'.$videoName;
            $mime = $request->video->getClientOriginalName();
            $file_type = explode('.', $mime);
            if($file_type[1] == 'mp4' ||$file_type[1] == 'mov' ||$file_type[1] == 'mkv'){
                $type = 'Video';
            }else{
                $type = 'Image';
            }
        }
        $sc_videos_ID =  DB::table('sc_videos')->insertGetId([
            'User_ID' => $request->user()->id,
            'video' => $videoPath ? $videoPath : $request->video,
            'name' => $request->name,
            'redirection_link' => $request->redirection_link,
            'description' => $request->description,
            'type' => $type,
        ]);
        $sc_videos = SCVideos::where('id', $sc_videos_ID)->first();
        return response()->json([
            'status' => 200,
            'message' => 'User SC video uploaded successfully!',
            'sc_videos' => $sc_videos,
            'base_url' => url('/'),
        ]);
    }
    
    public function SCVideoList(Request $request)
    {   
        $sc_videos = SCVideos::orderBy('updated_at', 'desc')->where('User_ID', $request->user()->id)->select('id', 'video', 'name', 'description', 'type')->get();
        
        foreach($sc_videos as $key => $value){

            $mapSCPlacemet = TBLShippingContainerPlacements::where('SC_ID', $value->id)->where('Item_type', 'Video')->first();
            if($mapSCPlacemet){
                $sc_videos[$key]['SCplacement_id'] = $mapSCPlacemet->SC_ID;
            }else{
                $sc_videos[$key]['SCplacement_id'] = '';

            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'User SC video listed successfully!',
            'sc_videos' => $sc_videos,
            'base_url' => url('/'),
        ]);
    }

    public function SCVideoDelete(Request $request)
    {
        // return response($request->sc_id);
        $destinationPath = public_path();
        $video_exist = SCVideos::where('id', $request->sc_id)->where('User_ID', $request->user()->id)->first();
        if($video_exist->video){
            File::delete($destinationPath.'/'.$video_exist->video);
        } 
        $sc_videos = SCVideos::where('User_ID', $request->user()->id)->where('id', $request->sc_id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User SC video deleted successfully!',
        ]);
    }

    // Manage Shipping COntainer

    public function ManageSC(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required',
            'Featured_Image' => 'required',
            'Description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        
        $destinationPath = public_path();
        $imagePath ='';
        
        if($request->file('Featured_Image')){
            
            $imageName = $request->user()->id.'_'.time().'_'.$request->Featured_Image->getClientOriginalName(); 
            $request->Featured_Image->move(public_path('uploads/users'), $imageName);
            $imagePath = 'uploads/users/'.$imageName;
        }
        $sc_image_ID =  DB::table('TBL_Shipping_container')->insertGetId([
            'User_ID' => $request->user()->id,
            'Featured_Image' => $imagePath ? $imagePath : $request->Featured_Image,
            'Name' => $request->Name,
            'Description' => $request->Description,
        ]);
        $manage_sc = TBLShippingContainer::where('Sc_ID', $sc_image_ID)->first();
        return response()->json([
            'status' => 200,
            'message' => 'User SC video uploaded successfully!',
            'manage_sc' => $manage_sc,
            'base_url' => url('/'),
        ]);
    }
    
    public function ManageSCList(Request $request)
    {   
        if($request->item_id){
            $manage_sc = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->where('User_ID', $request->user()->id)->select('Sc_ID', 'Featured_Image', 'Name', 'Description','Status')->get();
            foreach($manage_sc as $key => $value){
                $mapSCPlacemet = TBLShippingContainerPlacements::where('SC_ID', $value->Sc_ID)->where('Item_ID', $request->item_id)->where('Item_type', 'Product')->first();
                if($mapSCPlacemet){
                    $manage_sc[$key]['SCplacement_id'] = $mapSCPlacemet->id;
                }else{
                    $manage_sc[$key]['SCplacement_id'] = '';
                }
            }
        }else if($request->video_id){
            $manage_sc = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->where('User_ID', $request->user()->id)->select('Sc_ID', 'Featured_Image', 'Name', 'Description','Status')->get();
            foreach($manage_sc as $key => $value){
                $mapSCPlacemet = TBLShippingContainerPlacements::where('SC_ID', $value->Sc_ID)->where('Item_ID', $request->video_id)->where('Item_type', 'Video')->first();
                if($mapSCPlacemet){
                    $manage_sc[$key]['SCplacement_id'] = $mapSCPlacemet->id;
                }else{
                    $manage_sc[$key]['SCplacement_id'] = '';
                }
            }
        }else if($request->slime_seat){
            $manage_sc = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->where('User_ID', $request->user()->id)->select('Sc_ID', 'Featured_Image', 'Name', 'Description','Status')->get();
            foreach($manage_sc as $key => $value){
                $mapSCPlacemet = TBLShippingContainerPlacements::where('SC_ID', $value->Sc_ID)->where('Item_ID', $request->slime_seat)->where('Item_type', 'Slime Seat')->first();
                if($mapSCPlacemet){
                    $manage_sc[$key]['SCplacement_id'] = $mapSCPlacemet->id;
                }else{
                    $manage_sc[$key]['SCplacement_id'] = '';
                }
            }
        }else{
            $manage_sc = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->where('User_ID', $request->user()->id)->select('Sc_ID', 'Featured_Image', 'Name', 'Description','Status')->get();
        }
        
      

        return response()->json([
            'status' => 200,
            'message' => 'User manage SC listed successfully!',
            'manage_sc' => $manage_sc,
            'base_url' => url('/'),
        ]);
    }

    public function ManageSCDelete(Request $request)
    {
        $destinationPath = public_path();
        // $video_exist = TBLShippingContainer::where('Sc_ID', $request->sc_id)->where('User_ID', $request->user()->id)->first();
        // if($video_exist->Featured_Image){
        //     File::delete($destinationPath.'/'.$video_exist->Featured_Image);
        // } 
        $sc_videos = TBLShippingContainer::where('User_ID', $request->user()->id)->where('Sc_ID', $request->sc_id)->update(['Status' => 0]);
        return response()->json([
            'status' => 200,
            'message' => 'User Manage SC deleted successfully!',
        ]);
    }

    public function GetManageSC(Request $request)
    {

        $msc = TBLShippingContainer::where('User_ID', $request->user()->id)->where('Status', 1)->where('Sc_ID', $request->sc_id)->first();
        return response()->json([
            'status' => 200,
            'message' => 'User Data Get successfully!',
            'manage_sc' => $msc,
            'base_url' => url('/'),
        ]);
    }

    public function UpdateManageSC(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'Name' => 'required',
            'Featured_Image' => 'required',
            'Description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        $video_exist = TBLShippingContainer::where('Sc_ID', $request->sc_id)->where('User_ID', $request->user()->id)->first();
        
        
        $destinationPath = public_path();
        $ImagePath ='';
        
        if($request->file('Featured_Image')){
            if($video_exist->Featured_Image){
                File::delete($destinationPath.'/'.$video_exist->Featured_Image);
            } 
            $ImageName = $request->user()->id.'_'.time().'_'.$request->Featured_Image->getClientOriginalName(); 
            $request->Featured_Image->move(public_path('uploads/users'), $ImageName);
            $ImagePath = 'uploads/users/'.$ImageName;
        }
        $sc_videos_ID =  DB::table('TBL_Shipping_container')->where('Sc_ID', $request->sc_id)->update([
            'Featured_Image' => $ImagePath ? $ImagePath : $request->Featured_Image,
            'Name' => $request->Name,
            'Description' => $request->Description,
        ]);
        $msc = TBLShippingContainer::where('Sc_ID', $request->sc_id)->where('User_ID', $request->user()->id)->first();
        return response()->json([
            'status' => 200,
            'message' => 'User SC video uploaded successfully!',
            'manage_sc' => $msc,
            'base_url' => url('/'),
        ]);
    }

    public function MapSCVideo(Request $request)
    {
       
        if($request->SC_ID){
            $sc_ids = explode(',', $request->SC_ID);

            // For remove Manage Shipping Container 
            $SC_not_checked = TBLShippingContainerPlacements::whereNotIn('SC_ID', $sc_ids)->where('Item_type','Video')->where('Item_ID', $request->Item_ID)->get();
            if($SC_not_checked){
                foreach($SC_not_checked as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->where('Item_type','Video')->where('Item_ID', $request->Item_ID)->delete();
                }
            }
      
            // Add Map Slime Seat
            foreach($sc_ids as $key => $value){
                $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::where('SC_ID', $value)->where('Item_type','Video')->where('Item_ID', $request->Item_ID)->first();
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
                'message' => 'User Map Shipping container successfully!',
                'base_url' => url('/'),
            ]);



        }else{
            // Remove All maped slime seat
            $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::whereIn('Item_ID', [$request->Item_ID])->where('Item_type','Video')->get();
            if($TBLShippingContainerPlacementsID){
                foreach($TBLShippingContainerPlacementsID as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->delete();
                }
            }
            return response()->json([
                'status' => 204,
                'message' => 'User Map Shipping Container Delete successfully!',
                'base_url' => url('/'),
            ]);
        }
    }

    public function MapManageSC(Request $request)   
    {
        if($request->SC_ID){
            $sc_ids = explode(',', $request->SC_ID);

            // For remove Manage Shipping Container 
            $SC_not_checked = TBLShippingContainerPlacements::whereNotIn('SC_ID', $sc_ids)->where('Item_type','Product')->where('Item_ID', $request->Item_ID)->get();
            if($SC_not_checked){
                foreach($SC_not_checked as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->where('Item_type','Product')->where('Item_ID', $request->Item_ID)->delete();
                }
            }
      
            // Add Map Slime Seat
            foreach($sc_ids as $key => $value){
                $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::where('SC_ID', $value)->where('Item_type','Product')->where('Item_ID', $request->Item_ID)->first();
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
                'message' => 'User Map Shipping container successfully!',
                'base_url' => url('/'),
            ]);



        }else{
            // Remove All maped slime seat
            $TBLShippingContainerPlacementsID = TBLShippingContainerPlacements::whereIn('Item_ID', [$request->Item_ID])->where('Item_type','Product')->get();
            if($TBLShippingContainerPlacementsID){
                foreach($TBLShippingContainerPlacementsID as $key => $value){
                    TBLShippingContainerPlacements::where('SC_ID', $value->SC_ID)->delete();
                }
            }
            return response()->json([
                'status' => 204,
                'message' => 'User Map Shipping Container Delete successfully!',
                'base_url' => url('/'),
            ]);
        }
        
       
    }
   

    public function GetContainerContent(Request $request)
    {
        if($request->shipping_container_id){
            $shippingContainer = TBLShippingContainer::leftjoin('users', 'TBL_Shipping_container.User_ID', '=', 'users.id')
               ->where('TBL_Shipping_container.Sc_ID', $request->shipping_container_id)
               ->select(
                'TBL_Shipping_container.*',
                'users.name',
                'users.id as user_id',
                'users.lastname',
                'users.profile_image'
            )->first();
            
            if($shippingContainer){
                $shippingContainer->profile_image = url('/').'/public/'.$shippingContainer->profile_image;
                $shippingContainer->Featured_Image = url('/').'/public/'.$shippingContainer->Featured_Image;
            }
            $Shipping_container_product = TBLShippingContainerPlacements::whereIn('SC_ID', [$request->shipping_container_id])->where('Item_type', 'Product')->get();
            foreach($Shipping_container_product as $key => $value){
                $user_nft = UserNFT::where('id', $value->Item_ID)->first();
                $Shipping_container_product[$key]->name = $user_nft->name;
                $Shipping_container_product[$key]->description = $user_nft->description;
                $Shipping_container_product[$key]->price = $user_nft->price;
                $Shipping_container_product[$key]->image =$user_nft->image;
                $Shipping_container_product[$key]->tokenID = $user_nft->tokenID;
            }
            $Shipping_container_video = TBLShippingContainerPlacements::whereIn('SC_ID', [$request->shipping_container_id])->where('Item_type', 'Video')->get();
            foreach($Shipping_container_video as $key => $value){
                $user_nft = SCVideos::where('id', $value->Item_ID)->first();
                $Shipping_container_video[$key]->name = $user_nft->name;
                $Shipping_container_video[$key]->description = $user_nft->description;
                $Shipping_container_video[$key]->video = url('/').'/public/'.$user_nft->video;
                $Shipping_container_video[$key]->redirection_link = $user_nft->redirection_link;
                $Shipping_container_video[$key]->type = $user_nft->type;
            }
            return response()->json([
                'status' => 200,
                'message' => 'User Container Content Fatched successfully!',
                'shippingContainer' => $shippingContainer,
                'Shipping_container_product' => $Shipping_container_product,
                'Shipping_container_video' => $Shipping_container_video,
                'base_url' => url('/'),
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Shipping Container ID Required!',
            ]);
        }  
    }

    public function UpdateShippingVisitContainer(Request $request)
    {
        if($request->shipping_container_id){
            TBLShippingContainer::where('Sc_ID', $request->shipping_container_id)
                ->update([
                'Visit_count'=> DB::raw('Visit_count+1'),
            ]);
            // DB::table('TBL_Shipping_container')->where('Sc_ID', $request->shipping_container_id)->increment('Visit_count',1);
            $visit_count = TBLShippingContainer::where('Sc_ID', $request->shipping_container_id)->first();
            return response()->json([
                'status' => 200,
                'message' => 'Shipping Visit container Updated successfully!',
                'VisitCount' => $visit_count,
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Shipping Container ID Required!',
            ]);
        }
    }

    // Get Shipping Container....
    public function GetShippingContainer(Request $request)
    {
        $manage_sc = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->select('Sc_ID', 'Featured_Image', 'Name', 'Description')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Get Shipping Container successfully!',
            'data' => $manage_sc,
            'base_url' => url('/'),
        ]);
    }
    
}
