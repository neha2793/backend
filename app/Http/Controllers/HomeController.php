<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Orders;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::orderBy('updated_at','desc')->get();
        $transaction = Transactions::orderBy('updated_at','desc')->get();

        return view('index', compact('users', 'transaction'));
    }

    public function Transactions(Request $request)
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
        $usd = json_decode($response) ?json_decode($response)->USD : '';
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
            'user_nfts.name'
            )
        ->paginate(5);
        foreach($transaction as $key => $value){
            $value->UsdPrice = $usd * $value->Price;
        }
        return view('transaction', compact('transaction'));
    }
}
