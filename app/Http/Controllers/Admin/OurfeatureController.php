<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ourfeature;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Log;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OurfeatureController extends Controller
{
    public function index()
    {
        try {
            $articles = Ourfeature::all();
            return view('admin.ourfeature.index',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature page could not be loaded.']);
        }
    }

    public function create()
    { 
        try {
            $categories = Category::where('parent' ,'features')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.ourfeature.create',compact('categories','languages'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        
        try {
            Ourfeature::create([
                'user_id' => Auth::id(),
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category_id ?? 1,
                'title' => $request->title,
                'email' =>$request->email,
                'phone' => $request->phone,
                'content' => $request->content,
                'language' => $request->language,
            ]);
            return redirect()->route('admin.ourfeature.index')->with(['type' => 'success', 'message' =>'Team Meamber Saved.']);

        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Team Meamber could not be saved.']);
        }
    }

    public function edit($ourfeature)
    { 
        try {
            $categories = Category::where('parent' ,'features')->get();
            $languages = Option::where('key','=','language')->get();
            $value =Ourfeature::where('id',$ourfeature)->first();
            return view('admin.ourfeature.edit',compact('categories','value','languages'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        
        $article =Ourfeature::where('id' ,$id)->first();
        $request->validate([
            'title' => 'required|max:255',
            'language' => 'required',
            'media_id' => 'nullable|numeric|min:1',
            'category_id' => 'nullable|numeric|min:1',
        ]);
        try {
                $article->update([
                    'media_id' => $request->media_id ?? 1,
                    'category_id' => $request->category_id ?? 1,
                    'title' => $request->title,
                    'email' =>$request->email,
                    'phone' => $request->phone,
                    'content' => $request->content,
                    'language' => $request->language,
                ]);
          
            return redirect()->route('admin.ourfeature.index')->with(['type' => 'success', 'message' =>'Our feature Has Been Updated.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature could not be updated.']);
        }
    }

    public function delete($article)
    {
        try {
            $articledata = Ourfeature::where('id',$article)->first();
            $articledata->delete();
            return redirect()->route('admin.ourfeature.index')->with(['type' => 'success', 'message' =>'Our feature To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Our feature could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature could not be deleted.']);
        }
    }

    public function trash()
    {
        try {
            $articles = Ourfeature::onlyTrashed()->get();
            return view('admin.ourfeature.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Our feature page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Ourfeature::withTrashed()->find($id)->restore();
            return redirect()->route('admin.ourfeature.trash')->with(['type' => 'success', 'message' =>'Our feature Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'TeamMember',
                'message' => 'Our feature could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Ourfeature::withTrashed()->find($id);
            $article->getSlug()->delete();
            $article->forceDelete();
            $filepath = 'newletter/'.$article->file_id;
            unlink($filepath);
            return redirect()->route('admin.ourfeature.trash')->with(['type' => 'warning', 'message' =>'Our feature Deleted.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Our feature could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Ourfeature::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {   
            return 'not found';
        }
        return $request->status;
    }
}
