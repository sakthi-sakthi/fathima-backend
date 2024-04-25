<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainMenu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Throwable;
class SubmenuController extends Controller
{
    public function index(){
        try {
            $articles = Submenu::select('submenus.*','main_menus.title as mainmenuname')
            ->leftJoin('main_menus','main_menus.id','submenus.parent_id')
            ->orderBy('Position','asc')->get();
            return view('admin.homemenu.submenu.index',compact('articles'));
            
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'Submenu page could not be loaded.']);
        }

    }
    public function create()
    {
        try {
            $parentdata = MainMenu::all();
            return view('admin.homemenu.submenu.form', compact('parentdata'));
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu create page could not be loaded.']);
        }
    }
    public function store(Request $request)
    {  
       
        $request->validate([
            'title' => 'required|min:3|max:255',
            'link' => 'required|min:1|max:255',
            'parent_id' =>'required',
        ]);
      try {
           $postion = Submenu::count()+1;
            $postionval= Submenu::where('Position','>=',$postion)->count();
            if($postionval == 0 ){
                $request->merge([
                    'Position' => $postion
              ]);
            }
             Submenu::create([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
                'Position' => $request->Position ?? 0

            ]);
              return redirect()->back()->with(['type' => 'success', 'message' =>'Submenu Created Successfully.']);
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu could not be saved.']);
        }
    }

    public function edit($id)
    {
        try {
            $parentdata = MainMenu::all();
            $editsubmenu = Submenu::where('id','=',$id)->first();
            return view('admin.homemenu.submenu.form',compact('parentdata','editsubmenu'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu edit page could not be loaded.']);
        }
    }


    public function update(Request $request , $id){
         try {
            $data = Submenu::where('id', $id)->first();
            $data->update([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
            ]);
            return redirect()->route('admin.submenu.index')->with(['type' => 'success', 'message' =>'Submenu Updated Successfully.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu could not be Updated.']);
        }
    }
    public function switch(Request $request)
    {
        try {
            Submenu::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            return $th;
        }
        return $request->status;
    }
    public function editmainmenu(Request $request){
        
        $id = $request->id;
        $data = Submenu::where('id',$id)->first();
        return response()->json(['status' => true,'data'=> $data]);
    }
    public function delete($id){
       
        try {
            $data = Submenu::find($id);
            $data->delete();
            return redirect()->route('admin.submenu.index')->with(['type' => 'success', 'message' =>'Submenu Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu could not be deleted.']);
        }
    }
    public function show()
    {
        try {
            $articles = Submenu::select('submenus.*','main_menus.title as mainmenuname')
            ->leftJoin('main_menus','main_menus.id','submenus.parent_id')
            ->onlyTrashed()
            ->orderBy('Position','asc')->get();
            // $articles = Submenu::onlyTrashed()->get();
            return view('admin.homemenu.submenu.trash',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Submenu trash page could not be loaded.']);
        }
    }
    public function recover($id)
    {
        try {
            Submenu::withTrashed()->find($id)->restore();
            return redirect()->route('admin.submenu.trashed')->with(['type' => 'success', 'message' =>'Submenu Recovered.']);
        } catch (Throwable $th) {

            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu could not be recovered.']);
        }
    }
    public function destroy($id)
    {
        try {
            $article = Submenu::withTrashed()->find($id);
            $article->delete();
            $article->forceDelete();
            return redirect()->route('admin.submenu.trashed')->with(['type' => 'warning', 'message' =>'Submenu Deleted.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Submenu could not be destroyed.']);
        }
    }

    public function updateorder(Request $request){

        foreach ($request->Position as $key => $Position) {
            $main = Submenu::find($Position['id'])->update(['Position' => $Position['Position']]);
            }
    
            return response()->json(['data'=> 'success']); 
    }
}
