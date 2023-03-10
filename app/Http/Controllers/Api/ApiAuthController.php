<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserNFT;
use App\Models\UserBoughtNFT;
use App\Models\UserVerificationRequest;
use App\Models\Pages;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\ContactUS;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Mail\ForgetPassword;
use Carbon\Carbon;
use DB;

class ApiAuthController extends Controller
{
   
    public function login(Request $request)
    {
        // return response($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
                
            ], 400);
        }
        

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized','status' => 401], 401);
        }
        return $this->createNewToken($token);
    }
    // login Bareer token created here---
    protected function createNewToken($token)
    {
   
        $user = User::where('id',Auth('api')->user()->id)->first();
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
            'base_url' => url('/'),
        ]);
    }

    public function register(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
            ], 400);
        }
       
        $userDetail = $validator->validated();
    
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
        ]);

        $vToken = Str::random(50);
        $id = encrypt($user->id);

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized','status' => 401], 401);
        }
        return $this->createNewToken($token);
      
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Logout',
            'message' => 'logout',

        ]);
    }

    public function ForgetPassword(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
            ], 400);
        }

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $user = User::where('email', $request->email)->first();
        $user->redirect_url = 'https://2kpaid.com/app/public/reset_password?email='.$user->email.'&token='.$token;
        $user->customer_support ='https://2kpaid.com/app/public/contact';

        \Mail::to($user->email)->send(new ForgetPassword($user));
        return response()->json([
            'status' => 200,
            'token' => $token,
            'message' => 'Reset password link send to your mail address. Link valid for 60 minutes!'
        ]);
    }

    public function ResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
            ], 400);
        } 
        $user = User::where('email', $request->email)->first();
        if(Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 400,
                'message' => 'Password not changed, old and new password is same!'
            ]);
        }
        $user = User::where('email', $request->email)->first();
        $reset_password = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();
        $token_after_expire = Carbon::parse($reset_password->created_at)->addHour(1);
        if(Carbon::now() <= $token_after_expire){

            User::where('email', $request->email)->update(['password' =>  bcrypt($request->password)]);
            $user = User::where('email',$request->email)->first();
            return response()->json([
                'status' => 200,
                'message' => 'Password updated successfully',
                'user' => $user,
                'base_url' => url('/'),
            ]);
    
            
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Reset password link has been expired!'
            ]);
        }
        

    }
    
    public function GetContactUS(Request $request)
    {
        $contactUS_list = ContactUS::orderBy('updated_at', 'desc')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Get contact us list successfully!',
            'data' => $contactUS_list
        ]);
    }

    public function SaveContactUS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
                
            ], 400);
        }
        $contact_detail = ContactUS::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'regarding' => $request->regarding,
            'message' => $request->message,
        ]);
        
        return response()->json([
            'status' => 200,
            'message' => 'contact detail successfully submited',
            'data' => $contact_detail
        ]);

    }
    
    public function GetPages(Request $request)
    {
        $pages = Pages::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Get Pages successfully',
            'pages' => $pages
        ]); 
    }

    public function GetPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()->first(),
                
            ], 400);
        }
        $page = Pages::where('id', $request->page_id)->first();
        return response()->json([
            'status' => 200,
            'message' => 'Get Page successfully',
            'page' => $page
        ]); 
    }
    
    public function CheckAuth(Request $request)
    {
        return response()->json([
            'status' => 200,
            'message' => 'User Authorised'
        ]);
    }
    
    public function GetProfile(Request $request){
        $userInfo = User::where('id', $request->user_id)->select('id', 'name', 'lastname','profile_image', 'bio', 'facebook_link', 'twitter_link', 'youtube_link', 'is_verified')->first();
        if($userInfo){
            $userInfo->profile_image = url('/').'/public/'.$userInfo->profile_image;
            if($userInfo->is_verified == 1){
                
                $available_nft_count = UserNFT::where('user_id', $request->user_id)->where('status', 'listed')->count();
                $user_nfts = UserNFT::whereIn('user_id', [$request->user_id])->get();
                
                $forcount = UserNFT::whereIn('user_id', [$request->user_id]);
                
                $nft_count = $forcount->count();
                $volumn = $forcount->where('status','sold')->sum('price');
                $sale = $forcount->where('status','sold')->count();
                
                $user_collected_nft = UserBoughtNFT::leftjoin('user_nfts', 'User_bought_NFT.product_id', '=', 'user_nfts.id')
                ->where('User_bought_NFT.user_id', $request->user_id)
                ->select('user_nfts.name', 'user_nfts.description', 'user_nfts.price', 'user_nfts.image', 'user_nfts.string_price', 'user_nfts.tokenID', 'user_nfts.status', 'User_bought_NFT.id', 'User_bought_NFT.product_id')
                ->get();
              
                
                return response()->json([
                    'status' => 200,
                    'message' => 'User info fatched!',
                    'userInfo' =>  $userInfo,
                    'user_nfts' => $user_nfts,
                    'volumn' => number_format(floor($volumn*100)/100,2, '.', ''),
                    'total_items' => number_format($nft_count),
                    'sale' => number_format($sale),
                    'available_nft_count' => number_format($available_nft_count),
                    'user_collected_nft' => $user_collected_nft,
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'User not verified!',
                ]);
            }
        }
        
        
    }
    
    public function VerifyUser(Request $request){
        if($request->id){
            UserVerificationRequest::create(['user_id' => $request->id]);
            User::where('id',$request->id)->update(['is_verified' => 2]);
            return response()->json([
                'status' => 200,
                'message' => 'Verification request successfully submited!'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'User ID required'
            ]);
        }
    }

   
}


