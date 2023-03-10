<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItems;
use App\Models\SCVideos;
use App\Models\Transactions;
use App\Models\Users;
use App\Models\TBLShippingContainer;
use App\Models\TBLShippingContainerPlacements;
use Validator;
use Illuminate\Support\Facades\File;

class ShippingContainerVideoController extends Controller
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
        $SCVideo = SCVideos::orderBy('updated_at', 'desc')->paginate(5);
        return view('SCVIdeoManagement.index', compact('SCVideo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SCVIdeoManagement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        // dd($request->all());

        
        $destinationPath = public_path();
        $full_path = public_path().'/uploads/users/';
        
        $imagePath ='';
        if($request->file('video')){
          
            $imageName = '_'.time().'_'.$request->video->getClientOriginalName(); 
            $request->video->move($full_path, $imageName);
            $imagePath = 'uploads/users/'.$imageName;
        }
        SCVideos::create([
            'video' => $imagePath,
            'name' => $request->name,
            'description' => $request->description,
            'redirection_link' => $request->redirection_link,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SCVideos = SCVideos::where('id', $id)->first();
        return view('SCVIdeoManagement.edit', compact('SCVideos'));
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
            'name' => 'required',
            'description' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
       
        $destinationPath = public_path();
        $full_path = public_path().'/uploads/users/';
       
        $imagePath ='';
        $preImage = SCVideos::where('id', $id)->first();
        if($request->file('video')){
            if($preImage->video){
                File::delete($destinationPath.'/'.$preImage->video);
            } 
            $imageName = $id.'_'.time().'_'.$request->video->getClientOriginalName(); 
            $request->video->move($full_path, $imageName);
            $imagePath = 'uploads/users/'.$imageName;
        }
        SCVideos::where('id', $id)->update([
            'video' => $imagePath ? $imagePath : $preImage->video,
            'name' => $request->name,
            'description' => $request->description,
            'redirection_link' => $request->redirection_link,
            'type' => $request->type,
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
   
        $ShippingContainer = SCVideos::where('id', $id)->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}

