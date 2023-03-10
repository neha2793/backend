<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserNFT;
use App\Models\UserBoughtNFT;
use App\Models\SCVideos;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use DB;

class NFTController extends Controller
{
    public function saveWalletAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        User::where('id', $request->user()->id)->update(['wallet_address' => $request->wallet_address]);

        return response()->json([
            'status' => 200,
            'message' => 'User wallet address successfully stored!',
            'user' => User::where('id', $request->user()->id)->first(),
        ]);

    }

    public function saveUserNFT(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'hash_token' => 'required',
            'tokenID' => 'required',
            'seller' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }
        $details = array(
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'string_price' => $request->string_price,
            'image' => $request->image,
            'hash_token' => $request->hash_token,
            'tokenID' => $request->tokenID,
            'user_id' => $request->user()->id,
            'seller' => $request->seller,
        );

        $userNFT = UserNFT::create($details);
        if($userNFT){
            return response()->json([
                'status' => 200,
                'message' => 'User NFT details successfully stored!',
                'userNFT' => $userNFT,
            ]);
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'error in query!',
            ]);
        }

    }

    public function saveBoughtNFT(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_wallet' => 'required',
            'hash_token' => 'required',
            'tokenID' => 'required',
            'gasLimit' => 'required',
            'gasPrice' => 'required',
            'maxFeePerGas' => 'required',
            'maxPriorityFeePerGas' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }
        $details = array(
            'product_id' => $request->product_id,
            'from_wallet' => $request->from_wallet,
            'hash_token' => $request->hash_token,
            'price' => $request->price,
            'string_price' => $request->string_price,
            'tokenID' => $request->tokenID,
            'gasLimit' => json_encode($request->gasLimit),
            'gasPrice' => json_encode($request->gasPrice),
            'maxFeePerGas' => json_encode($request->maxFeePerGas),
            'maxPriorityFeePerGas' => json_encode($request->maxPriorityFeePerGas),
            'user_id' => $request->user()->id,
        );

        $userBoughtNFT = UserBoughtNFT::create($details);
        UserNFT::where('tokenID', $request->tokenID)->update(['status' => 'sold']);
        

        if($userBoughtNFT){
            return response()->json([
                'status' => 200,
                'message' => 'User NFT details successfully stored!',
                'userNFT' => $userBoughtNFT,
            ]);
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'error in query!',
            ]);
        }
    }

    public function users(Request $request)
    {
        $users = User::orderBy('id', 'desc')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Users Successfully fatched!',
            'user' => $users,
        ]);
    }

    public function userNFTCollection(Request $request)
    {

        $ids = explode(',',$request->ids[0]);
        $wallet_address = [];
        $users = User::whereIn('id', $ids)->get();
        foreach($users as $ckey => $val){
            array_push($wallet_address, $val->wallet_address);
        }
        $userNFTCollection = UserNFT::whereIn('user_id', $ids)->get();
        if(count($userNFTCollection)){
            foreach($userNFTCollection as $key => $value ){
                $user = User::where('id', $value->user_id)->first();
                $userNFTCollection[$key]['wallet_address'] = $user->wallet_address;

                if(auth('api')->user()){
                    $wishlist = Wishlist::where('product_id', $value->id)
                    ->where('user_id', auth('api')->user()->id)->first();
                    
                    if($wishlist){
                        $userNFTCollection[$key]->wishlist = 1;
                        $userNFTCollection[$key]->wishlist_id = $wishlist->id;
                    }else{
                        $userNFTCollection[$key]->wishlist = 0;
                    }
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Users Successfully fatched!',
            'userNFTCollection' => $userNFTCollection,
            'wallet_address' => $wallet_address,
            'flag' => 'backend',
        ]);
    }

    public function BuyNFTUser(Request $request)
    {
        $userBuyNFT = UserBoughtNFT::whereIn('user_id', [$request->user()->id])->get();
        foreach($userBuyNFT as $key => $value){
            if(auth('api')->user()){
                $wishlist = Wishlist::where('product_id', $value->product_id)
                ->where('user_id', auth('api')->user()->id)->first();
                
                if($wishlist){
                    $userBuyNFT[$key]->wishlist = 1;
                    $userBuyNFT[$key]->wishlist_id = $wishlist->id;
                }else{
                    $userBuyNFT[$key]->wishlist = 0;
                }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Users Successfully fatched!',
            'userBuyNFT' => $userBuyNFT,
        ]);
    }
    public function MyProfile(Request $request)
    {
        $users = User::where('id', $request->user()->id)->first();
        $available_nft_count = UserNFT::whereIn('user_id',  [$request->user()->id])->where('status', 'listed')->count();
        $user_nfts = UserNFT::leftjoin('wishlist', 'user_nfts.id', '=', 'wishlist.product_id')
            ->whereIn('user_nfts.user_id', [$request->user()->id])
            ->select(
                'user_nfts.*',
                'wishlist.id as wishlist_id',
                'wishlist.product_id as wishlist_product_id'
            )
            ->get();
        
        
        $forcount = UserNFT::whereIn('user_id', [$request->user()->id]);
        $nft_count = $forcount->count();
        $volumn = $forcount->where('status','sold')->sum('price');
        $sale = $forcount->where('status','sold')->count();
        $user_collected_nft = UserBoughtNFT::leftjoin('user_nfts', 'User_bought_NFT.product_id', '=', 'user_nfts.id')
        ->leftjoin('wishlist', 'User_bought_NFT.product_id', '=', 'wishlist.product_id')
        ->whereIn('User_bought_NFT.user_id', [$request->user()->id])
        ->select(
            'user_nfts.name', 
            'user_nfts.description', 
            'user_nfts.price', 
            'user_nfts.image', 
            'user_nfts.string_price', 
            'user_nfts.tokenID', 
            'user_nfts.status', 
            'User_bought_NFT.id', 
            'User_bought_NFT.product_id',
            'wishlist.id as wishlist_id',
            'wishlist.product_id as wishlist_product_id'
        )
        ->get();
       

        return response()->json([
            'status' => 200,
            'message' => 'Users detail successfully fatched!',
            'user' => $users,
            'available_nft_count' => number_format($available_nft_count),
            'user_nfts' => $user_nfts,
            'nft_count' => number_format($nft_count),
            'volumn' => number_format(floor($volumn*100)/100,2, '.', ''),
            'sale' => number_format($sale),
            'user_collected_nft' => $user_collected_nft,
            'base_url' => url('/'),
        ]);
    }

    public function UpdateMyProfile(Request $request)
    {
        // return response($request->all());
        $destinationPath = public_path();
        $profilePath ='';
        $preUser = User::where('id', $request->user()->id)->first();
        if($request->file('profile_image')){
            if($preUser->profile_image){
                File::delete($destinationPath.'/'.$preUser->profile_image);
            } 
            $imageName = $request->user()->id.'_'.time().'_'.$request->profile_image->getClientOriginalName(); 
            $request->profile_image->move(public_path('uploads/users'), $imageName);
            $profilePath = 'uploads/users/'.$imageName;
        }
        User::where('id', $request->user()->id)->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'bio' => $request->bio,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => $request->country,
            'facebook_link' => $request->facebook_link,
            'twitter_link' => $request->twitter_link,
            'youtube_link' => $request->youtube_link,
            'profile_image' => $profilePath ? $profilePath : $request->profile_image,
            'phone_number' => $request->phone_number
        ]);
        $user = User::where('id', $request->user()->id)->first();
        return response()->json([
            'status' => 200,
            'message' => 'Users profile update successfully!',
            'user' => $user,
            'base_url' => url('/'),
        ]);
    }

    public function NFTProfileImage(Request $request)
    {

        if($request->ids){

            $ids = explode(',',$request->ids[0]);
        }else{
            $ids = '';
        }
        $user_nft = UserNFT::where('tokenID', '!=',  null);
        if($ids){
            $user_nft  =  $user_nft->whereIn('user_id', $ids);
        }
        if($request->filter == 'newest_item'){
            $user_nft->orderBy('id','desc');
        }
        if($request->filter == 'sold_item'){
            $user_nft->where('status','sold');
        }
        if($request->inOrder){
            $user_nft->orderBy('price', $request->inOrder);
        }
        $user_nft = $user_nft->get()->toArray();
     
        // $wallet_address = explode(',', $request->wallet_address);
        $profile_data = User::where('profile_image', '!=', null)->get();
        foreach($user_nft as $key => $value){  
            $wishlist = Wishlist::where('product_id', $value['id'])->where('user_id', $request->user()->id)->first();
            if($wishlist){
                
                $user_nft[$key]['wishlist'] = 1;
                $user_nft[$key]['wishlist_id'] = $wishlist->id;
            }else{
                $user_nft[$key]['wishlist'] = 0;
            }
            
        }
        // dd($user_nft);

        return response()->json([
            'status' => 200,
            'message' => 'profile images fatched successfully!',
            'profilesData' => $profile_data,
            'user_nft' => $user_nft,
            'base_url' => url('/'),
        ]); 
    }

    public function NFTProfileImageList()
    {
        $user_nft = UserNFT::where('status', '!=',  'sold')->where('tokenID', '!=',  null)->get();
        // $wallet_address = explode(',', $request->wallet_address);
        $profile_data = User::where('profile_image', '!=', null)->get();
        // dd($user_nft);
        return response()->json([
            'status' => 200,
            'message' => 'profile images fatched successfully!',
            'profilesData' => $profile_data,
            'user_nft' => $user_nft,
            'base_url' => url('/'),
        ]); 
    }

    public function UserNFT(Request $request)
    {
        $userNFT = UserNFT::where('user_id', $request->user()->id)->where('status', '!=',  'sold')->where('tokenID', '!=', null)->get();
        foreach($userNFT as $key => $value){

            if(auth('api')->user()){
                $wishlist = Wishlist::where('product_id', $value->id)
                ->where('user_id', auth('api')->user()->id)->first();
                
                if($wishlist){
                    $userNFT[$key]->wishlist = 1;
                    $userNFT[$key]->wishlist_id = $wishlist->id;
                }else{
                    $userNFT[$key]->wishlist = 0;
                }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'User NFT fatched successfully!',
            'user_nft' => $userNFT,
            'base_url' => url('/'),
        ]);
    }

    public function GetProductDetails(Request $request)
    {
        if($request->product_id){

            $productDetail = UserNFT::where('user_id', $request->user()->id)->where('id', $request->product_id)->first();
            return response()->json([
                'status' => 200,
                'message' => 'user Prouct Detail fatched successfully!',
                'productDetail' => $productDetail,
                'base_url' => url('/'),
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Product ID Required!',
            ]);
        }

    }

    public function GetTrendingMerchandise(Request $request)
    {
        $trending_merchandise = UserNFT::orderBy('order_count', 'desc')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Trending Merchandise Get successfully!',
            'trending_merchandise' => $trending_merchandise,
        ]);
    }

    public function GetTopSeller(Request $request)
    {
        $data = [];
        $user_info = DB::table('user_nfts')
        ->where('status', 'sold')
        ->select('user_id', DB::raw('count(*) as total'))
        ->groupBy('user_id')
        ->orderBy('total', 'desc')
        ->get();

        foreach($user_info as $key => $value){
         
            $user = User::where('id', $value->user_id)->first();
            $user_info[$key]->profile = url('/').'/public/'.$user->profile_image;
            $user_info[$key]->full_name = $user->name.' '.$user->lastname;
        }
        return response()->json([
            'status' => 200,
            'message' => 'Top seller get successfully!',
            'data' => $user_info,
        ]);
    }
    
    public function GetTopCreator(Request $request)
    {
        $data = [];
        $user_info = DB::table('user_nfts')
        ->leftjoin('users', 'user_nfts.user_id', '=', 'users.id')
        ->where('users.is_verified', 1)
        ->select('user_id', DB::raw('count(*) as total'))
        ->groupBy('user_id')
        ->orderBy('total', 'desc')
        ->get();
       
       

        foreach($user_info as $key => $value){
            $totalPrice = DB::table('user_nfts')->whereIn('user_id', [$value->user_id])->sum('price');
            $user = User::where('id', $value->user_id)->first();
            $user_info[$key]->profile = url('/').'/public/'.$user->profile_image;
            $user_info[$key]->full_name = $user->name.' '.$user->lastname;
            $user_info[$key]->volume = number_format((float)$totalPrice, 3, '.', '');
        }
        return response()->json([
            'status' => 200,
            'message' => 'Top Creator get successfully!',
            'data' => $user_info,
        ]);
    }

}