<?php
    	use App\Components\Core\Utilities\MenuHelper;

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

Route::get('/','Front\\HomeController@index')->name('front.home');
Route::get('files/{id}/preview','Front\\FileController@filePreview')->name('front.file.preview');
Route::get('files/{id}/download','Front\\FileController@fileDownload')->name('front.file.download');

Auth::routes();

// admin
Route::prefix('admin')->namespace('Admin')->middleware('auth')->group(function()
{
    // single page
    Route::get('/', 'HomeController@showHome')->name('admin.home');

    // resource routes
    Route::resource('users','UserController');
    Route::resource('groups','GroupController');
    Route::resource('permissions','PermissionController');
    Route::resource('files','FileController');
    Route::resource('file-groups','FileGroupController');
    Route::resource('subjects','SubjectController');
    Route::resource('user-subjects','UserSubjectController');
    Route::resource('subject-users','subjectUsersController');
    Route::get('/me/confirm-email/{token}','UserController@verifyEmail');

});
Route::get('/mailable', function () {
    //$invoice = App\Invoice::find(1);

    return new App\Mail\NewStudent("data");
});
    Route::resource('universities','UniversityController');

// admin
Route::prefix('public')->namespace('Public')->middleware('auth')->group(function()
{
    // single page
    Route::get('/', function () {
		MenuHelper::initMenu();
    	return view('public.single-page');
    } );
    // single page
    Route::get('/me', function () {
    	return Auth::user()->load(['notifications', 'lessons.user']);
    } );
    Route::get('/me/notifications/markAsRead', function () {
    	return Auth::user()->unreadNotifications->markAsRead();
    } );



    // resource routes
    Route::resource('users','UserController');
    Route::resource('groups','GroupController');
    Route::resource('permissions','PermissionController');
    Route::resource('files','FileController');
    Route::resource('file-groups','FileGroupController');
    Route::resource('subjects','SubjectController');
    Route::resource('user-subjects','UserSubjectController');
    Route::resource('subject-users','subjectUsersController');

});