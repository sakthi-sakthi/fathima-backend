<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Log;
use App\Models\Option;
use App\Models\Article;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        
        try {
            $segment = request()->segment(3); 
            $categories = Category::where('parent' ,$segment)->get();
            return view("admin.category.index", compact('categories'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'Categories page could not be loaded.']);
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            return view("admin.category.create", compact('categories'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'category',
                'message' => 'The category create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' => 'The category create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'type' => 'required',
        ]);
        try {
            Category::create([
                'title' => $request->title,
                'media_id' => $request->media_id ?? 1,
                'parent' => $request->parent,
                'content' => $request->content,
                'type' => $request->type,
            ]);
            return redirect()->away(url('admin/category/'.$request->parent))->with(['type' => 'success', 'message' => 'Category Saved.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'The category could not be saved.']);
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.category.edit', compact('category'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'The category edit page could not be loaded.']);
        }
    }

    public function update(Request $request, Category $category)
    {

        
        $request->validate([
            'title' => 'required|min:3|max:255',
            'media_id' => 'nullable|numeric|min:1',
            'type' => 'required',
        ]);
        try {

            $category->update([
                'title' => $request->title,
                'media_id' => $request->media_id ?? 1,
                'parent' => $request->parent,
                'content' => $request->content,
                'type' => $request->type,
            ]);

            return redirect()->away(url('admin/category/'.$request->parent))->with(['type' => 'success', 'message' => 'Category Saved.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'The category could not be updated.']);
        }
    }

    public function destroy($id)
    {
        
        try {
            $data = Article::where('category_id',$id)->first();
            $letterdata =Update::where('category_id',$id)->first();
            
            if($data || $letterdata) {
                return redirect()->route('admin.category.index')->with(['type' => 'error', 'message' => 'Cannot delete the category. It has posts associated with it..']);
         }else{
           
                $category = Category::findOrFail($id);
                $category->getSlug()->forceDelete();
                $category->delete();
            }
            return redirect()->route('admin.category.index')->with(['type' => 'success', 'message' => 'Category Deleted.']);
        } catch (Throwable $th) {
           
            Log::create([
                'model' => 'category',
                'message' => 'The category could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' => 'The category could not be destroyed.']);
        }
    }
}
