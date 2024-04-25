<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Slug;
use App\Models\Option;
use Throwable;

class PageController extends Controller
{
    public function index()
    {
        try {
            $pages = Page::all();
            return view('admin.page.index',compact('pages'));
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'Pages page could not be loaded.']);
        }
    }

    public function create()
    {
        try {
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.page.create',compact('languages'));
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'no_index' => 'nullable|in:on',
            'no_follow' => 'nullable|in:on',
            'media_id' => 'nullable|numeric|min:1',
        ]);
        try {
            

           Page::create([
                'media_id' => $request->media_id ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'language' => $request->language,
            ]);

            return redirect()->route('admin.page.index')->with(['type' => 'success', 'message' =>'Page Created.']);
        } catch (Throwable $th) {
            dd($th);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page could not be saved.']);
        }
    }

    public function edit(Page $page)
    {
        try {
            $languages = Option::where('key','=','language')->get();
            return view('admin.page.edit',compact('page','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'The page edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page edit page could not be loaded.']);
        }
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'media_id' => 'nullable|numeric|min:1',
        ]);
        try {
           
            $page->update([
                'media_id' => $request->media_id ?? 1,
                'title' => $request->title,
                'content' => $request->content,
                'language' => $request->language,
            ]);

            return redirect()->route('admin.page.index')->with(['type' => 'success', 'message' =>'Page Updated.']);
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page could not be updated.']);
        }
    }

    public function delete( $page)
    {
        try {
            $Pagedata = Page::where('id',$page)->first();
            $Pagedata->delete();
            return redirect()->route('admin.page.index')->with(['type' => 'success', 'message' =>'Page Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'The page could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page could not be deleted.']);
        }
    }

    public function trash()
    {
        try {
            $pages = Page::onlyTrashed()->get();
            return view('admin.page.trash',compact('pages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'Pages trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Pages trash page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Page::withTrashed()->find($id)->restore();
            return redirect()->route('admin.page.trash')->with(['type' => 'success', 'message' =>'Page Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'The page could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $page = Page::withTrashed()->find($id);
            $page->getSlug()->delete();
            $page->forceDelete();
            return redirect()->route('admin.page.trash')->with(['type' => 'error', 'message' =>'The Page Has Beed Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'The page could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The page could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Page::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'page',
                'message' => 'The page could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }
}
