
<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RazorpayPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\ArchivoController;

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

Route::get("/", [LandingController::class, "index"]);

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// authentication routes
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::post('logout', 'Auth\AuthController@logout');
Route::get('register', 'Auth\AuthController@showRegistrationForm');
Route::post('register', 'Auth\AuthController@register');

Route::get('app-setting', [AppSettingController::class, 'index']);
Route::post('update-setting', [AppSettingController::class, 'update'])->name('update-setting');

Route::get('forgot-password', 'Auth\AuthController@showForgotPasswordForm');
Route::post('forgot-password', 'Auth\AuthController@forgotPassword');
Route::get('reset-password/{user_id}/{token}', 'Auth\AuthController@showResetPasswordForm');
Route::post('reset-password', 'Auth\AuthController@resetPassword');
Route::get('change-password', 'Auth\AuthController@showChangePasswordForm');
Route::post('change-password', 'Auth\AuthController@changePassword');

Route::middleware('sentinel.auth')->group(function () {

    // manage landing
    Route::get("front-setting", [LandingController::class, 'manage'])->name('landing.manage');
    Route::get("section-enable/{id}", [LandingController::class, 'section_enable'])->name('section.enable');
    Route::get("section-disable/{id}", [LandingController::class, 'section_disable'])->name('section.disable');

    // dashboard
    Route::get('/dashboard', 'HomeController@index');

    // profile routes
    Route::get('profile-edit', 'UserController@edit');
    Route::post('profile-update', 'UserController@update');
    Route::get('profile-view', 'UserController@profile_view');

    // resource routes
    Route::get('user/profile-details', 'UserController@userProfileDetails');
    Route::resource('user', 'UserController');
    Route::resource('doctor', 'DoctorController');
    Route::resource('patient', 'PatientController');
    Route::resource('receptionist', 'ReceptionistController');
    Route::resource('accountant', 'AccountantController');
    Route::resource('appointment', 'AppointmentController');
    Route::resource('prescription', 'PrescriptionController');
    Route::resource('department', 'DepartmentController');
    Route::resource('invoice', 'InvoiceController');
    Route::get('receptionist-view/{id}', 'ReceptionistController@receptionist_view');
    Route::get('accountant-view/{id}', 'ReceptionistController@accountant_view');
    Route::get('doctor-view/{id}', 'DoctorController@doctor_view');


    // appointment routes
    Route::get('appointmentList', 'AppointmentController@appointment_list');
    Route::post('appointment-status/{id}', 'AppointmentController@appointment_status');
    Route::get('getMonthlyAppointments', 'ReportController@getMonthlyAppointments');
    Route::post('patient-by-appointment', 'InvoiceController@patient_by_appointment')->name('patient_by_appointment');
    Route::post('doctor-by-department', 'AppointmentController@doctor_by_department')->name('doctor_by_department');
    Route::post('appointment-by-doctor', 'InvoiceController@appointment_by_doctor')->name('appointment_by_doctor');
    Route::post('/doctor-by-day-time', 'AppointmentController@doctor_by_day_time')->name('doctor_by_day_time');
    Route::post('/appointment-time-by-appointment-slot', 'AppointmentController@time_by_slot')->name('timeBySlot');
    Route::get('appointment-create', 'AppointmentController@appointment_create');
    Route::post('appointment-store', 'AppointmentController@appointment_store');
    Route::get('/cal-appointment-show', 'AppointmentController@cal_appointment_show');
    Route::get('pending-appointment', 'AppointmentController@pending_appointment');
    Route::get('upcoming-appointment', 'AppointmentController@upcoming_appointment');
    Route::get('complete-appointment', 'AppointmentController@complete_appointment');
    Route::get('cancel-appointment', 'AppointmentController@cancel_appointment');
    Route::get('today-appointment', 'AppointmentController@today_appointment');
    Route::get('patient-appointment', 'AppointmentController@patient_appointment');
    Route::get('/doctor/{id}/available-days', [DoctorController::class, 'availableDays']);



    // Revenue / Earning / calender
    Route::get('getMonthlyUsersRevenue', 'ReportController@getMonthlyUsersRevenue');
    Route::get('getMonthlyInvoice', 'ReportController@getMonthlyInvoice');
    Route::get('getMonthlyEarning', 'ReportController@getMonthlyEarning');
    Route::get('calender', 'HomeController@calender');

    // Notification routes
    Route::get('notification-list', 'NotificationController@index');
    Route::get('/notification/{id}', 'NotificationController@notification');
    Route::post('/top-notification', 'NotificationController@notification_top');
    Route::get('/notification-count', 'NotificationController@notificationCount');

    // Time slot
    Route::get('/time-edit/{id}', 'DoctorController@time_edit');
    Route::post('/time-update/{id}', 'DoctorController@time_update');
    Route::get('/time-update-ajax/{id}', 'DoctorController@time_update_ajax');

    // Invoice routes
    Route::get('invoice-email/{id}', 'EmailController@invoice_email_send');
    Route::get('invoice-list', 'InvoiceController@invoice_list');
    Route::get('invoice-view/{id}', 'InvoiceController@invoice_view');
    Route::get('transaction', 'InvoiceController@transaction');

    // Prescription routes
    Route::get('prescription-email/{id}', 'EmailController@prescription_email_send');
    Route::get('prescription-list', 'PrescriptionController@prescription_list');
    Route::get('prescription-view/{id}', 'PrescriptionController@prescription_view');
    Route::delete('archivo/{id}', [ArchivoController::class, 'destroy'])->name('archivo.destroy');

    // Pagination
    Route::post('per-page-item', 'HomeController@per_page_item');


    // Razorpay Payment
    Route::post('payment-complete', [RazorpayPaymentController::class, 'payment_complete']);
    Route::post('razorpay-payment/{id}', [RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');

    //Stripe Payment
    Route::post('stripe/{id}', [StripePaymentController::class, 'store'])->name('stripe.store');
    Route::get('paymentComplete', [StripePaymentController::class, 'payment_complete']);

    // Payment Api key add
    Route::resource('payment-key', 'PaymentApiController');
});
