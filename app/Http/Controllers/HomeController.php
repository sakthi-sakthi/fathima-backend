<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Option;
use App\Models\Ourfeature;
use App\Models\Testimonial;
use App\Models\Update;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Room;
use App\Models\Socialmedia;
use App\Models\Media;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function gethomepagedetails(Request $request)
    {
        // Get parameters from the request with default values set to null
        $slideid = $request->input('slideid', null);
        $testid = $request->input('testid', null);
        $projectid = $request->input('projectid', null);

        #region Slider Data  
        $Slides = Slide::orderBy('order','asc')->get();

        $SlidesData = [];

        foreach ($Slides as $key => $slides) {
            $data = [
                'id' => $slides->id,
                'title' => $slides->title,
                'content' => $slides->content,
                'image' => asset('slideimages/' . $slides->bg),
                'date' => optional($slides->created_at)->format('d-m-Y'), // Use optional to handle potential null value
            ];
            $SlidesData[] = $data;
        }
        #endregion

        #region Newsletter Data    
        $updates = Update::select(
            'updates.title',
            'updates.file_id',
            'updates.id',
            'updates.content',
            'updates.media_id',
            'updates.created_at',
            'updates.eventdate',
            'categories.title as category_name'
        )
            ->leftJoin('categories', 'updates.category_id', '=', 'categories.id')
            ->where('updates.status', 1)
            ->orderBy('updates.created_at', 'desc') // Add this line to order by created_at in descending order
            ->limit(4)
            ->get();

        $updates->each(function ($update) {
            $mediaUrl = null;
            $update->created_date = optional($update->created_at)->format('d-m-Y');
            $update->eventdate = optional(date_create($update->eventdate))->format('d-m-Y');
            $media = Media::find($update->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $update->file_url = $update->file_id ? asset('updates/' . $update->file_id) : null;

            if ($update->media_id != 1) {
                $update->media_url = $mediaUrl;
            }
        });
        #endregion

        #region project Data 
        $resource = Room::select(
            'rooms.title',
            'rooms.file_id',
            'rooms.id',
            'rooms.content',
            'rooms.media_id',
            'rooms.eventdate',
            'rooms.created_at',
            'categories.title as category_name',
            'categories.id as category_id'
        )
            ->leftJoin('categories', 'rooms.category_id', '=', 'categories.id')
            ->where('rooms.status', 1)
            ->when($projectid, function ($query) use ($projectid) {
                $query->where('rooms.category_id', $projectid);
            })
            ->orderBy('rooms.created_at', 'asc') // Add this line to order by created_at in descending order
            ->limit(6) // Add this line to limit the result to the top four records
            ->get();

        $resource->each(function ($update) {
            $mediaUrl = null;
            $update->created_date = optional($update->created_at)->format('d-m-Y');
            $update->eventdate = optional(date_create($update->eventdate))->format('d-m-Y');
            $media = Media::find($update->media_id);
            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $update->file_url = $update->file_id ? asset('newsletter/' . $update->file_id) : null;

            if ($update->media_id != 1) {
                $update->media_url = $mediaUrl;
            }
        });

        #endregion

        #region testimonial

        $articles = Testimonial::select(
            'testimonials.title',
            'testimonials.id',
            'testimonials.content',
            'testimonials.media_id',
            'testimonials.created_at',
            'categories.title as category_name',
            'categories.content as category_description'
        )
            ->leftJoin('categories', 'testimonials.category_id', '=', 'categories.id')
            ->where('testimonials.status', 1)
            ->when($testid, function ($query) use ($testid) {
                // Only apply the where clause if $testid is not null
                $query->where('categories.id', $testid);
            })
            ->get();

        $articles->each(function ($article) {
            $mediaUrl = null;
            $media = Media::find($article->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            $article->image = $article->media_id != 1 ? $mediaUrl : null;
            $article->date = optional($article->created_at)->format('d-m-Y');
        });
        #endregion 

        #region youtube Data
        $data = Socialmedia::all();
        #endregion

        #region Allgallery Data
        $Image = Image::select('images.id', 'images.title', 'images.alt', 'images.path', 'images.created_at', 'categories.title as categoryname')->leftJoin('categories', 'categories.id', '=', 'images.category_id')
            ->orderBy('images.id', 'desc')->get();
        $imagesData = [];

        foreach ($Image as $key => $image) {
            $data = [
                'id' => $image->id,
                'title' => $image->title,
                'alt_tag' => $image->alt,
                'image' => asset($image->path),
                'date' => $image->created_at->format('d-m-Y'),
                'categoryname' => $image->categoryname,
            ];
            $imagesData[] = $data;
        }
        #endregion
        
        #region footer contact Data
        $contactpage = Option::where('key', 'contact')->first();

// dd($contactpage);
        if($contactpage != null){
            $arrayData = unserialize($contactpage->value);
            $map = $arrayData['map'];
            $zoom = $arrayData['zoom'];
            $contactdata = [
                'mobile' => $arrayData['phone'],
                'cell' => $arrayData['cell'],
                'email' => $arrayData['email'],
                'address' => $arrayData['address'],
                'googleMapsUrl' => "https://maps.google.com/maps?q=" . $map . "&t=&z=" . $zoom . "&ie=UTF8&iwloc=&output=embed"
            ];
        }
        

        #endregion

        #region Header menu Data
        $results = DB::table('main_menus')
            ->select('main_menus.id', 'main_menus.title as label', 'main_menus.link as url', 'submenus.title as submenutitle', 'submenus.link as submenuUrl', 'submenus.id as submenuid', 'main_menus.status')
            ->leftJoin('submenus', 'submenus.parent_id', 'main_menus.id')
            ->where('main_menus.status', 1)
            ->orderBy('main_menus.Position', 'asc')
            ->orderBy('submenus.Position', 'asc')
            ->whereNull('submenus.deleted_at') 
            ->whereNull('main_menus.deleted_at') 
            ->get();

        $groupedResults = collect($results)->groupBy('id');

        $finalResult = $groupedResults->map(function ($group) {
            $mainMenu = $group->first();

            $children = $group->filter(function ($item) {
                return !empty($item->submenutitle) && !empty($item->submenuUrl);
            })->map(function ($item) {
                return [
                    'id' => $item->submenuid,
                    'label' => $item->submenutitle,
                    'url' => $item->submenuUrl,
                ];
            })->values();

            return [
                'id' => $mainMenu->id,
                'label' => $mainMenu->label,
                'url' => $mainMenu->url,
                'children' => $children->isNotEmpty() ? $children : null,
            ];
        })->values();

        $response = $finalResult->toArray();

        #endregion

        $result = [
            'SlidesData' => $SlidesData,
            'newslettersdata' => $updates,
            'projectdata' => $resource,
            'testmonialdata' => $articles,
            'yotubedata' => $data,
            'allgallerydata' => $imagesData,
            'footercontactdata' => $contactdata ?? '',
            'headermenudata' => $response,
        ];

        // Use empty instead of count to check if the result is empty
        if (!empty($result)) {
            return response()->json([
                "status" => "success",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "success" => false,
                "message" => "No records found"
            ]);
        }
    }

    public function Roomfacilities(Request $request){
        $projectid = $request->input('projectid', null);

       #Room table Data 
       $resource = Room::select(
        'rooms.title',
        'rooms.file_id',
        'rooms.id',
        'rooms.content',
        'rooms.media_id',
        'rooms.eventdate',
        'rooms.created_at',
        'categories.title as category_name',
        'categories.id as category_id',
    )
        ->leftJoin('categories', 'rooms.category_id', '=', 'categories.id')
        ->where('rooms.status', 1)
        ->when($projectid, function ($query) use ($projectid) {
            $query->where('rooms.category_id', $projectid);
        })
        ->orderBy('rooms.created_at', 'asc') // Add this line to order by created_at in descending order
        ->get();

    $resource->each(function ($update) {
        $mediaUrl = null;
        $update->created_date = optional($update->created_at)->format('d-m-Y');
        $update->eventdate = optional(date_create($update->eventdate))->format('d-m-Y');
        $media = Media::find($update->media_id);
        if ($media) {
            $mediaUrl = $media->getUrl();
        }
        $update->file_url = $update->file_id ? asset('newsletter/' . $update->file_id) : null;

        if ($update->media_id != 1) {
            $update->media_url = $mediaUrl;
        }
    });

  #end Room table Data 

  #region Newsletter Data    
    $updates = Update::select(
        'updates.title',
        'updates.file_id',
        'updates.id',
        'updates.content',
        'updates.media_id',
        'updates.created_at',
        'updates.eventdate',
        'categories.title as category_name',
        'categories.id as category_id',
    )
        ->leftJoin('categories', 'updates.category_id', '=', 'categories.id')
        ->where('updates.status', 1)
        ->orderBy('updates.created_at', 'desc') // Add this line to order by created_at in descending order
        ->limit(4)
        ->get();

    $updates->each(function ($update) {
        $mediaUrl = null;
        $update->created_date = optional($update->created_at)->format('d-m-Y');
        $update->eventdate = optional(date_create($update->eventdate))->format('d-m-Y');
        $media = Media::find($update->media_id);

        if ($media) {
            $mediaUrl = $media->getUrl();
        }
        $update->file_url = $update->file_id ? asset('updates/' . $update->file_id) : null;

        if ($update->media_id != 1) {
            $update->media_url = $mediaUrl;
        }
    });

#endregion

            #services code
            $activities = Activity::select(
            'activities.title',
            'activities.id',
            'activities.content',
            'activities.media_id',
            'activities.created_at',
            'activities.amenities',
            'activities.activitydate',
            'categories.id as category_id',
            'categories.title as category_name'

            )
            ->leftJoin('categories', 'activities.category_id', '=', 'categories.id')
            ->where('activities.status', 1)
            ->orderBy('activities.created_at', 'desc')
            ->limit(4)
            ->get();

            $activities->each(function ($activity) {
            $mediaUrl = null;
            $activity->created_date = optional($activity->created_at)->format('d-m-Y');
            $activity->activitydate = optional(date_create($activity->activitydate))->format('d-m-Y');

            $media = Media::find($activity->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }

            if ($activity->media_id != 1) {
                $activity->media_url = $mediaUrl;
            }
            });
            #services end

            // #region Newsletter Data
        $ourFeatures = Ourfeature::select(
            'ourfeatures.title',
            'ourfeatures.id',
            'ourfeatures.content',
            'ourfeatures.media_id',
            'ourfeatures.created_at',
            'categories.title as category_name',
            'categories.id as category_id'
        )
            ->leftJoin('categories', 'ourfeatures.category_id', '=', 'categories.id')
            ->where('ourfeatures.status', 1)
            ->orderBy('ourfeatures.created_at', 'desc')
            ->limit(4)
            ->get();

        $ourFeatures->each(function ($ourFeature) {
            $mediaUrl = null;
            $ourFeature->created_date = optional($ourFeature->created_at)->format('d-m-Y');
            
            $media = Media::find($ourFeature->media_id);

            if ($media) {
                $mediaUrl = $media->getUrl();
            }
            
            // Remove the reference to file_id
            // $ourFeature->file_url = $ourFeature->file_id ? asset('ourfeatures/' . $ourFeature->file_id) : null;

            if ($ourFeature->media_id != 1) {
                $ourFeature->media_url = $mediaUrl;
            }
        });
   #region Allgallery Data
   $Image = Image::select('images.id', 'images.title', 'images.alt', 'images.path', 'images.created_at', 'categories.title as categoryname','categories.id as category_id')->leftJoin('categories', 'categories.id', '=', 'images.category_id')
   ->orderBy('images.id', 'desc')->get();
$imagesData = [];

foreach ($Image as $key => $image) {
   $data = [
       'id' => $image->id,
       'category_id'=> $image->category_id,
       'title' => $image->title,
       'alt_tag' => $image->alt,
       'image' => asset($image->path),
       'date' => $image->created_at->format('d-m-Y'),
       'categoryname' => $image->categoryname,
   ];
   $imagesData[] = $data;
}
#endregion
    $result = [
        'Updates' => $updates,
        'benildeused' => $resource,
        'ourFeatures' => $ourFeatures,
        'allgallerydata' => $imagesData,
        'services' => $activities ?? ''
];

    if (!empty($result)) {
        return response()->json([
            "status" => "success",
            "data" => $result
        ]);
    } else {
        return response()->json([
            "status" => "failed",
            "success" => false,
            "message" => "No records found"
        ]);
    }

    }

}
