<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Transactions;
use App\Models\Users;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use Validator;
use Illuminate\Support\Facades\File;

class ShippingContainerController extends Controller
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
        $shippingContainer = TBLShippingContainer::orderBy('updated_at', 'desc')->where('Status', 1)->paginate(5);
        // dd($shippingContainer);
        return view('shippingContainerManagement.index', compact('shippingContainer'));
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
        $TBLShippingContainer = TBLShippingContainer::where('Sc_ID', $id)->where('Status', 1)->first();
    //    dd($TBLShippingContainer);
        return view('shippingContainerManagement.edit', compact('TBLShippingContainer'));
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
        $validator = Validator::make($request->all(), [
            'Name' => 'required',
            'Description' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        $destinationPath = env('IMAGE_FULL_PATH');
        $image_delete_Path = env('IMAGE_PUBLIC_PATH');
        $imagePath ='';
        $preImage = TBLShippingContainer::where('Sc_ID', $id)->first();
        if($request->file('Featured_Image')){
            if($preImage->Featured_Image){
                File::delete($image_delete_Path.'/'.$preImage->Featured_Image);
            } 
            $imageName = $id.'_'.time().'_'.$request->Featured_Image->getClientOriginalName(); 
            $request->Featured_Image->move($destinationPath, $imageName);
            $imagePath = 'uploads/users/'.$imageName;
        }
        TBLShippingContainer::where('Sc_ID', $id)->update([
            'Featured_Image' => $imagePath ? $imagePath : $preImage->Featured_Image,
            'Name' => $request->Name,
            'Description' => $request->Description,
        ]);
       
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
   
        $ShippingContainer = TBLShippingContainer::where('Sc_ID', $id)->update(['Status' => '0']);
        $ShippingContainerPlacements = TBLShippingContainerPlacements::whereIn('SC_ID', [$id])->update(['status' => '0']);
        return back()->with('success', 'Deleted Successfully!');
    }
}

