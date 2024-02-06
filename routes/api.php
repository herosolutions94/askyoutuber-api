<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Ajax;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentPages;
use App\Http\Controllers\Account;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
/*==============================API POST Routes =====================================*/

Route::post('/get_data', [App\Http\Controllers\Ajax::class, 'get_data']);
Route::post('/newsletter', [App\Http\Controllers\Ajax::class, 'newsletter']);
Route::post('/contact-us', [App\Http\Controllers\Ajax::class, 'contact_us']);
Route::post('/signup', [App\Http\Controllers\Ajax::class, 'signup']);
Route::post('/login', [App\Http\Controllers\Ajax::class, 'login']);
Route::post('/forgot-password', [App\Http\Controllers\Ajax::class, 'forget_password']);
Route::post('/reset-password/{token}', [App\Http\Controllers\Ajax::class, 'reset_password']);

Route::post('/save-image', [App\Http\Controllers\Ajax::class, 'save_image']);
Route::post('/upload-image', [App\Http\Controllers\Ajax::class, 'upload_image']);
Route::post('/upload-file', [App\Http\Controllers\Ajax::class, 'upload_file']);



/*==============================API GET Routes =====================================*/
Route::match(['GET','POST'], '/site-settings', [ContentPages::class,'website_settings']);
Route::post('home-content', [ContentPages::class,'home_page']);
Route::post('about-content', [ContentPages::class,'about_page']);
Route::post('become-youtuber-content', [ContentPages::class,'become_a_youtuber']);
Route::post('help-content', [ContentPages::class,'help']);
Route::post('help-category', [ContentPages::class,'help_category']);
Route::post('help-question-details', [ContentPages::class,'help_question_details']);
Route::post('login-content', [ContentPages::class,'login_page']);
Route::post('signup-content', [ContentPages::class,'signup_page']);
Route::post('forgot-password-content', [ContentPages::class,'forgot_page']);
Route::post('reset-password-content', [ContentPages::class,'reset_page']);
Route::post('contact-content', [ContentPages::class,'contact_page']);
Route::post('terms-conditions-content', [ContentPages::class,'terms_conditions_page']);
Route::post('privacy-policy-content', [ContentPages::class,'privacy_policy_page']);


/*==============================API Post Routes =====================================*/
Route::post('save-contact-message', [Ajax::class,'contact_us']);
Route::post('save-newsletter-message', [Ajax::class,'save_newsletter']);

/*==============================API Post Routes =====================================*/
Route::post('save-signup', [Ajax::class,'signup']);
Route::post('save-login', [Ajax::class,'login']);
/*==============================API Youtuber Routes =====================================*/
Route::match(['GET','POST'], '/user/dashboard', [Account::class,'dashboard']);
Route::match(['GET','POST'], '/user/profile', [Account::class,'profile']);
Route::match(['GET','POST'], '/user/resend-otp-code', [Account::class,'resend_otp_code']);
Route::match(['GET','POST'], '/user/verify-otp', [Account::class,'verify_otp_code']);
Route::match(['GET','POST'], '/user/update-profile', [Account::class,'update_profile']);
Route::match(['GET','POST'], '/user/update-password', [Account::class,'update_password']);
Route::match(['GET','POST'], '/user/become-youtuber-request', [Account::class,'become_youtuber_request']);

