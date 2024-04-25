<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Article;
use App\Models\Log;
use App\Models\Option;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Throwable;

class MessageController extends Controller
{
    public function index()
    {
        try {
            $articles = Message::all();
            return view('admin.message.index',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Projects page could not be loaded.']);
        }
    }

    public function create()
    { 
        try {
            $categories = Category::where('parent' ,'message')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.message.create',compact('categories','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'media_id' => 'nullable|numeric|min:1',
            'category' => 'nullable|numeric|min:1',
        ], [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least :min characters.',
            'title.max' => 'Title cannot be longer than :max characters.',
            'media_id.numeric' => 'Media ID must be a number.',
            'media_id.min' => 'Media ID must be at least :min.',
            'category.numeric' => 'Category must be a number.',
            'category.min' => 'Category must be at least :min.'
        ]);
        try {

            $message = new Message();
            if ($request->hasFile('file_id')) {
                $filename = $request->file('file_id')->getClientOriginalName();
                $request->file('file_id')->move('messages', $filename);
                $message->file_id = $filename;
            }
            $message->title = $request->title;
            $message->media_id = $request->media_id ?? 1;
            $message->category_id = $request->category;
            $message->content = $request->content;
            $message->status = 1;
            $message->save();
            return redirect()->route('admin.message.index')->with(['type' => 'success', 'message' =>'Projects Saved.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects could not be saved.']);
        }
    }

    public function edit($newsletter)
    { 
        try {
            $categories = Category::where('parent' ,'message')->get();
            $languages = Option::where('key','=','language')->get();
            $value =Message::where('id',$newsletter)->first();
            return view('admin.message.edit',compact('categories','value','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'title' => 'required|min:3|max:255',
            'media_id' => 'nullable|numeric|min:1',
            'category' => 'nullable|numeric|min:1',
        ], [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least :min characters.',
            'title.max' => 'Title cannot be longer than :max characters.',
            'media_id.numeric' => 'Media ID must be a number.',
            'media_id.min' => 'Media ID must be at least :min.',
            'category.numeric' => 'Category must be a number.',
            'category.min' => 'Category must be at least :min.'
        ]);

        $article =Message::find($id);
       
        try {
            $file = $request->hasFile('file_id');
           
            $article->update([
                'media_id' => $request->media_id ?? 1,
                'category_id' => $request->category ?? 1,
                'title' => $request->title,
                'content' => $request->content,
            ]);

            if ($file) {
                $filename = $request->file('file_id')->getClientOriginalName();
                $request->file('file_id')->move('messages', $filename);
                $article->file_id = $filename;
                $article->save();
            }
            return redirect()->route('admin.message.index')->with(['type' => 'success', 'message' =>'The Projects Has Been Updated.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects could not be updated.']);
        }
    }

    public function delete($article)
    {
        try {
            $articledata = Message::where('id',$article)->first();
            $articledata->delete();
            return redirect()->route('admin.message.index')->with(['type' => 'success', 'message' =>'Projects Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects could not be deleted.']);
        }
    }

    public function gettrash()
    {  
       
        try {
            $articles = Message::onlyTrashed()->get();
            return view('admin.message.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'Projects trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Projects trash page could not be loaded.']);
        }
    }
    public function recover($id)
    {
        try {
            Message::withTrashed()->find($id)->restore();
            return redirect()->route('admin.message.trash')->with(['type' => 'success', 'message' =>'Projects Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects could not be recovered.']);
        }
    }

    public function destroy($id)
    {
       
        try {
            $article = Message::withTrashed()->find($id);
            $article->delete();
            $article->forceDelete();
            $filename = $article->file_id;
            if ($filename) {
                $filepath = 'songs/'.$article->file_id;
                unlink($filepath);
            }else{

            }
            return redirect()->route('admin.message.trash')->with(['type' => 'warning', 'message' =>'Projects Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Projects could not be destroyed.']);
        }
    }

    public function show(Request $request)
    {
        try {
            Message::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Projects',
                'message' => 'The Projects could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }
}
