<?php

use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\TotalAccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me'] );
    Route::post('resend/code', [AuthController::class,'ResendActiveCode'] );
    Route::post('active/code', [AuthController::class,'ActiveCode'] );

});


Route::group(['prefix'=>'client','middleware'=>'auth:api'],function (){
    Route::get('index',[ClientController::class,'index']);
    Route::post('store',[ClientController::class,'store']);
    Route::get('show/{id}',[ClientController::class,'show']);
    Route::put('update/{id}',[ClientController::class,'update']);
    Route::delete('destroy/{id}',[ClientController::class,'destroy']);
    Route::get('/search', [ClientController::class, 'search']);


});

Route::group(['prefix' => 'debts', 'middleware' => 'auth:api'], function () {
    Route::post('store', [DebtController::class, 'store']);// إضافة دين جديد
    Route::get('/', [DebtController::class, 'index']);//جلب قائمة الديون مع الفلاتر
    Route::get('show/{id}', [DebtController::class, 'show']);//عرض دين معيّن بالتفصيل
    Route::put('update/{id}', [DebtController::class, 'update']);//تعديل بيانات دين
    Route::delete('delete/{id}', [DebtController::class, 'destroy']);//حذف دين
    Route::get('client/{client_id}', [DebtController::class, 'byClient']);//جلب ديون زبون معيّن
});


Route::group(['prefix' => 'payments', 'middleware' => 'auth:api'], function () {
    Route::post('store', [PaymentController::class, 'store']);//إضافة دفعة
    Route::get('/', [PaymentController::class, 'index']);//جلب كل التسديدات مع فلاتر
    Route::get('client/{client_id}', [PaymentController::class, 'byClient']);//جلب تسديدات زبون معين
});



Route::group(['middleware' => 'auth:api'], function() {
    Route::get('clients/{id}/statement', [StatementController::class, 'show']);//كشف حساب زبون
    Route::get('clients/transactions/{id}', [StatementController::class, 'merged'])->middleware('auth:api');//حركات الزبون حسب التاريخ
    Route::get('transactions/all', [StatementController::class, 'allTransactions'])->middleware('auth:api');//كل العملاء حسب التاريخ مع فلترة

});


// مسارات الإشعارات
Route::group(['prefix' => 'notifications', 'middleware' => 'auth:api'], function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
    Route::get('unread-count', [NotificationController::class, 'unreadCount']);
});

// مسار ملخص الحساب
Route::get('total_account', [TotalAccountController::class, 'total_account'])->middleware('auth:api');
