<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/history/{param}', 'HistoryTransaksiController@getHistory');


Route::any('/income/all', 'KasMasukController@getAll');
Route::post('/income/store', 'KasMasukController@store');
Route::delete('/income/{id}/delete', 'KasMasukController@delete');
Route::get('/income/{id}/detail', 'KasMasukController@detail');

Route::any('/hutang/all', 'HutangController@getAll');
Route::post('/hutang/store', 'HutangController@store');
Route::delete('/hutang/{id}/delete', 'HutangController@delete');
Route::get('/hutang/{id}/detail', 'HutangController@detail');


Route::any('/piutang/all', 'PiutangController@getAll');
Route::post('/piutang/store', 'PiutangController@store');
Route::delete('/piutang/{id}/delete', 'PiutangController@delete');
Route::get('/piutang/{id}/detail', 'PiutangController@detail');

Route::any('/outcome/all', 'KasKeluarController@getAll');
Route::post('/outcome/store', 'KasKeluarController@store');
Route::delete('/outcome/{id}/delete', 'KasKeluarController@delete');
Route::get('/outcome/{id}/detail', 'KasKeluarController@detail');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
