<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use App\Models\PasswordReset;
use Carbon\Carbon;
use App\Mail\OtpViaMail;
use App\Mail\EmailVerify as SendVerificationMail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Models\EmailVerify;
use Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Helpers\ApiResponseTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ApiAuthController extends Controller
{

    use ApiResponseTrait;

    public function userDetails(Request $request)
    {
        return $this->createNewToken($request->bearerToken());
    }
    public function login(Request $request)
    {
        if($request->has('linkedin_code'))
        {
           return $this->handleProviderLoginCallback($request);
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            //  'user_role_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // $email = $this->sendCode($request->email);
        // $details = [
        //     'title' => 'Reset Password',
        //     'body'  => 'Please',
        //     'token'  => $email->token
        // ];
        // $user = User::where('id',Auth('api')->id())->with('roles')->with('experience')->with('spaces')->get();
        // \Mail::to($request->email)->send(new OtpViaMail($details,$user));
        return $this->createNewToken($token);
        //        }

    }

    public function handleProviderLoginCallback(Request $request)
    {
        try
        {
            $request->validate([
                'linkedin_code' => 'required',
            ]);

            // Get Access Token

            $redirectUrl=env('FRONTEND_URL').'/login';
            
            $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
                'grant_type' => 'authorization_code',
                'code' => $request->linkedin_code,
                'client_id' => env('LINKEDIN_CLIENT_ID'),
                'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
                'redirect_uri' => $redirectUrl,
            ]);
           
           
            if(!$response->successful())
            {
                return $this->respondUnAuthorized();
            }
           
            $token=json_decode($response->body());
            $token=$token->access_token;
           
            // Get user Details
            $user=Socialite::driver('linkedin')->userFromToken($token);
           

            $user = User::where('email', $user->email)->first();
            if($user)
            {
                $token = auth('api')->login($user, true);
                return $this->createNewToken($token);
            }

            return $this->respondUnAuthorized();

        } catch (\Exception$e) {
            
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function register(Request $request)
    // {
      
       
    //     // login_type 1,2

    //     // dd($request->all());
       
    //     if($request->has('linkedin_code'))
    //     {

    //         return $this->handleProviderCallback($request);
    //     }
        
    //     $validator = Validator::make($request->all(), [
    //         'firstname' => 'required|string|between:2,100',
    //         'lastname' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'password' => 'required|string|min:6',
    //         'type' => ['required',Rule::in(['Advisor','Advisee'])],
    //     ]);
        
    //     $userType = $request->type;
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors()->first(),
    //         ], 400);
    //     }
    //     // $status = $userType === 'Advisor' ? 0:1;
    //     // dd($status);
    //     $userDetail = $validator->validated();
    //     unset($userDetail['type']);
    //     $user = User::create([
    //         'firstname' => $request->firstname,
    //         'lastname' => $request->lastname,
    //         'email' => $request->email,
    //     //            'google_id' => $request->google_id,
    //         'password' => bcrypt($request->password),
    //     //            'status' => $status,
    //     ]);
      

    //     // event(new Registered($user));
        
    //     if($request->login_type){
    //         $login_type = $request->login_type;
    //     }else{
    //         $login_type = 0;
    //     }
    //     // event(new Registered($user));
        
    //     if($login_type == 2){

    //         $vToken = Str::random(50);
    //         $id = encrypt($user->id);

    //         User::where('id', $user->id)->update([
    //             'email_verified' => 1
    //         ]);
           
    //     }else{
    //         $this->sendEmailVerification($user);
    //         }
            
    //             $this->sendEmailVerification($user);
    //    $user->syncRoles($userType);
       
      
    //     if($userType=='Advisee'){
    //         $advisee= DB::table('advisees')->insertGetId([
    //             'UserID'=>$user->id,
    //             'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
    //             'funnel_status'=>'Account Created',
    //         ]);
    //         $user->update(['AdviseeID'=>$advisee]);
    //     }
    //     else
    //     {
    //         $advisor= DB::table('advisors')->insertGetId([
    //                 'UserID'=>$user->id,
    //                 'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
    //                 'funnel_status'=>'Account Created',
    //         ]);
    //         $user->update(['AdvisorID'=>$advisor]);
    //     }


    //     //        $email = $this->sendCode($request->email);
    //     //        $details = [
    //     //            'title' => 'Reset Password',
    //     //            'body'  => 'Please',
    //     //            'token'  => $email->token,
    //     //            'user_id' => $user->id,
    //     //            'email_token' => ''
    //     //        ];

    //             // dd($details);
    //             // \Mail::to($request->email)->send(new OtpViaMail($details));

    //     //        if (!$token = auth('api')->attempt($userDetail)) {
    //     //            return response()->json(['error' => 'Unauthorized'], 401);
    //     //        }
    //     //        return $this->createNewToken($token);
    //     return response()->json([
    //         'statusCode' => 200,
    //         'Success' => 'Successfully register',
    //         'message' => 'Request has been processed',
    //         'data' => User::where('email',$request->email)->first(),
    //     ]);
    // }

    public function register(Request $request)
    {
       
        // login_type 1,2

        // dd($request->all());
       
        if($request->has('linkedin_code'))
        {

            return $this->handleProviderCallback($request);
        }
        
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'type' => ['required',Rule::in(['Advisor','Advisee'])],
        ]);
        
        $userType = $request->type;
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }
        // $status = $userType === 'Advisor' ? 0:1;
        // dd($status);
        $userDetail = $validator->validated();
        unset($userDetail['type']);
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
        //            'google_id' => $request->google_id,
            'password' => bcrypt($request->password),
        //            'status' => $status,
        ]);

        
        // event(new Registered($user));
        
        if($request->login_type == 2){

            $vToken = Str::random(50);
            $id = encrypt($user->id);

            User::where('id', $user->id)->update([
                'email_verified' => 1
            ]);
        }else{
            $this->sendEmailVerification($user);
        }
       
       $user->syncRoles($userType);
       
      
        if($userType=='Advisee'){
            $advisee= DB::table('advisees')->insertGetId([
                'UserID'=>$user->id,
                'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
                'funnel_status'=>'Account Created',
            ]);
            $user->update(['AdviseeID'=>$advisee]);
        }
        else
        {
            $advisor= DB::table('advisors')->insertGetId([
                    'UserID'=>$user->id,
                    'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
                    'funnel_status'=>'Account Created',
            ]);
            $user->update(['AdvisorID'=>$advisor]);
        }


        //        $email = $this->sendCode($request->email);
        //        $details = [
        //            'title' => 'Reset Password',
        //            'body'  => 'Please',
        //            'token'  => $email->token,
        //            'user_id' => $user->id,
        //            'email_token' => ''
        //        ];

                // dd($details);
                // \Mail::to($request->email)->send(new OtpViaMail($details));

        //        if (!$token = auth('api')->attempt($userDetail)) {
        //            return response()->json(['error' => 'Unauthorized'], 401);
        //        }
        //        return $this->createNewToken($token);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully register',
            'message' => 'Request has been processed',
            'data' => User::where('email',$request->email)->first(),
        ]);
    }


    public function sendEmailVerification($user)
    {
        
        $vToken = Str::random(50);
        $id = encrypt($user->id);

        User::where('id', $user->id)->update([
            'email_token' => $vToken
        ]);
        $url = \URL::to('api/email/verify/'.$id.'/'.$vToken);
        
        $details = [
            'firstname'  => $user->firstname,
            'email' => $user->email,
            'url'  => $url,
        ];
       
        \Mail::to($user->email)->send(new SendVerificationMail($details));
        
        return true;

    }

    public function handleProviderCallback(Request $request)
    {
        try
        {
            $request->validate([
                'linkedin_code' => 'required',
                'type' => ['required',Rule::in(['Advisor','Advisee'])],
                'login_type' => 'required',
            ]);

            $redirectUrl=env('FRONTEND_URL').(($request->type=='Advisee') ? '/': '/'.strtolower($request->type).'/').env('LINKEDIN_REDIRECT_URL');
            

            // Get Access Token
            
                $url = "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code=" . $request->linkedin_code . "&redirect_uri=".$redirectUrl ."&client_id=".env('LINKEDIN_CLIENT_ID')."&client_secret=".env('LINKEDIN_CLIENT_SECRET')."";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Length:0',
                    'Content-Type: application/json',
                ),
                ));
                
                $response = curl_exec($curl);
                $err      = curl_error($curl);
                curl_close($curl);

                if (!$err) {
                    $response = json_decode($response);
                } else {
                    throw new exception(__METHOD_ . '() ' . $err . ', on line : ' . _LINE__);
                }

            

          
            if(!$response->access_token)
            {
                return $this->respondUnAuthorized();
            }
          
            $token=$response->access_token;

            // Get user Details
            $user=Socialite::driver('linkedin')->userFromToken($token);
            $userType = $request->type;
            $myuser = $this->findorCreateUser($user,$userType);
            $user = User::where('email', $user->email)->first();
            $token = auth('api')->login($user, true);

            $user->syncRoles($userType);

      
            
            return $this->createNewToken($token);

        } catch (\Exception$e) {
            
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
    // public function handleProviderCallback(Request $request)
    // {
    //     try
    //     {
            
    //         $response = Socialite::driver('linkedin')->userFromToken($request->authToken);
            
    //         $token = $user->token;
    //         $myuser = $this->findorCreateUser($user);
    //         $user = User::where('email', $user->email)->first();
    //         $token = auth('api')->login($user, true);
    //         return $this->createNewToken($token);

    //     } catch (\Exception$e) {
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function findorCreateUser($user,$userType)
    {
        $existingUser = User::where('email', $user->email)->first();
        if ($existingUser) {
            auth('api')->login($existingUser, true);
        } else {
            $newUser = User::create([
                'firstname' => $user->first_name,
                'lastname' => $user->last_name,
                'email' => $user->email,
                'email_verified' => 1,
                'linkedin_id' => $user->id,
                'password' => bcrypt('my-linkedin'),
            ]);

            if($userType=='Advisee'){
                info("Advisee");
                $advisee= DB::table('advisees')->insertGetId(
                    [
                        'UserID'=>$newUser->id,
                        'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
                        'funnel_status'=>'Account Created',
                ]);
                $newUser->update(['AdviseeID'=>$advisee]);
            }
            else
            {
                info("Advisors");
                $advisor= DB::table('advisors')->insertGetId(
                    [
                        'UserID'=>$newUser->id,
                        'account_created_timestamp_EST'=>Carbon::parse(now(),'EST')->setTimeZone('EST'),
                        'funnel_status'=>'Account Created',
                ]);
                $newUser->update(['AdvisorID'=>$advisor]);
            }
            auth('api')->login($newUser, true);
        }

        return $existingUser;

    }

    protected function createNewToken($token)
    {

        $user = User::where('id',Auth('api')->user()->id)
        //    ->with('roles')
        // ->with('experience')->with('spaces')
            ->first();
        
        $user->user_roles= $user->roles->pluck('name')->toArray();
        unset($user->roles);
        // dd(\DB::getQueryLog());
        // dd($user);
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
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

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        $checkUser = User::where('email', $request->email)->get();
        if($checkUser->isNotEmpty()){
            $token = rand(100000, 999999);
            while(PasswordReset::where('token',$token)->get()->isNotEmpty()){
                $token = rand(100000, 999999);
            }
            // $resetUpdate = AppUser::where('email',$request->email)->update(['password_reset'=>$code]);
            $vToken = Str::random(150);
            PasswordReset::where('email',$request->email)->delete();
            $insert = PasswordReset::insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
                'user_verification_token' => $vToken
            ]);
            if($insert){
                $details = [
                    'title' => 'Reset Password',
                    'body'  => 'Please',
                    'token'  => $token
                ];
                \Mail::to($request->email)->send(new OtpViaMail($details));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Email sent please check your mail',
                    'accessToken' => $vToken
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email you entered does not belong to any account',
                ],401);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email account',
            ],404);
        }

    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'token' => 'required',
        ]);
        $data = PasswordReset::where(['email' => $request->email, 'token' => $request->token])->get();
        if($data->isNotEmpty()){
            $user = User::where('email',$request->email)->update([
                'email_verified_at' => now()
            ]);
            return $this->createNewToken($request->bearerToken());
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Token is not verified please try again',
            ],404);
        }
    }

    public function codeVerification(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'code' => 'required'
        ]);
        $data = PasswordReset::where(['email' => $request->email, 'token' => $request->code])->get();
        if($data->isNotEmpty()){
            return response()->json([
                'status' => 'success',
                'message' => 'Token is verified',
                'data' => [],
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Token is not verified please try again',
            ],404);
        }

    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'email' => 'required',
            'access_token' => 'required'
        ]);
        $verifyUser = PasswordReset::where(['email' => $request->email, 'user_verification_token' => $request->access_token ])->get();
        if($verifyUser->isNotEmpty()){
            $data = User::where('email',$request->email)->update(['password' => Hash::make($request->password)]);
            if($data){
                PasswordReset::where('email', $request->email)->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Your password successfully updated',
                ]);
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong please try again',
                ],401);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'We are unable to verify your identity',
            ],401);
        }

    }
    public function resendToken(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        $data = $this->sendCode($request->email);
        if($data){
            $details = [
                'title' => 'Reset Password',
                'body'  => 'Please',
                'token'  => $data->token
            ];
            \Mail::to($request->email)->send(new OtpViaMail($details));
            return response()->json([
                'status' => 'success',
                'message' => 'Email sent please check your mail',
                'accessToken' => $data->user_verification_token
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, Please try again.',
            ],401);
        }

    }
    public function resendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email',$request->email)->first();
        if($user){
            $user->id = $user->id;
            $this->sendEmailVerification($user);
            return response()->json([
                'status' => 'success',
                'message' => 'Email sent please check your mail',
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, Please try again.',
            ],401);
        }

    }
    public function sendCode($email)
    {
        $token = rand(100000, 999999);
        while(PasswordReset::where('token',$token)->get()->isNotEmpty()){
            $token = rand(100000, 999999);
        }
        // $resetUpdate = AppUser::where('email',$request->email)->update(['password_reset'=>$code]);
        $vToken = Str::random(150);
        PasswordReset::where('email',$email)->delete();
        $insert = PasswordReset::insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'user_verification_token' => $vToken
        ]);
        return PasswordReset::where('email',$email)->first();
    }

    public function linkVerify($id,$token,Request $request)
    {
        $user_id = decrypt($id);
        $user = User::where(['id' => $user_id, 'remember_token' => $token])->get();
        if($user->isNotEmpty()){
            User::where('id',$user_id)->update(['email_verified_at' => now()]);
            return $this->createNewToken($request->bearerToken());
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User Link. Please try again!'
            ],401);
        }

    }

    public function authCheck()
    {
        try {
            $user = auth()->userOrFail();
            return response()->json([
                'status' => 'success',
                'user' => $user,
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            // return $e->getMessage();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ]);
        }

    }
    public function emailCheck(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:users,email,except,id'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Email is unique',
        ]);
    }

    public function SettingPasswordReset(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required',
            'new_password'=>'required|string|min:6',
            'password_confirmation'=>'required'
        ]);

        $user = User::where('email', $request->email)->pluck('password');
        if (password_verify($request->password,$user[0]))
        {
            $userUpdatePassword = User::where('email', $request->email)->first();
            $userUpdatePassword->update(['password' => Hash::make($request->new_password)]);

        }else {
            return response()->json(['failed','Error'], 400);
        }

        return Helpers::sendResponseBack('updated','password', 'Password successfully updated', 'Something Went wrong please try again');

    }


    public function GetMeetingType(Request $redirectUrl)
    {
        $MeetingType = DB::table('meeting_types')->get();
        if($MeetingType){
            return response()->json([
                'message' => 'Data Get Successfully',
                'data' => $MeetingType,
            ]);
        }else{
            return response()->json([
                'message' => 'error',
            ]);
        }
    }

    public function GetAdviseesProfileData(Request $request)
    {
        $adviseesData = DB::table('advisees')->where('UserID' ,$request->user()->id)->select('AdviseeID','headline','about_me','current_career_goals','just_for_fun','tags_list','cover_profile','profile_goal')->first();
        $userData = DB::table('users')->where('id' ,$request->user()->id)->select('id','firstname','lastname','pronouns')->first();
        $backgroundData = DB::table('user_backgrounds')->where('UserID' ,$request->user()->id)->select('id','time_zone','country','state','city')->first();
        $educationExpData = DB::table('education_experiences')->whereIn('UserID' ,[$request->user()->id])->select('EducationExperienceID','school','graduation_year','degree')->get();
        $workExpData = DB::table('work_experiences')->whereIn('UserID' ,[$request->user()->id])->select('WorkExperienceID','company','title','industry','role','start_date', 'end_date')->get();
        $data=[
            'workExperience' =>$workExpData,
            'advisees' =>$adviseesData,
            'background' =>$backgroundData,
            'educationExperience' =>$educationExpData,
            'userData' =>$userData,
        ];
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Data Fatched',
            'message' => 'Request has been processed',
            'Data' => $data,
        ]);

    }

    public function BasicInfo(Request $request)
    {
        $basicInfo = DB::table('advisees')
            ->leftJoin('users', 'users.id', '=', 'advisees.UserID')
            ->where('UserID' ,$request->user()->id)
            ->select('firstname','lastname','headline','resume','profile_goal','cover_profile','pronouns','tags_list')
        ->get();


        return response()->json([
            "status"=> 200,
            "successMsg" => 'Basic data successfully Fatched',
            'basicInfo' => $basicInfo,
        ]);
    }

    public function updateBasicInfo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'headline' => 'required|string|between:2,100',
            'tags_list' => 'required|string|between:2,100',
            'profile_goal' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }

        $advisee =  DB::table('advisees')->where('UserID', $request->user()->id)->first();
    

        $coverName = '';
        $resumeName = '';
        //  For Profile Image----

        $destinationPath = public_path();
        
        if($request->profile_goal){
            if($advisee->profile_goal){
                File::delete($destinationPath.'/'.$advisee->profile_goal);
            } 
            $imageName = $request->user()->id.'_'.time().'_'.$request->profile_goal->getClientOriginalName(); 
            $request->profile_goal->move(public_path('uploads/users'), $imageName);
            $profilePath = 'uploads/users/'.$imageName;
        }
        
        // dd($imageName);
        
        //  For Cover image ------
        if($request->cover_profile){
            if($advisee->cover_profile){
                File::delete($destinationPath.'/'.$advisee->cover_profile);
            }
            $coverName = $request->user()->id.'_'.time().'_'.$request->cover_profile->getClientOriginalName();  
            $request->cover_profile->move(public_path('uploads/users'), $coverName);
            $coverPath = 'uploads/users/'.$coverName;
        }

        //  For Resume ------
        if($request->resume){
            if($advisee->resume){
                File::delete($destinationPath.'/'.$advisee->resume);
            }
            $resumeName = $request->user()->id.'_'.time().'_'.$request->resume->getClientOriginalName();
            $request->resume->move(public_path('uploads/users'), $resumeName);
            $resumePath = 'uploads/users/'.$resumeName;

        }

        $basicInfoUserArr = array(
            'firstname'=>$request->firstname,
            "lastname" => $request->lastname,
            "pronouns" => $request->pronouns
        );
        $tags_list = json_decode($request->tags_list,true);

        $basicInfoAdviseeArr = array(
            "headline" => $request->headline,
            "tags_list" => $tags_list,
            "profile_goal" => $profilePath,
            "cover_profile" => $coverPath,
            "resume_submitted" => $resumeName?1:0, 
            'resume' => $resumePath,
        );
        // dd($basicInfoAdviseeArr);

        User::where('id', $request->user()->id)->update($basicInfoUserArr);
        DB::table('advisees')->where('UserID', $request->user()->id)->update($basicInfoAdviseeArr);

        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);

    }

    public function UpdateAboutMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about_me' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }
        DB::table('advisees')->where('UserID', $request->user()->id)->update(['about_me' => $request->about_me]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);


    }

    public function UpdateJustForFun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'just_for_fun' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }
        DB::table('advisees')->where('UserID', $request->user()->id)->update(['just_for_fun' => $request->just_for_fun]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);


    }

    public function UpdateCareerInterests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_career_goals' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }
        DB::table('advisees')->where('UserID', $request->user()->id)->update(['current_career_goals' => $request->current_career_goals]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);


    }

    public function GetAdvisorProfileData(Request $request)
    {
        $advisorData      = DB::table('advisors')->where('UserID' ,$request->user()->id)->select('AdvisorID','headline','about_me','just_for_fun','tags_list','cover_profile','profile_goal','profile_video')->first();
        $userData         = DB::table('users')->where('id' ,$request->user()->id)->select('id','firstname','lastname','pronouns')->first();
        $backgroundData   = DB::table('user_backgrounds')->where('UserID' ,$request->user()->id)->select('id','time_zone','country','state','city')->first();
        $educationExpData = DB::table('education_experiences')->whereIn('UserID' ,[$request->user()->id])->select('EducationExperienceID','school','graduation_year','ask_me_about')->get();
        $workExpData      = DB::table('work_experiences')->whereIn('UserID' ,[$request->user()->id])->select('WorkExperienceID','company','title','industry','role','start_date', 'end_date','ask_me_about')->get();
        $data=[
            'workExperience' =>$workExpData,
            'advisor' =>$advisorData,
            'background' =>$backgroundData,
            'educationExperience' =>$educationExpData,
            'userData' =>$userData,
        ];
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Data Fatched',
            'message' => 'Request has been processed',
            'Data' => $data,
        ]);

    }

    public function AdvisorUpdateAboutMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about_me' => 'required|string|between:2,100',
        ]);

        $advisor = DB::table('advisors')->where('UserID', $request->user()->id)->first();

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }

        //  For Advisor Video ------
        $destinationPath = public_path();
        $advisorVideo = '';
        if($request->profile_video){
            if($advisor->profile_video){
                File::delete($destinationPath.'/'.$advisor->profile_video);
            } 
            $advisorVideo = $request->user()->id.'_'.time().'_'.$request->profile_video->getClientOriginalName(); 
            $request->profile_video->move(public_path('uploads/users'), $advisorVideo);
            $videoPath = 'uploads/users/'.$advisorVideo;
            
        }
        

        
        DB::table('advisors')->where('UserID', $request->user()->id)->update([
            'about_me' => $request->about_me,
            'profile_video_bool'=> $advisorVideo?1:0,
            'profile_video' => $videoPath,

        ]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);
    }
    public function AdvisorUpdateJustForFun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'just_for_fun' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }
        DB::table('advisors')->where('UserID', $request->user()->id)->update(['just_for_fun' => $request->just_for_fun]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);


    }

    public function AdvisorCanHelp(Request $request)
    {
        DB::table('advisors')->where('UserID', $request->user()->id)->update(['help' => $request->help]);
        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);
    }

    public function AdvisorBasicInfo()
    {
        $basicInfo = DB::table('advisors')
        ->leftJoin('users', 'users.id', '=', 'advisors.UserID')
        ->where('UserID' ,$request->user()->id)
        ->select('firstname','lastname','headline','profile_goal','cover_profile','pronouns','tags_list')
        ->get();
        return response()->json([
            "status"=> 200,
            "successMsg" => 'Basic data successfully Fatched',
            'basicInfo' => $basicInfo,
        ]);
    }

    public function AdvisorUpdateBasicInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'headline' => 'required|string|between:2,100',
            'tags_list' => 'required|string|between:2,100',
            'profile_goal' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => 'The server understood the request, but is refusing to fulfill it',
            ]);
        }

        $advisee =  DB::table('advisors')->where('UserID', $request->user()->id)->first();
    

        $coverName = '';
        $resumeName = '';
        //  For Profile Image----

        $destinationPath = public_path();
        
        if($request->profile_goal){
            if($advisee->profile_goal){
                File::delete($destinationPath.'/'.$advisee->profile_goal);
            } 
            $imageName = $request->user()->id.'_'.time().'_'.$request->profile_goal->getClientOriginalName(); 
            $request->profile_goal->move(public_path('uploads/users'), $imageName);
            $profilePath = 'uploads/users/'.$imageName;
        }
        
        // dd($imageName);
        
        //  For Cover image ------
        if($request->cover_profile){
            if($advisee->cover_profile){
                File::delete($destinationPath.'/'.$advisee->cover_profile);
            }
            $coverName = $request->user()->id.'_'.time().'_'.$request->cover_profile->getClientOriginalName();  
            $request->cover_profile->move(public_path('uploads/users'), $coverName);
            $coverPath = 'uploads/users/'.$coverName;
        }

       

        $basicInfoUserArr = array(
            'firstname'=>$request->firstname,
            "lastname" => $request->lastname,
            "pronouns" => $request->pronouns
        );
        $tags_list = json_decode($request->tags_list,true);

        $basicInfoAdviseeArr = array(
            "headline" => $request->headline,
            "tags_list" => $tags_list,
            "profile_goal" => $profilePath,
            "cover_profile" => $coverPath,
        );
        // dd($basicInfoAdviseeArr);

        User::where('id', $request->user()->id)->update($basicInfoUserArr);
        DB::table('advisors')->where('UserID', $request->user()->id)->update($basicInfoAdviseeArr);

        return response()->json([
            'statusCode' => 200,
            'Success' => 'Successfully Updated',
            'message' => 'Request has been processed',
        ]);
    }

    public function completeProfile(Request $request)
    {
        if($request->user()->hasRole('Advisee')){

            $completeProfileData = DB::table('advisees')
            ->where('UserID' ,$request->user()->id)
            ->select('headline','profile_goal','about_me','initial_career_goals','tags_list')
            ->get();
            $url = url('/');

            return response()->json([
                "status"=> 200,
                "successMsg" => 'Basic data successfully Fatched',
                'completeProfileData' => $completeProfileData,
                'base_url' => $url,
              
               
            
            ]);
        }else if($request->user()->hasRole('Advisor')){
            $completeProfileData = DB::table('Advisors')
            ->where('UserID' ,$request->user()->id)
            ->select('headline','profile_goal','about_me','help','tags_list')
            ->get();
            
            $url = url('/');
            
            return response()->json([
                "status"=> 200,
                "successMsg" => 'Basic data successfully Fatched',
                'completeProfileData' => $completeProfileData,
                'base_url' => $url,
                
            ]);
        }else{
            return response()->json([
                "status"=> 401,
                "messsage" => 'unauthorized or expire login token',
            ]);
        }
    }

    public function updateCompleteProfile(Request $request)
    {

        if($request->user()->hasRole('Advisee')){
            // dd($request->all());

            $validator = Validator::make($request->all(), [
                'headline' => 'required|string|between:2,100',
                'profile_goal' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tags_list' => 'required',
                'about_me' => 'required|string',
                'initial_career_goals' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => 'The server understood the request, but is refusing to fulfill it',
                ]);
            }

            $advisee =  DB::table('advisees')->where('UserID', $request->user()->id)->first();

            
            $destinationPath = public_path();
            
            if($request->profile_goal){
                if($advisee->profile_goal){
                    // return response()->json($destinationPath.'/'.$advisee->profile_goal);
                    File::delete($destinationPath.'/'.$advisee->profile_goal);
                } 
                $imageName = $request->user()->id.'_'.time().'_'.$request->profile_goal->getClientOriginalName(); 
                $request->profile_goal->move(public_path('uploads/users'), $imageName);
                $imagePath = 'uploads/users/'.$imageName;
               
            }
            DB::table('advisees')
            ->where('UserID' ,$request->user()->id)
            ->update([
                'headline' => $request->headline,
                'profile_goal' => $imagePath,
                'tags_list' => json_decode($request->tags_list),
                'about_me' => $request->about_me,
                'initial_career_goals' => $request->initial_career_goals,
            ]);


            $completeProfileData = DB::table('advisees')
            ->where('UserID' ,$request->user()->id)
            ->select('headline','profile_goal','about_me','initial_career_goals','tags_list')
            ->get();

            $url = url('/');
            
            return response()->json([
                "status"=> 200,
                "successMsg" => 'Basic data successfully Fatched',
                'completeProfileData' => $completeProfileData,
                'base_url' => $url,
            ]);


        }else if($request->user()->hasRole('Advisor')){
            $validator = Validator::make($request->all(), [
                'headline' => 'required|string|between:2,100',
                'profile_goal' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tags_list' => 'required|string',
                'about_me' => 'required|string',
                'help' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => 'The server understood the request, but is refusing to fulfill it',
                ]);
            }

            $advisee =  DB::table('advisors')->where('UserID', $request->user()->id)->first();

            $destinationPath = public_path();
            
            if($request->profile_goal){
                if($advisee->profile_goal){

                    File::delete($destinationPath.'/'.$advisee->profile_goal);
                } 
                $imageName = $request->user()->id.'_'.time().'_'.$request->profile_goal->getClientOriginalName(); 
                $request->profile_goal->move(public_path('uploads/users'), $imageName);
                $imagePath = 'uploads/users/'.$imageName;
            }
            DB::table('advisors')
            ->where('UserID' ,$request->user()->id)
            ->update([
                'headline' => $request->headline,
                'profile_goal' => $imagePath,
                'tags_list' => json_decode($request->tags_list),
                'about_me' => $request->about_me,
                'help' => $request->help,
            ]);


            $completeProfileData = DB::table('advisees')
            ->where('UserID' ,$request->user()->id)
            ->select('headline','profile_goal','about_me','help','tags_list')
            ->get();
            $url = url('/');
            
            return response()->json([
                "status"=> 200,
                "successMsg" => 'Basic data successfully Fatched',
                'completeProfileData' => $completeProfileData,
                'base_url' => $url,
            ]);

        }else{
            return response()->json([
                "status"=> 401,
                "messsage" => 'unauthorized or expire login token',
            ]);
        }



        
    }
    public function StoreAdvisorService(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'meeting_type' => 'required|string|between:2,100',
        //     'time' => 'required|string|between:2,100',
        //     'timezone' => 'required|string|between:2,100',
        //     'offer_meeting_each_month' => 'required|string|between:2,100',
        //     'availability_time' => 'required|string|between:2,100',
        // ]);
        
        // if($validator->fails()){
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'The server understood the request, but is refusing to fulfill it',
        //     ]);
        // }
        $services =  DB::table('user_services')->where('AdvisorID', $request->user()->AdvisorID)->first();
        if($services){
            DB::table('user_services')->whereIn('AdvisorID', [$request->user()->AdvisorID])->delete();
            foreach($request->meeting_type as $key => $value){
                $servicesArr = array(
                    'meeting_type' => $value,
                    'time' => $request->time[$key],
                    'timezone' => $request->timezone,
                    'offer_meeting_each_month' => $request->offer_meeting_each_month,
                    'availability_time' => $request->availability_time,
                    'unavailable' => $request->availability_time ? 0 : 1,
                    'AdvisorID' => $request->user()->AdvisorID,
                    'UserID' => $request->user()->id,
                );
                $data = DB::table('user_services')->create($servicesArr);
            }
        }else{
            foreach($request->meeting_type as $key => $value){
                
               
                $servicesArr = array(
                    'meeting_type' => $value,
                    'time' => $request->time[$key],
                    'timezone' => $request->timezone,
                    'offer_meeting_each_month' => $request->offer_meeting_each_month,
                    'availability_time' => $request->availability_time,
                    'unavailable' => $request->availability_time ? 0 : 1,
                    'AdvisorID' => $request->user()->AdvisorID,
                    'UserID' => $request->user()->id,
                );
                // echo "<pre>";
                // print_r($servicesArr);
                $data =   DB::table('user_services')->create($servicesArr);
            }
            // die;
        }

        if($data){
            return response()->json([
                'statusCode' => 200,
                'Success' => 'Successfully Updated Or Created',
                'message' => 'Request has been processed',
            ]);
        }else{
            return response()->json([
                'statusCode' => 400,
                'Success' => 'Request failed',
                'message' => 'Request has been cancel',
            ]);
        }

    }


    public function GetQuiz(Request $redirectUrl)
    {
        $Quiz = DB::table('quiz')->orderBy('updated_at', 'asc')->get();
        if($Quiz){
            return response()->json([
                'message' => 'Data Get Successfully',
                'data' => $Quiz,
            ]);
        }else{
            return response()->json([
                'message' => 'error',
            ]);
        }
    }


   
    
}
