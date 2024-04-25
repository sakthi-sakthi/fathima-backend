<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainMenu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Log;
class MainMenuController extends Controller
{
    public function index(){
        try {
            $articles = MainMenu::orderBy('Position','asc')->get();
            return view('admin.homemenu.mainmenu.index',compact('articles'));
            
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'mainmenu page could not be loaded.']);
        }

    }
    public function create()
    {
        try {
            return view('admin.homemenu.mainmenu.create');
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'The mainmenu create page could not be loaded.']);
        }
    }
    public function store(Request $request)
    {  
       
        $request->validate([
            'title' => 'required|min:3|max:255',
            'link' => 'required|min:1|max:255',
        ]);
        $type = $request->type;
      try {
         if ($type != 'update') {
            $postion = MainMenu::count()+1;
            $postionval= MainMenu::where('Position','>=',$postion)->count();
            if($postionval == 0 ){
                $request->merge([
                    'Position' => $postion
                ]);
            }
             MainMenu::create([
                'title' => $request->title,
                'link' => $request->link,
                'Position' => $request->Position ?? 0
            ]);
              return redirect()->back()->with(['type' => 'success', 'message' =>'Mainmenu Created Successfully.']);
        }else{
            $id = $request->id;
            $data = Mainmenu::where('id', $id)->first();
            $data->update([
                'title' => $request->title,
                'link' => $request->link,
            ]);
            return redirect()->back()->with(['type' => 'success', 'message' =>'Mainmenu Updated Successfully.']);
        }    
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Mainmenu could not be saved.']);
        }
    }
    public function switch(Request $request)
    {
        try {
            MainMenu::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            return $th;
        }
        return $request->status;
    }
    public function editmainmenu(Request $request){
        
        $id = $request->id;
        $data = Mainmenu::where('id',$id)->first();
        return response()->json(['status' => true,'data'=> $data]);
    }
    public function delete($id){
       
        try {
            $finddata = Submenu::where('parent_id',$id)->count();
            if ($finddata) {
                return redirect()->back()->with(['type' => 'warning', 'message' =>'Mainmenu is linked with Submenus.']);
            }else{
                $data = MainMenu::find($id);
                $data->delete();
                return redirect()->route('admin.mainmenu.index')->with(['type' => 'success', 'message' =>'Mainmenu Moved To Recycle Bin.']);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Mainmenu could not be deleted.']);
        }
    }
    public function show()
    {
        try {
            $articles = MainMenu::onlyTrashed()->get();
            return view('admin.homemenu.mainmenu.trash',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'Mainmenu trash page could not be loaded.']);
        }
    }
    public function recover($id)
    {
        try {
            MainMenu::withTrashed()->find($id)->restore();
            return redirect()->route('admin.mainmenu.trashed')->with(['type' => 'success', 'message' =>'Mainmenu Recovered.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Mainmenu could not be recovered.']);
        }
    }
    public function destroy($id)
    {
        try {
            $article = MainMenu::withTrashed()->find($id);
            $article->delete();
            $article->forceDelete();
            return redirect()->route('admin.mainmenu.trashed')->with(['type' => 'warning', 'message' =>'Mainmenu Deleted.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Mainmenu could not be destroyed.']);
        }
    }

    public function updateorder(Request $request){

        foreach ($request->Position as $key => $Position) {
            $main = MainMenu::find($Position['id'])->update(['Position' => $Position['Position']]);
            }
    
            return response()->json(['data'=> 'success']); 
    }
}
