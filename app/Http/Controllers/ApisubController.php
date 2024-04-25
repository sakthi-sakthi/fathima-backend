<?php

namespace App\Http\Controllers;

use App\Models\Newsevent;
use App\Models\OurTeam;
use App\Models\Page;
use Illuminate\Http\Request;

class ApisubController extends Controller
{
    
    public function getteam()
    {
        $teams = OurTeam::where('status',1)->get();

        $teams->each(function ($team) {
            
            $team->media_url = $team->getMedia()->first() ? $team->getMedia()->first()->getUrl() : null;
            $team->mediathumb = $team->getMedia()->first() ? $team->getMedia()->first()->getUrl('thumb') : null;
            $team->categoryname = $team->getCategory()->first()->title;
        });
        
        return response()->json([
           'status' => 200,
            'teams' => $teams
        ]);
    }
    public function getteambyid($id){
        $teams = OurTeam::where('category_id', $id)->get();

        $teams->each(function ($team) {
            
            $team->media_url = $team->getMedia()->first() ? $team->getMedia()->first()->getUrl() : null;
            $team->mediathumb = $team->getMedia()->first() ? $team->getMedia()->first()->getUrl('thumb') : null;
            $team->categoryname = $team->getCategory()->first()->title;
        });
        
        return response()->json([
            'status' => 200,
             'teams' => $teams
         ]);
    }
    public function getallpages(Request $request){

        $pages = Page::where('status',1)->get();
        $pages->each(function ($page) {
            $page->media_url = $page->getMedia()->first() ? $page->getMedia()->first()->getUrl() : null;
            $page->mediathumb = $page->getMedia()->first() ? $page->getMedia()->first()->getUrl('thumb') : null;
        });
        return response()->json([
            'status' => 200,
            'pages' => $pages
        ]); 
    }
    public function getpagebyid($id){
        $pages = Page::where('id', $id)->get();
        $pages->each(function ($page) {
            $page->media_url = $page->getMedia()->first() ? $page->getMedia()->first()->getUrl() : null;
            $page->mediathumb = $page->getMedia()->first() ? $page->getMedia()->first()->getUrl('thumb') : null;
        });
        return response()->json([
            'status' => 200,
            'pages' => $pages
        ]); 
    }
    public function getnewsevents(Request $request){
        $newsevents = Newsevent::where('status',1)->get();
        $newsevents->each(function ($newsevent) {
            $newsevent->media_url = $newsevent->getMedia()->first() ? $newsevent->getMedia()->first()->getUrl() : null;
            $newsevent->mediathumb = $newsevent->getMedia()->first() ? $newsevent->getMedia()->first()->getUrl('thumb') : null;
            $newsevent->category_name = $newsevent->getCategory()->first() ? $newsevent->getCategory()->first()->title : null;
            $newsevent->category_id = $newsevent->getCategory()->first() ? $newsevent->getCategory()->first()->id : null;
            $newsevent->created_date = optional($newsevent->created_at)->format('d-m-Y');
            $newsevent->eventdate = optional(date_create($newsevent->eventdate))->format('d-m-Y');
            $newsevent->file_url = $newsevent->file_id ? asset('UpcomingNews/' . $newsevent->file_id) : null;
            
        });
        return response()->json([
            'status' => 200,
            'newsevents' => $newsevents
        ]);
    }
}
