<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;
use App\Models\User;

class WishlistController extends Controller
{
    public function AddWishlist(Request $request)
    {
        if($request->product_id){
            $wishlist =   Wishlist::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Wishlist added successfully',
                'wishlist' => $wishlist,
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Product id required',
            ]);
        }
    }

    public function Wishlist(Request $request)
    {
        $wishlist = Wishlist::leftjoin('user_nfts','user_nfts.id', '=', 'wishlist.product_id')
        ->where('wishlist.user_id', $request->user()->id)
        ->select('wishlist.id as wishlist_id', 'user_nfts.*')
        ->get();
        return response()->json([
            'status' => 200,
            'message' => 'Wishlist get successfully',
            'wishlist' => $wishlist,
        ]);
    }

    public function DeleteWishlist(Request $request)
    {
        if($request->wishlist_id){
            Wishlist::where('id', $request->wishlist_id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Wishlist delete successfully',
            ]);
                
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Wishlist id required',
            ]);
        }
    }
     
}
