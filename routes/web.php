<?php

use App\Http\Controllers\Ajax;
use App\Http\Controllers\admin\Blog;
use App\Http\Controllers\admin\Faqs;
use App\Http\Controllers\admin\Index;
use App\Http\Controllers\admin\Pages;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Company;
use App\Http\Controllers\admin\Property_requests;
use App\Http\Controllers\admin\Contact;
use App\Http\Controllers\admin\Members;
use App\Http\Controllers\admin\Amenties;
use App\Http\Controllers\admin\Features;
use App\Http\Controllers\admin\Branches;
use App\Http\Controllers\admin\Partners;
use App\Http\Controllers\admin\Services;
use App\Http\Controllers\admin\Dashboard;
use App\Http\Controllers\admin\Locations;
use App\Http\Controllers\admin\Categories;
use App\Http\Controllers\admin\Sitecontent;
use App\Http\Controllers\admin\Subscribers;
use App\Http\Controllers\admin\Top_markets;
use App\Http\Controllers\admin\Testimonials;
use App\Http\Controllers\admin\Faq_categories;
use App\Http\Controllers\admin\Floor_plans;
use App\Http\Controllers\admin\ContentPages;
use App\Http\Controllers\admin\Meta_info;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*==============================API POST  Routes =====================================*/
/*==============================Ajax Routes =====================================*/
// Route::post('newsletter', [Ajax::class,'newsletter']);
Route::get('get_states/{country_id}', [Ajax::class,'get_states']);
Route::get('json_object', [Ajax::class,'json_object']);
Route::get('get_data', [Ajax::class,'get_data']);
Route::post('post_data', [Ajax::class,'post_data']);
Route::get('home_page', [ContentPages::class,'home_page']);
// Route::match(['GET','POST'], '/get_data', [Ajax::class,'get_data']);
/*==============================Admin Routes =====================================*/
Route::controller(Index::class)->group(function () {
    Route::get('/admin/register', 'register');
    Route::post('/admin/register', 'store');
});
Route::get('/admin/login', [Index::class,'admin_login'])->middleware('admin_logged_in');
Route::get('/admin/login', [Index::class,'admin_login'])->middleware('admin_logged_in');
Route::post('/admin/login', [Index::class,'login'])->middleware('admin_logged_in');
Route::get('/admin/logout', [Index::class,'logout']);
Route::get('/admin/change_language', [Index::class,'change_language']);

Route::middleware(['is_admin'])->group(function(){
    Route::get('/admin/dashboard', [Dashboard::class,'index']);
    Route::match(['GET','POST'], '/admin/change-password', [Dashboard::class,'change_password']);
    Route::get('/admin/site_settings', [Dashboard::class,'settings']);
    Route::post('/admin/settings', [Dashboard::class,'settings_update']);
    Route::get('/admin/sitecontent', [Sitecontent::class,'index']);


    /*==============================Locations Module =====================================*/
    Route::get('/admin/locations', [Locations::class,'index']);
    Route::match(['GET','POST'], '/admin/locations/add', [Locations::class,'add']);
    Route::match(['GET','POST'], '/admin/locations/edit/{id}', [Locations::class,'edit']);
    Route::match(['GET','POST'], '/admin/locations/delete/{id}', [Locations::class,'delete']);
    /*==============================Top Markets Module =====================================*/
    Route::get('/admin/top_markets', [Top_markets::class,'index']);
    Route::match(['GET','POST'], '/admin/top_markets/add', [Top_markets::class,'add']);
    Route::match(['GET','POST'], '/admin/top_markets/edit/{id}', [Top_markets::class,'edit']);
    Route::match(['GET','POST'], '/admin/top_markets/delete/{id}', [Top_markets::class,'delete']);
    /*==============================Services Module =====================================*/
    Route::get('/admin/services', [Services::class,'index']);
    Route::match(['GET','POST'], '/admin/services/add', [Services::class,'add']);
    Route::match(['GET','POST'], '/admin/services/edit/{id}', [Services::class,'edit']);
    Route::match(['GET','POST'], '/admin/services/delete/{id}', [Services::class,'delete']);
    /*==============================Testimonials Module =====================================*/
    Route::get('/admin/testimonials', [Testimonials::class,'index']);
    Route::match(['GET','POST'], '/admin/testimonials/add', [Testimonials::class,'add']);
    Route::match(['GET','POST'], '/admin/testimonials/edit/{id}', [Testimonials::class,'edit']);
    Route::match(['GET','POST'], '/admin/testimonials/delete/{id}', [Testimonials::class,'delete']);
    /*==============================Partners Module =====================================*/
    Route::get('/admin/partners', [Partners::class,'index']);
    Route::match(['GET','POST'], '/admin/partners/add', [Partners::class,'add']);
    Route::match(['GET','POST'], '/admin/partners/edit/{id}', [Partners::class,'edit']);
    Route::match(['GET','POST'], '/admin/partners/delete/{id}', [Partners::class,'delete']);
    /*==============================FAQ Categories Module =====================================*/
    Route::get('/admin/faq_categories', [Faq_categories::class,'index']);
    Route::match(['GET','POST'], '/admin/faq_categories/add', [Faq_categories::class,'add']);
    Route::match(['GET','POST'], '/admin/faq_categories/edit/{id}', [Faq_categories::class,'edit']);
    Route::match(['GET','POST'], '/admin/faq_categories/delete/{id}', [Faq_categories::class,'delete']);
    Route::match(['GET','POST'], '/admin/get-faq-sub-categories/{id}', [Faq_categories::class,'faq_sub_categories']);
    /*==============================FAQs =====================================*/
    Route::get('/admin/faqs', [Faqs::class,'index']);
    Route::match(['GET','POST'], '/admin/faqs/add', [Faqs::class,'add']);
    Route::match(['GET','POST'], '/admin/faqs/edit/{id}', [Faqs::class,'edit']);
    Route::match(['GET','POST'], '/admin/faqs/delete/{id}', [Faqs::class,'delete']);
    /*==============================BLOG Categories Module =====================================*/
    Route::get('/admin/categories', [Categories::class,'index']);
    Route::match(['GET','POST'], '/admin/categories/add', [Categories::class,'add']);
    Route::match(['GET','POST'], '/admin/categories/edit/{id}', [Categories::class,'edit']);
    Route::match(['GET','POST'], '/admin/categories/delete/{id}', [Categories::class,'delete']);
    /*==============================BLOG =====================================*/
    Route::get('/admin/blog', [Blog::class,'index']);
    Route::match(['GET','POST'], '/admin/blog/add', [Blog::class,'add']);
    Route::match(['GET','POST'], '/admin/blog/edit/{id}', [Blog::class,'edit']);
    Route::match(['GET','POST'], '/admin/blog/delete/{id}', [Blog::class,'delete']);
    /*==============================Website Textual Pages =====================================*/
    Route::match(['GET','POST'], '/admin/pages/home', [Pages::class,'home']);
    Route::match(['GET','POST'], '/admin/pages/about', [Pages::class,'about']);
    Route::match(['GET','POST'], '/admin/pages/become_a_youtuber', [Pages::class,'become_a_youtuber']);
    Route::match(['GET','POST'], '/admin/pages/help', [Pages::class,'help']);
    Route::match(['GET','POST'], '/admin/pages/contact', [Pages::class,'contact']);
    Route::match(['GET','POST'], '/admin/pages/privacy_policy', [Pages::class,'privacy_policy']);
    Route::match(['GET','POST'], '/admin/pages/terms_conditions', [Pages::class,'terms_conditions']);
    Route::match(['GET','POST'], '/admin/pages/signup', [Pages::class,'signup']);
    Route::match(['GET','POST'], '/admin/pages/signin', [Pages::class,'signin']);
    Route::match(['GET','POST'], '/admin/pages/forgot', [Pages::class,'forgot']);
    Route::match(['GET','POST'], '/admin/pages/reset', [Pages::class,'reset']);
    Route::match(['GET','POST'], '/admin/pages/search', [Pages::class,'search']);
    /*==============================Members =====================================*/
    Route::get('/admin/members', [Members::class,'index']);
    Route::match(['GET','POST'], '/admin/members/add', [Members::class,'add']);
    Route::match(['GET','POST'], '/admin/members/edit/{id}', [Members::class,'edit']);
    Route::match(['GET','POST'], '/admin/members/delete/{id}', [Members::class,'delete']);
    /*==============================Company =====================================*/
    Route::get('/admin/meta_info', [Meta_info::class,'index']);
    Route::match(['GET','POST'], '/admin/meta_info/add', [Meta_info::class,'add']);
    Route::match(['GET','POST'], '/admin/meta_info/edit/{id}', [Meta_info::class,'edit']);
    Route::match(['GET','POST'], '/admin/meta_info/delete/{id}', [Meta_info::class,'delete']);
    /*==============================Contact =====================================*/
    Route::get('/admin/contact', [Contact::class,'index']);
    Route::match(['GET','POST'], '/admin/contact/view/{id}', [Contact::class,'view']);
    Route::match(['GET','POST'], '/admin/contact/delete/{id}', [Contact::class,'delete']);
     /*==============================Property Requests =====================================*/
    Route::get('/admin/property_requests', [Property_requests::class,'index']);
    Route::match(['GET','POST'], '/admin/property_requests/approve/{id}', [Property_requests::class,'approve']);
    Route::match(['GET','POST'], '/admin/property_requests/denied/{id}', [Property_requests::class,'denied']);
    Route::match(['GET','POST'], '/admin/property_requests/cancelled/{id}', [Property_requests::class,'cancelled']);
    Route::match(['GET','POST'], '/admin/property_requests/delete/{id}', [Property_requests::class,'delete']);
    /*==============================Subscribers =====================================*/
    Route::get('/admin/subscribers', [Subscribers::class,'index']);
    Route::match(['GET','POST'], '/admin/subscribers/view/{id}', [Subscribers::class,'view']);
    Route::match(['GET','POST'], '/admin/subscribers/delete/{id}', [Subscribers::class,'delete']);

    /*==============================Property Ameneties Module =====================================*/
    Route::get('/admin/amenties', [Amenties::class,'index']);
    Route::match(['GET','POST'], '/admin/amenties/add', [Amenties::class,'add']);
    Route::match(['GET','POST'], '/admin/amenties/edit/{id}', [Amenties::class,'edit']);
    Route::match(['GET','POST'], '/admin/amenties/delete/{id}', [Amenties::class,'delete']);
    /*==============================Property Features Module =====================================*/
    Route::get('/admin/features', [Features::class,'index']);
    Route::match(['GET','POST'], '/admin/features/add', [Features::class,'add']);
    Route::match(['GET','POST'], '/admin/features/edit/{id}', [Features::class,'edit']);
    Route::match(['GET','POST'], '/admin/features/delete/{id}', [Features::class,'delete']);
    /*==============================Property Branches Module =====================================*/
    Route::get('/admin/branches', [Branches::class,'index']);
    Route::match(['GET','POST'], '/admin/branches/view/{id}', [Branches::class,'view']);
    Route::match(['GET','POST'], '/admin/branches/delete/{id}', [Branches::class,'delete']);
    /*==============================Property Floor Plans Module =====================================*/
    Route::get('/admin/floor_plans', [Floor_plans::class,'index']);
    Route::match(['GET','POST'], '/admin/floor_plans/view/{id}', [Floor_plans::class,'view']);
    Route::match(['GET','POST'], '/admin/floor_plans/delete/{id}', [Floor_plans::class,'delete']);
});

