<?php

use App\Http\Controllers\AttendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use Laravel\Passport\Passport;

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->name('passport.token');
Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize'])
    ->name('passport.authorizations.authorize');
Route::post('/oauth/authorize', [AuthorizationController::class, 'approve'])
    ->name('passport.authorizations.approve');
Route::delete('/oauth/authorize', [AuthorizationController::class, 'deny'])
    ->name('passport.authorizations.deny');
Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh'])
    ->name('passport.token.refresh');
Route::middleware('auth:api')->group(function () {
    Route::get('/oauth/clients', [ClientController::class, 'forUser'])
        ->name('passport.clients.index');
    Route::post('/oauth/clients', [ClientController::class, 'store'])
        ->name('passport.clients.store');
    Route::put('/oauth/clients/{client_id}', [ClientController::class, 'update'])
        ->name('passport.clients.update');
    Route::delete('/oauth/clients/{client_id}', [ClientController::class, 'destroy'])
        ->name('passport.clients.destroy');

    Route::get('/oauth/scopes', [ClientController::class, 'all'])
        ->name('passport.scopes.index');

    Route::get('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser'])
        ->name('passport.personal.tokens.index');
    Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store'])
        ->name('passport.personal.tokens.store');
    Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy'])
        ->name('passport.personal.tokens.destroy');
});



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('password/forgot-password', [ForgetPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [ResetPasswordController::class, 'passwordReset']);

Route::middleware('auth:api')->group(function ()
{
    Route::post('/logout',[AuthController::class,'logout']);

    Route::get('/userData/{id}',[UserController::class,'GetUserData']);

    Route::post('/email-verification', [EmailVerificationController::class, 'email_verification']);
    Route::get('/email-verification', [EmailVerificationController::class,'sendEmailVerification']);

    Route::get('places/AllPlaces',[PlaceController::class,'AllPlaces']);
    Route::get('places/PlaceData/{id}',[PlaceController::class,'GetPlaceData']);

    Route::post('places/like_unlike/{place_id}',[VisitController::class,'like_unlike']);
    Route::get('user/LikedPlaces',[VisitController::class,'LikedPlaces']);

    Route::post('/review/{place_id}',[ReviewController::class,'Review']);
    Route::post('/edit_review/{review_id}',[ReviewController::class,'edit_review']);
    Route::delete('/delete_review/{review_id}',[ReviewController::class,'delete_review']);
    Route::get('/view_reviews/{place_id}',[ReviewController::class,'view_reviews']);

    Route::post('/post',[PostController::class,'post']);
    Route::post('/edit_post/{post_id}',[PostController::class,'edit_post']);
    Route::delete('/delete_post/{post_id}',[PostController::class,'delete_post']);
    Route::post('/share_post/{post_id}',[PostController::class,'sharePost']);
    Route::get('/posts_timeline',[PostController::class,'view_posts']);
    Route::get('/view_a_post/{post_id}',[PostController::class,'get_post']);


    Route::post('/comment/{post_id}',[CommentController::class,'comment']);
    Route::post('/edit_comment/{comment_id}',[CommentController::class,'edit_comment']);
    Route::delete('/delete_comment/{comment_id}',[CommentController::class,'delete_comment']);
    Route::get('/view_comments/{post_id}',[CommentController::class,'view_comments']);

    Route::post('/react/{post_id}',[ReactController::class,'React']);
    Route::get('/view_reactors/{post_id}',[ReactController::class,'reactors_list']);

    Route::get('/all_experiences',[ExperienceController::class,'all_experiences']);
    Route::get('/Get_Experience_Data/{experience_id}',[ExperienceController::class,'Get_Experience_Data']);
    Route::post('/rate_experience/{experience_id}',[ViewController::class,'rate']);
    Route::post('/like_an_experience/{experience_id}',[ViewController::class,'like_an_experience']);

    Route::get('/all_guides',[GuideController::class,'AllGuides']);
    Route::get('/Get_Guide_Data/{guide_id}',[GuideController::class,'GetGuideData']);
    Route::post('/guide_rate/{guide_id}',[FeedbackController::class,'rate']);
    Route::post('/guide_like/{guide_id}',[FeedbackController::class,'like']);
    Route::get('/liked_guides',[FeedbackController::class,'LikedGuides']);
    Route::post('/guide_review/{guide_id}',[FeedbackController::class,'review']);
    Route::get('/guide_reviews/{guide_id}',[FeedbackController::class,'guide_reviews']);

    Route::get('/all_events',[EventController::class,'AllEvents']);
    Route::get('/Get_Event_Data/{id}',[EventController::class,'GetEventData']);

    Route::post('/like_event/{event_id}',[AttendController::class,'like_event']);
    Route::get('/liked_events',[AttendController::class,'liked_events']);

    Route::post('/event_booking/{event_id}',[BookController::class,'bookTicket']);

    Route::post('/chats', [ChatController::class, 'createChat']);
    Route::post('/chats/{chatId}/messages', [ChatController::class, 'sendMessage']);
    Route::get('/chats/{chatId}/messages', [ChatController::class, 'getMessages']);

});
