<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OurTeam;
use Illuminate\Http\Request;
use Throwable;

class OurteamController extends Controller
{

    public function index()
    {
        $ourteams = OurTeam::orderBy('id','desc')->get();
        return view('admin.ourteam.index',compact('ourteams'));
    }

    public function create()
    {
        $categories = Category::where('parent','ourteams')->get();
        return view('admin.ourteam.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ]);
        try {
        $ourteam = new OurTeam();
        $ourteam->title = $request->title;
        $ourteam->role = $request->role;
        $ourteam->category_id = $request->category_id;
        $ourteam->status =  1 ;
        $ourteam->content = $request->content;
        $ourteam->media_id = $request->media_id ?? 1;
        $ourteam->save();
        return redirect()->route('admin.ourteams.index')->with('success', 'Our Team created successfully.');
        } catch (Throwable $th) {
           
            return redirect()->back()->with('error', 'Our Team could not be created.');
        }
        dd($request->all());
    }

    public function edit(OurTeam $ourteam)
    {
        $categories = Category::where('parent','ourteams')->get();
        return view('admin.ourteam.edit',compact('ourteam','categories'));
    }

    public function update(Request $request, OurTeam $ourteam)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ]);
        try {
        $ourteam->title = $request->title;
        $ourteam->role = $request->role;
        $ourteam->category_id = $request->category_id;
        $ourteam->content = $request->content;
        $ourteam->media_id = $request->media_id ?? 1;
        $ourteam->save();
        return redirect()->route('admin.ourteams.index')->with('success', 'Our Team updated successfully.');
        } catch (Throwable $th) {
            
            return redirect()->back()->with('error', 'Our Team could not be updated.');
        }
    }
    public function delete($ourteam)
    {
        $ourteam = OurTeam::find($ourteam);
        $ourteam->delete();
        return redirect()->route('admin.ourteams.index')->with('success', 'Our Team deleted successfully.');
    }
    public function show(){

        $ourteams = OurTeam::onlyTrashed()->get();
        return view('admin.ourteam.trash',compact('ourteams'));
    }

    public function recover($ourteam)
    {
        $ourteam = OurTeam::withTrashed()->find($ourteam);
        $ourteam->restore();
        return redirect()->route('admin.ourteams.index')->with('success', 'Our Team restored successfully.');
    }

    public function destroy($ourteam)
    {
        $ourteam = OurTeam::withTrashed()->find($ourteam);
        $ourteam->forceDelete();
        return redirect()->route('admin.ourteams.index')->with('success', 'Our Team deleted permanently.');
    }
    public function updateorder(Request $request)
    {
        $order = $request->id;
        
             OurTeam::find($order)->update([
                'status' => $request->status == true ? 1 : 0
            ]);
        
            return $request->status;
    }

}
