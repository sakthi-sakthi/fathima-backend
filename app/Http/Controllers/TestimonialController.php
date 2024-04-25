<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Option;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Auth;
class TestimonialController extends Controller
{
    public function index()
    {
        try {
            $articles = Testimonial::all();
            return view('admin.testimonial.index',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'testimonial page could not be loaded.']);
        }
    }

    public function create()
    {
        try {
            $categories = Category::where('parent','testimonial')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.testimonial.create',compact('categories','languages'));
        } catch (Throwable $th) {
           return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|min:3|max:255',
            'language' => 'required',
            'media_id' => 'nullable|numeric|min:1',
            'category_id' => 'nullable|numeric|min:1',
        ]);
        try {
           Testimonial::create([
                'user_id' => Auth::id(),
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category_id ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.testimonial.index')->with(['type' => 'success', 'message' =>'Post Saved.']);
        } catch (Throwable $th) {
          
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial could not be saved.']);
        }
    }

    public function edit($id)
    {
        try {
            $article = Testimonial::where('id',$id)->first();
            $categories = Category::where('parent','testimonial')->get();
            $languages = Option::where('key','=','language')->get();
            return view('admin.testimonial.edit',compact('categories','article','languages'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'language' => 'required',
            'media_id' => 'nullable|numeric|min:1',
            'category' => 'nullable|numeric|min:1',
        ]);
        $testimonial = Testimonial::where('id',$id)->first();
        try {
            $testimonial->update([
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.testimonial.index')->with(['type' => 'success', 'message' =>'The testimonial Has Been Updated.']);
            
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial could not be updated.']);
        }
    }

    public function show($id)
    { 
       try {
            $articledata = Testimonial::where('id',$id)->first();
           $articledata->delete();
            return redirect()->route('admin.testimonial.index')->with(['type' => 'success', 'message' =>'testimonial Moved To Recycle Bin.']);
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial could not be deleted.']);
        }
    }

    public function trashed()
    {
        try {
            $articles = Testimonial::onlyTrashed()->get();
            return view('admin.testimonial.trash',compact('articles'));
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'testimonial trash page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Testimonial::withTrashed()->find($id)->restore();
            return redirect()->route('admin.testimonial.trash')->with(['type' => 'success', 'message' =>'testimonial Recovered.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $testimonial = Testimonial::withTrashed()->find($id);
            $testimonial->getSlug()->delete();
            $testimonial->forceDelete();
            return redirect()->route('admin.testimonial.trash')->with(['type' => 'warning', 'message' =>'testimonial Deleted.']);
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The testimonial could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Testimonial::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
           
        }
        return $request->status;
    }
}
