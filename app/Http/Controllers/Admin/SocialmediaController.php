<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Socialmedia;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SocialmediaController extends Controller
{
    public function index()
    {
        $socialdata = Socialmedia::all();
        $count = count($socialdata);
       return view('admin.socialmedia',compact('socialdata','count'));
    }

    public function socialStore(Request $request){
        

        try {
              Socialmedia::create([
                'title' => $request->title,
                'mediaurl' => $request->mediaurl,
                'apikey' => $request->apikey,
                'channelid' => $request->channelid,
                'counts' => $request->counts,
            ]);
            return redirect()->route('admin.social.index')->with(['type' => 'success', 'message' =>'Social media Saved.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Social media could not be saved.']);
        }

    }


    public function edit(){
        $socialdata = Socialmedia::all();
        $count = count($socialdata);
        return view('admin.socialmedia',compact('socialdata','count'));
    }

    public function socialupdate(Request $request){
       
// dd($request->all());
        try {

            $socialmedia = Socialmedia::find($request->id);
            if ($socialmedia) {
                $socialmedia->update([
                    'title' => $request->title,
                    'mediaurl' => $request->mediaurl,
                    'apikey' => $request->apikey,
                    'channelid' => $request->channelid,
                    'counts' => $request->counts,
                ]);
            } 
            return redirect()->route('admin.social.index')->with(['type' => 'success', 'message' =>'Social media Has Been Updated.']);
            
        } catch (Throwable $th) {
           dd($th);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Social media could not be updated.']);
        }

    }
}
