<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Option;
use App\Models\Slide;
use Illuminate\Http\Request;
use Throwable;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('order','asc')->get();
        $languages = Option::where('key','=','language')->get();
        return view('admin.slides.index',compact('slides','languages'));
    }

    public function create()
    {
        $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
        $categories = Category::all();
        return view('admin.slides.create',compact('languages','categories'));
    }
    public function store(Request $request)
    {
        try{
           
            $slide = new Slide;
            if ($request->hasFile('images')) {
                $File = $request->file('images');
                $imageName = time() . '_' . $File->getClientOriginalName();
                $File->move(public_path('slideimages'), $imageName);
                $slide->bg = $imageName;
                }
                $slide->order = $request->order ?? '0';
                 $slide->title = $request->title ?? '';
                // $slide->content = $request->content ?? '';
                $slide->save();
            return redirect()->route('admin.slide.index')->with('success', 'Images uploaded successfully.');
       
        } catch (Throwable $th){
           
            return redirect()->route('admin.slide.index')->with('error', 'No images selected.');
        }
    
        
   
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    { 
        $categories = Category::all();
        $slide = Slide::find($id);
        $languages = Option::where('key','=','language')->get();
        return view('admin.slides.edit',compact('slide','languages','categories'));
    }

    public function update(Request $request, $id)
    {
      
      try{
        
        $slide = Slide::find($id);
        if ($request->hasFile('images')) {
            $File = $request->file('images');
            $imageName = time() . '_' . $File->getClientOriginalName();
            $File->move(public_path('slideimages'), $imageName);
            $slide->bg = $imageName;
        }   
            $slide->order = $request->order ?? '0';
            $slide->title = $request->title ?? '';
            // $slide->content = $request->content ?? '';
            $slide->save();
       
        return redirect()->route('admin.slide.index')->with('success', 'Images uploaded successfully.');
    } catch (Throwable $th) {
            return redirect()->route('admin.slide.index')->with('error',  $th);
        }   

    }

    public function destroy($id)
    {
        Slide::find($id)->delete();
        return redirect()->route('admin.slide.index');
    }
    public function updateOrder(Request $request)
    {
        $sortedItems = $request->input('sortedItems');
        // Loop through the sorted items and update their order in the database
        foreach ($sortedItems as $index => $itemId) {
            $slide = Slide::find($itemId);
            $slide->order = $index + 1; // Assuming the order field is named 'order'
            $slide->save();
        }

        return response()->json(['success' => true]);
    }
}
