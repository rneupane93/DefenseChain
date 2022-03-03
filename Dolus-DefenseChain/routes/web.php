<?php


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::post('/about', 'DataCenter\DolusController@AdminLogin')->name('pages.about');

Route::get('/home', 'HomeController@index')->name('home');
Route::post("/login" , 'Auth\LoginController@postLogin');
Route::post("/logout" , 'Auth\LoginController@logout')->name('logout');
Route::post("/register" , 'Auth\RegisterController@postRegister');
Route::get("/servers" , 'DataCenter\DolusController@getServerDetails')->name('pages.servers');
Route::get("/users" , 'DataCenter\DolusController@getUserDetails')->name('pages.users');
Route::get("/qvms" , 'DataCenter\DolusController@getQVMDetails')->name('pages.qvms');
Route::get("/blacklistips" , 'DataCenter\DolusController@getBacklistedIPs')->name('pages.blacklistips');
Route::get("/usermigrations" , 'DataCenter\DolusController@getUserMigrationDetails')->name('pages.usermigrations');
Route::get("/attackhistory" , 'DataCenter\DolusController@getAllAttackHistory')->name('pages.attackhistory');
Route::get("/switchdevices" , 'DataCenter\DolusController@getSwitchAndDevices')->name('pages.switchdevices');
Route::get("/policies" , 'DataCenter\DolusController@getPolicies')->name('pages.policies');
Route::get("/suspicious" , 'DataCenter\DolusController@getSuspiciousness')->name('pages.suspicious');






