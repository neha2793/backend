<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\SlimeTour;
use Validator;
use Illuminate\Support\Facades\File;

class SlimeTourController extends Controller
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
        $slime_tours = SlimeTour::orderBy('updated_at', 'desc')->paginate(5);
       
        return view('slimeTour.index', compact('slime_tours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slimeTour.create');
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
            'image' => 'required',
            'name' => 'required',
            'location' => 'required',
            'date' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        // dd($request->all());

        $destinationPath = public_path().'/uploads/slimeTour';
        $imagePath ='';
        if($request->file('image')){
          
            $imageName = '_'.time().'_'.$request->image->getClientOriginalName(); 
            $request->image->move($destinationPath, $imageName);
            $imagePath = 'uploads/slimeTour/'.$imageName;
        }
        SlimeTour::create([
            'image' => $imagePath,
            'name' => $request->name,
            'location' => $request->location,
            'date' => $request->date,
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
        $slimeTour = SlimeTour::where('id', $id)->first();
        return view('slimeTour.edit', compact('slimeTour'));
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
            'location' => 'required',
            'date' => 'required',
            // 'Featured_Image' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        $destinationPath = public_path().'/uploads/slimeTour';
        $imagePath ='';
        $preImage = SlimeTour::where('id', $id)->first();
        if($request->file('image')){
            if($preImage->image){
                File::delete($destinationPath.'/'.$preImage->image);
            } 
            $imageName = $id.'_'.time().'_'.$request->image->getClientOriginalName(); 
            $request->image->move($destinationPath, $imageName);
            $imagePath = 'uploads/slimeTour/'.$imageName;
        }
        SlimeTour::where('id', $id)->update([
            'image' => $imagePath ? $imagePath : $preImage->image,
            'name' => $request->name,
            'location' => $request->location,
            'date' => $request->date,
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
   
        $SlimeTour = SlimeTour::where('id', $id)->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}

