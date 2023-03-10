<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Transactions;
use App\Models\Users;
use Validator;
use Illuminate\Support\Facades\File;

class OrderManagementController extends Controller
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
        $transaction = Orders::
        leftjoin('TBL_Transactions', 'TBL_Orders.Order_ID', '=', 'TBL_Transactions.Order_ID')
        ->leftjoin('TBL_Order_items', 'TBL_Orders.Order_ID', '=', 'TBL_Order_items.Order_ID')
        ->leftjoin('user_nfts', 'TBL_Order_items.Product_ID', '=', 'user_nfts.id')
        ->select(
            'TBL_Transactions.T_Amount',
            'TBL_Transactions.Order_ID', 
            'TBL_Transactions.Status',
            'TBL_Order_items.Quantity', 
            'TBL_Order_items.Price',
            'TBL_Orders.Shipping_FirstName', 
            'TBL_Orders.Shipping_lastName', 
            'TBL_Orders.Order_total', 
            'TBL_Orders.Order_date', 
            'user_nfts.name', 
            )
        ->paginate(5);
        foreach($transaction as $key => $value){
            $value->UsdPrice = $usd * $value->Price;
        }
        // dd($transaction);
        return view('orderManagement.index', compact('transaction'));
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
        $transaction = Orders::
        leftjoin('TBL_Transactions', 'TBL_Orders.Order_ID', '=', 'TBL_Transactions.Order_ID')
        ->leftjoin('TBL_Order_items', 'TBL_Orders.Order_ID', '=', 'TBL_Order_items.Order_ID')
        ->leftjoin('user_nfts', 'TBL_Order_items.Product_ID', '=', 'user_nfts.id')
        ->where('TBL_Orders.Order_ID', $id)
        ->select(
            'TBL_Transactions.Status',
            'TBL_Order_items.Price',
            'TBL_Order_items.Quantity', 
            'TBL_Orders.*', 
            'user_nfts.name', 
            'user_nfts.image', 
            )
        ->first();
        $transaction->UsdPrice = $usd * $transaction->Price;
        // dd($transaction);

        return view('orderManagement.edit', compact('transaction'));
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
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'lastname' => 'required',
        //     'email' => 'required',
        //     'phone_number' => 'required',
        //     'wallet_address' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return back()->with('error', $validator->errors()->first());
        // }

        // $destinationPath = env('IMAGE_FULL_PATH');
        // $image_delete_Path = env('IMAGE_PUBLIC_PATH');
        // $profilePath ='';
        // $preUser = Users::where('id', $id)->first();
        // if($request->file('profile_image')){
        //     if($preUser->profile_image){
        //         File::delete($image_delete_Path.'/'.$preUser->profile_image);
        //     } 
        //     $imageName = $id.'_'.time().'_'.$request->profile_image->getClientOriginalName(); 
        //     $request->profile_image->move($destinationPath, $imageName);
        //     $profilePath = 'uploads/users/'.$imageName;
        // }
        // Users::where('id', $id)->update([
        //     'profile_image' => $profilePath ? $profilePath : $preUser->profile_image,
        //     'phone_number' => $request->phone_number,
        //     'name' => $request->name,
        //     'lastname' => $request->lastname,
        //     'email' => $request->email,
        //     'wallet_address' => $request->wallet_address,
        // ]);
       
        // return back()->with('success', 'Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $users = Users::where('id', $id)->delete();
        // return back()->with('success', 'Deleted Successfully!');
    }
}
