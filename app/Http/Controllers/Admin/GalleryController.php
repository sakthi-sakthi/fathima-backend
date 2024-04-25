<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GalleryController extends Controller
{
    public function index()
    {
        try {
            $medias = Image::orderBy('images.created_at', 'desc')->get();
        $categories = Category::where('parent','gallery')->get();
        return view("admin.gallery.index",compact('medias','categories'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'Gallery page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Gallery page could not be loaded.']);
        }
    }

    public function create()
    {
        try {
            $categories = Category::where('parent','gallery')->get();
            return view("admin.gallery.create", compact("categories"));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'The Gallery edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Gallery edit page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        try {
            if($request->document){
                foreach ($request->input('document', []) as $image) {
                    $newImage = new Image;
                    $newImage->name = $image;
                    $newImage->path = 'uploads/'.$image;
                    $newImage->category_id = $request->category_id; 
                    $newImage->title = $request->title; 
                    $newImage->save();
                }
                return redirect()->route('admin.gallery.index')->with(['type' => 'success', 'message' =>'Gallery saved.']);
            }else{
                return redirect()->back()->with(['type' => 'error', 'message' =>'The Gallery is required']);
            }
            
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'The gallery could not be saved.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The gallery could not be saved.']);
        }
    }

    public function show($id)
    {
        try {
            $media = Image::find($id);
            $categories = Category::where('parent','gallery')->get();
            return view('admin.gallery.show',compact('media','categories'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'The gallery show page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The gallery show page could not be loaded.']);
        }
    }

    public function edit($id)
    {
        try {
            $media = Image::find($id);
            $categories = Category::where('parent','gallery')->get();
            return view('admin.gallery.edit',compact('media','categories'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'The gallery edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The gallery edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $media = Image::find($id);
            $media->title = $request->title;
            $media->alt = $request->alt;
            $media->category_id = $request->category_id;
            $media->save();
            return redirect()->route('admin.gallery.index')->with(['type' => 'success', 'message' =>'Gallery is updated.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => 'Gallery could not be updated.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Gallery could not be updated.']);
        }
    }

    public function destroy($id)
    {
        try {
            $gallery = Image::find($id);
            if($gallery){
                $filepath = 'uploads/'.$gallery->name;
                unlink($filepath);
            }
            $gallery->delete();
            
            return redirect()->route('admin.gallery.index')->with(['type' => 'success', 'message' =>'Media moved to recycle bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Gallery',
                'message' => "Gallery couldn't be moved to recycle bin.",
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>"Gallery couldn't be moved to recycle bin."]);
        }
    }

    public function storeMedia(Request $request)
    {
        try {
            $file = $request->file('file');
            $path = 'uploads/';
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $filesize= $file->getSize();
            $fileSizeInMB = round($filesize / (1024*1024), 2);
            // Check if the 'uploads/' directory exists, and create it if not
            if (!file_exists($path)) {
                mkdir($path, 0755, true); // The third parameter 'true' creates nested directories if needed.
            }
            
            $file->move($path, $name);
            
            return response()->json([
                'name'          => $name,
                'original_name' => $file->getClientOriginalName(),
                'filesize' => $fileSizeInMB
            ]);
    
        } catch (Throwable $th) {
            return response()->json([
                'filesize' => 4
            ]);
        }
    }
    public function galleryfilter(Request $request){
       
        $id = $request->data;
        try {
        $medias = Image::orderBy('images.created_at', 'desc')
        ->where('category_id',$id)->get();
        $selected = $id;
        $categories = Category::where('parent','gallery')->get();
        return view("admin.gallery.index",compact('medias','categories','selected'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Gallery page could not be loaded.']);
        }

    }
}
