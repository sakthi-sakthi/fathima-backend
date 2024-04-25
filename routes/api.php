<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApisubController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/store/contactform', [ApiController::class, 'storecontactform' ]); 
Route::get('/get/post/{id}',[ApiController::class,'getpostdata']);
Route::get('/get/slider/{id}',[ApiController::class,'getsliderimages']);
Route::get('/get/Newsletter',[ApiController::class,'getnewsletter']);
Route::get('/get/Pages/{id}',[ApiController::class,'getpage']);
Route::get('/get/Pages',[ApisubController::class,'getallpages']);
Route::get('/get/slidebar',[ApiController::class,'getslidebar']);
Route::get('get/gallery_images',[ApiController::class,'getGalleryimages']);
Route::get('get/teammembers',[ApiController::class,'getteam']);
Route::get('get/team/{id}',[ApiController::class,'getteammembers']);
Route::get('news/category/{id}', [ApiController::class, 'getresourcedata']);
Route::get('youtube/vedios', [ApiController::class, 'getyoutubedata']);
Route::get('get/contactDetails',[ApiController::class,'getcontactpage']);
Route::get('/get/Activity/list/{id}',[ApiController::class,'getactivitylist']);
Route::get('/get/menus',[ApiController::class,'getmenus']);
Route::get('/get/testimonial/{id}',[ApiController::class,'gettestimonialdata']);
Route::get('/get/messages/{id}',[ApiController::class,'getmessageslist']);
Route::get('/get/homepagee/sections',[HomeController::class,'gethomepagedetails']);
Route::get('get/teammembers',[ApisubController::class,'getteam']);
Route::get('get/team/{id}',[ApisubController::class,'getteambyid']);
// Booking room
Route::get('/booking/room', [HomeController::class, 'Roomfacilities']);