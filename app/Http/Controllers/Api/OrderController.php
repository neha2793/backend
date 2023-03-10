<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\SCVideos;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use App\Models\User;
use App\Models\Orders;
use App\Models\Transactions;
use App\Models\UserNFT;
use App\Models\OrderItems;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Mail\OrderPlacedMail;
use App\Mail\AdminOrderPlacedMail;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{

   

    public function OrderPlace(Request $request)
    {
        // dd($request->item['Product_ID']);

        // Validation  Work Start here......
            // $validator = Validator::make($request->shippingDetail, [
            //     'Shipping_FirstName' => 'required',
            //     'Shipping_lastName' => 'required',
            //     'Shipping_address1' => 'required',
            //     'Shipping_address2' => 'required',
            //     'Shipping_city'     =>  'required',
            //     'Shipping_state'     =>  'required',
            //     'Shipping_Zipcode'     =>  'required',
            // ]);
            
            // if ($validator->fails()) {
            //     return response()->json([
            //         'error' => $validator->errors()->first(),
            //     ], 400);
            // }

            $validatorItem = Validator::make($request->item, [
                'Price' => 'required',
                'Quantity' => 'required',
                'Product_ID' => 'required',
                'Transaction_Token' => 'required',
            ]);
            
            if ($validatorItem->fails()) {
                return response()->json([
                    'error' => $validatorItem->errors()->first(),
                ], 400);
            }

            $validatorBilling = Validator::make($request->billingDetail, [
                'email' => 'required',
                'country' => 'required',
            ]);
            if ($validatorBilling->fails()) {
                return response()->json([
                    'error' => $validatorBilling->errors()->first(),
                ], 400);
            }
        // Validation  Work End here......
        if(@$request->billingDetail['user_id']){
        
            $user = User::where('id', $request->billingDetail['user_id'])->first();
            if($user->email == $request->billingDetail['email']){

                User::where('id', $user->id)->update([
                    'name' => $request->billingDetail['name'],
                    'lastname' => $request->billingDetail['lastname'],
                    'address' => $request->billingDetail['address'],
                    'city' => $request->billingDetail['city'],
                    'country' => $request->billingDetail['country'],
                    'state' => $request->billingDetail['state'],
                    'pincode' => $request->billingDetail['pincode'],
                    'email' => $request->billingDetail['email'],
                ]);
                $order = Orders::create([
                    'User_ID' => $user->id,
                    'Shipping_FirstName' => $request->shippingDetail['Shipping_FirstName'],
                    'Shipping_lastName' => $request->shippingDetail['Shipping_lastName'],
                    'Shipping_address1' => $request->shippingDetail['Shipping_address1'],
                    'Shipping_address2' => $request->shippingDetail['Shipping_address2'],
                    'country' => $request->shippingDetail['shipping_country'],
                    'Shipping_city' => $request->shippingDetail['Shipping_city'],
                    'Shipping_state' => $request->shippingDetail['Shipping_state'],
                    'Shipping_Zipcode' => $request->shippingDetail['Shipping_Zipcode'],
                    'Order_total' => $request->item['Quantity'] *  $request->item['Price'],
                    'Order_date' => Carbon::now(),
                    'Status' => 1,
                ]);

                $orderItem = OrderItems::create([
                    'Order_ID' => $order->id,
                    'Product_ID' => $request->item['Product_ID'],
                    'Quantity' => $request->item['Quantity'],
                    'Price' => $request->item['Price'],
                ]);

                $transaction = Transactions::create([
                    'Order_ID' => $order->id,
                    'User_ID' => $user->id,
                    'T_Amount' =>  $request->item['Price'],
                    'Transaction_Token' => $request->item['Transaction_Token'],
                    'Transaction_Date' => Carbon::now(),
                    'Status' => 'Pending',
                ]);

                UserNFT::where('id', $request->item['Product_ID'])
                ->update([
                    'order_count'=> DB::raw('order_count+1'),
                ]);
                $product_detail = UserNFT::where('id', $request->item['Product_ID'])->first();
                $order->product_name = $product_detail->name;
                $order->amount = $request->item['Price'];
                $order->qty = $request->item['Quantity'];

                \Mail::to($user->email)->send(new OrderPlacedMail($order));
                \Mail::to('beingprofessional123@gmail.com')->send(new AdminOrderPlacedMail($order));

                return response()->json([
                    'status' => 200,
                    'message' => 'Order placed Successfully!',
                    'order' => $order,
                    'payment_method' => $transaction->Payment_Method,
                ]); 
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'User Already Exist!',
                ]);
            }
        }else{
        
            // Validation  Work Start here......
                // $validatorShipping = Validator::make($request->shippingDetail, [
                //     'Shipping_FirstName' => 'required',
                //     'Shipping_lastName' => 'required',
                //     'Shipping_address1' => 'required',
                //     'Shipping_address2' => 'required',
                //     'Shipping_city'     =>  'required',
                //     'Shipping_state'     =>  'required',
                //     'Shipping_Zipcode'     =>  'required',
                // ]);
                
                // if ($validatorShipping->fails()) {
                //     return response()->json([
                //         'error' => $validatorShipping->errors()->first(),
                //     ], 400);
                // }

                $validatorItem = Validator::make($request->item, [
                    'Price' => 'required',
                    'Quantity' => 'required',
                    'Product_ID' => 'required',
                    'Transaction_Token' => 'required',
                ]);
                
                if ($validatorItem->fails()) {
                    return response()->json([
                        'error' => $validatorItem->errors()->first(),
                    ], 400);
                }

                $validatorBilling = Validator::make($request->billingDetail, [
                    'email' => 'required',
                    'country' => 'required',
                ]);
                
                if ($validatorBilling->fails()) {
                    return response()->json([
                        'error' => $validatorBilling->errors()->first(),
                    ], 400);
                }

            
                $validator = Validator::make($request->billingDetail, [
                    'name' => 'required|string|between:2,100',
                    'lastname' => 'required|string|between:2,100',
                    'email' => 'required|string|email|max:100|unique:users',
                    'password' => 'required|string|min:6',
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'messages' => $validator->errors()->first(),
                    ], 400);
                }
            // Validation  Work End here......

            $userDetail = $validator->validated();
            $user = User::create([
                'name' => $request->billingDetail['name'],
                'lastname' => $request->billingDetail['lastname'],
                'address' => $request->billingDetail['address'],
                'city' => $request->billingDetail['city'],
                'state' => $request->billingDetail['state'],
                'country' => $request->billingDetail['country'],
                'pincode' => $request->billingDetail['pincode'],
                'email' => $request->billingDetail['email'],
                'password' => bcrypt($request->billingDetail['password']),
            ]);
            $vToken = Str::random(50);
            $id = encrypt($user->id);

            if (!$token = auth('api')->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }


            $order = Orders::create([
                'User_ID' => $user->id,
                'Shipping_FirstName' => $request->shippingDetail['Shipping_FirstName'],
                'Shipping_lastName' => $request->shippingDetail['Shipping_lastName'],
                'Shipping_address1' => $request->shippingDetail['Shipping_address1'],
                'Shipping_address2' => $request->shippingDetail['Shipping_address2'],
                'country' => $request->shippingDetail['shipping_country'],
                'Shipping_city' => $request->shippingDetail['Shipping_city'],
                'Shipping_state' => $request->shippingDetail['Shipping_state'],
                'Shipping_Zipcode' => $request->shippingDetail['Shipping_Zipcode'],
                'Order_total' => $request->item['Quantity'] *  $request->item['Price'],
                'Order_date' => Carbon::now(),
                'Status' => 1,
            ]);
           
            $orderItem = OrderItems::create([
                'Order_ID' => $order->id,
                'Product_ID' => $request->item['Product_ID'],
                'Quantity' => $request->item['Quantity'],
                'Price' => $request->item['Price'],
            ]);

            $transaction = Transactions::create([
                'Order_ID' => $order->id,
                'User_ID' => $user->id,
                'T_Amount' =>  $request->item['Price'],
                'Transaction_Token' => $request->item['Transaction_Token'],
                'Transaction_Date' => Carbon::now(),
                'Status' => 'Pending',
            ]);

            UserNFT::where('id', $request->item['Product_ID'])
                ->update([
                'order_count'=> DB::raw('order_count+1'),
            ]);

            $product_detail = UserNFT::where('id', $request->item['Product_ID'])->first();
            $order->product_name = $product_detail->name;
            $order->amount = $request->item['Price'];
            $order->qty = $request->item['Quantity'];

            \Mail::to($user->email)->send(new OrderPlacedMail($order));
            \Mail::to('beingprofessional123@gmail.com')->send(new AdminOrderPlacedMail($order));

            return response()->json([
                'status' => 200,
                'message' => 'Order placed Successfully!',
                'order' => $order,
                'payment_method' => $transaction->Payment_Method,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]);
        }
    }

    public function Ordernft(Request $request)
    {
       
        // Validation  Work Start here......
        // $validator = Validator::make($request->shippingDetail, [
        //     'Shipping_FirstName' => 'required',
        //     'Shipping_lastName' => 'required',
        //     'Shipping_address1' => 'required',
        //     'Shipping_address2' => 'required',
        //     'Shipping_city'     =>  'required',
        //     'Shipping_state'     =>  'required',
        //     'Shipping_Zipcode'     =>  'required',
        // ]);
        
        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()->first(),
        //     ], 400);
        // }

        // $validatorItem = Validator::make($request->item, [
        //     'Price' => 'required',
        //     'Quantity' => 'required',
        //     'Product_ID' => 'required',
        //     'Transaction_Token' => 'required',
        // ]);
        
        // if ($validatorItem->fails()) {
        //     return response()->json([
        //         'error' => $validatorItem->errors()->first(),
        //     ], 400);
        // }

        // $validatorBilling = Validator::make($request->billingDetail, [
        //     'email' => 'required',
        // ]);
        // if ($validatorBilling->fails()) {
        //     return response()->json([
        //         'error' => $validatorBilling->errors()->first(),
        //     ], 400);
        // }
        // Validation  Work End here......
       
        
        $user = User::where('id', $request->user()->id)->first();
       
        User::where('id', $user->id)->update([
            'name' => $request->billing_name,
            'lastname' => $request->billing_lastname,
            'address' =>$request->billing_address,
            'city' =>$request->billing_city,
            'state' =>$request->billing_state,
            'pincode' =>$request->billing_pincode,
            'email' =>$request->billing_email,
        ]);
        $order = Orders::create([
            'User_ID' => $user->id,
            'Shipping_FirstName' => $request->Shipping_FirstName,
            'Shipping_lastName' => $request->Shipping_lastName,
            'Shipping_address1' => $request->Shipping_address1,
            'Shipping_address2' => $request->Shipping_address2,
            'Shipping_city' => $request->Shipping_city,
            'Shipping_state' => $request->Shipping_state,
            'Shipping_Zipcode' => $request->Shipping_Zipcode,
            'Order_total' => $request->item_quantity *  $request->item_price,
            'Order_date' => Carbon::now(),
            'Status' => 1,
        ]);

        $orderItem = OrderItems::create([
            'Order_ID' => $order->id,
            'Product_ID' => $request->item_product_id,
            'Quantity' => $request->item_quantity,
            'Price' => $request->item_price,
        ]);

        $transaction = Transactions::create([
            'Order_ID' => $order->id,
            'User_ID' => $user->id,
            'T_Amount' =>  $request->item_price,
            'Transaction_Token' => $request->item_Transaction_Token,
            'Transaction_Date' => Carbon::now(),
            'Status' => 'Pending',
        ]);

        UserNFT::where('id', $request->item_product_id)
        ->update([
            'order_count'=> DB::raw('order_count+1'),
        ]);
        $product_detail = UserNFT::where('id', $request->item_product_id)->first();
        $order->product_name = $product_detail->name;
        $order->amount = $request->item_price;
        $order->qty = $request->item_quantity; 

        \Mail::to($user->email)->send(new OrderPlacedMail($order));
        \Mail::to('beingprofessional123@gmail.com')->send(new AdminOrderPlacedMail($order));

        return response()->json([
            'status' => 200,
            'message' => 'Order placed Successfully!',
            'order' => $order,
            'payment_method' => $transaction->Payment_Method,
        ]); 
        
       
    }

    public function GetTransactionHistory(Request $request)
    {
        $transaction = Orders::
        leftjoin('TBL_Transactions', 'TBL_Orders.Order_ID', '=', 'TBL_Transactions.Order_ID')
        ->leftjoin('TBL_Order_items', 'TBL_Orders.Order_ID', '=', 'TBL_Order_items.Order_ID')
        ->leftjoin('user_nfts', 'TBL_Order_items.Product_ID', '=', 'user_nfts.id')
        ->where('TBL_Orders.User_ID', $request->user()->id)
        ->select('TBL_Transactions.Order_ID', 'user_nfts.name', 'TBL_Transactions.T_Amount', 'TBL_Transactions.Status')
        ->get();
        return response()->json([
            'status' => 200,
            'message' => 'Get Transaction History Successfully!',
            'data' => $transaction,
        ]);
    }
}

