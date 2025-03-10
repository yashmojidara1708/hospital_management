<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Helpers\GlobalHelper;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Doctor\AppointmentController;

Auth::routes();
Route::any('/', [LoginController::class, 'showLoginForm'])->name('admin');
Route::any('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::any('/check/login', [LoginController::class, 'login']);
Route::any('/check/doctorlogin', [LoginController::class, 'doctorloginlogin']);
Route::any('/admin/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:staff'])->prefix('admin')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('/change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'index'])->name('admin.changePassword');
    Route::POST('/updatepassword', [App\Http\Controllers\Admin\ChangePasswordController::class, 'updatePassword'])->name('admin.updatePassword');

    Route::get('/get-states/{country_id}', function ($country_id) {
        return response()->json(GlobalHelper::getStatesByCountry($country_id));
    });

    Route::get('/get-cities/{state_id}', function ($state_id) {
        return response()->json(GlobalHelper::getCitiesByState($state_id));
    });

    Route::get('/role', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.role');
    Route::post('/rolelist', [App\Http\Controllers\Admin\RoleController::class, 'rolelist'])->name('admin.rolelist');
    Route::post('/role/save', [App\Http\Controllers\Admin\RoleController::class, 'save'])->name('save.role');
    Route::get('/role/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit.role');
    Route::post('/role/delete', [App\Http\Controllers\Admin\RoleController::class, 'delete'])->name('delete.role');
    Route::post('/role/toggle-status', [App\Http\Controllers\Admin\RoleController::class, 'toggleStatus'])->name('role.toggleStatus');
    // Specialities
    Route::get('/specialities', [App\Http\Controllers\Admin\SpecialitiesController::class, 'index'])->name('admin.specialities');
    Route::post('/specialitieslist', [App\Http\Controllers\Admin\SpecialitiesController::class, 'specialitieslist'])->name('admin.specialitieslist');
    Route::post('/specialities/save', [App\Http\Controllers\Admin\SpecialitiesController::class, 'save'])->name('save.specialities');
    Route::get('/specialities/edit', [App\Http\Controllers\Admin\SpecialitiesController::class, 'edit'])->name('edit.specialities');
    Route::post('/specialities/delete', [App\Http\Controllers\Admin\SpecialitiesController::class, 'delete'])->name('delete.specialities');
    Route::post('/specialities/toggle-status', [App\Http\Controllers\Admin\SpecialitiesController::class, 'toggleStatus'])->name('specialities.toggleStatus');

    // Patient
    Route::get('/patients', [App\Http\Controllers\Admin\PatientsController::class, 'index'])->name('admin.patients');
    Route::post('/patientslist', [App\Http\Controllers\Admin\PatientsController::class, 'patientslist'])->name('admin.patientslist');
    Route::post('/patients/save', [App\Http\Controllers\Admin\PatientsController::class, 'save'])->name('save.patients');
    Route::get('/patients/edit', [App\Http\Controllers\Admin\PatientsController::class, 'edit'])->name('edit.patients');
    Route::post('/patients/delete', [App\Http\Controllers\Admin\PatientsController::class, 'delete'])->name('delete.patients');
    Route::get('/patients/{id}', [App\Http\Controllers\Admin\PatientsController::class, 'patientDetails'])->name('patients.details');
    
    // Medicines
    Route::get('/medicines', [App\Http\Controllers\Admin\MedicinesController::class, 'index'])->name('admin.medicines');
    Route::post('/medicineslist', [App\Http\Controllers\Admin\MedicinesController::class, 'medicineslist'])->name('admin.medicineslist');
    Route::post('/medicines/save', [App\Http\Controllers\Admin\MedicinesController::class, 'save'])->name('save.medicines');
    Route::get('/medicines/edit', [App\Http\Controllers\Admin\MedicinesController::class, 'edit'])->name('edit.medicines');
    Route::post('/medicines/delete', [App\Http\Controllers\Admin\MedicinesController::class, 'delete'])->name('delete.medicines');

    // staff
    Route::get('/staff', [App\Http\Controllers\Admin\StaffController::class, 'index'])->name('admin.staff');
    Route::post('/stafflist', [App\Http\Controllers\Admin\StaffController::class, 'stafflist'])->name('admin.stafflist');
    Route::post('/staff/save', [App\Http\Controllers\Admin\StaffController::class, 'save'])->name('save.staff');
    Route::get('/staff/edit', [App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('edit.staff');
    Route::post('/staff/delete', [App\Http\Controllers\Admin\StaffController::class, 'delete'])->name('delete.staff');
   
    // Doctor
    Route::get('/doctors', [App\Http\Controllers\Admin\DoctorsController::class, 'index'])->name('admin.doctors');
    Route::post('/doctorslist', [App\Http\Controllers\Admin\DoctorsController::class, 'doctorslist'])->name('admin.doctorslist');
    Route::post('/doctors/save', [App\Http\Controllers\Admin\DoctorsController::class, 'save'])->name('save.doctors');
    Route::get('/doctors/edit', [App\Http\Controllers\Admin\DoctorsController::class, 'edit'])->name('edit.doctors');
    Route::post('/doctors/delete', [App\Http\Controllers\Admin\DoctorsController::class, 'delete'])->name('delete.doctors');
    Route::get('/doctors/{id}', [App\Http\Controllers\Admin\DoctorsController::class, 'doctorsDetails'])->name('doctors.details');

    // Appointment
    Route::get('/appointments', [App\Http\Controllers\Admin\AppointmentsController::class, 'index'])->name('admin.appointments');
    Route::post('/appointmentslist', [App\Http\Controllers\Admin\AppointmentsController::class, 'appointmentslist'])->name('admin.appointmentslist');
    Route::post('/appointments/save', [App\Http\Controllers\Admin\AppointmentsController::class, 'save'])->name('save.appointments');
    Route::get('/appointments/edit', [App\Http\Controllers\Admin\AppointmentsController::class, 'edit'])->name('edit.appointments');
    Route::post('/appointments/delete', [App\Http\Controllers\Admin\AppointmentsController::class, 'delete'])->name('delete.appointments');
    Route::post('/appointments/toggle-status', [App\Http\Controllers\Admin\AppointmentsController::class, 'toggleStatus'])->name('appointments.toggleStatus');
    Route::get('/appointments/getTimeSlots', [App\Http\Controllers\Admin\AppointmentsController::class, 'getTimeSlots'])->name('appointments.getTimeSlots');
    Route::GET('/appointments/checkAvailability', [App\Http\Controllers\Admin\AppointmentsController::class, 'checkAvailability'])->name('appointments.checkAvailability');

  //  Route::get('/doctor/changePassword',[App\Http\Controllers\Doctor\DashboardController::class, 'changepassword'])->name('doctor-change-password');
    //Route::POST('/doctor/updatePassword',[App\Http\Controllers\Doctor\DashboardController::class, 'doctorUpdatePassword'])->name('doctor-update-password');

});

Route::any('/doctor/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('doctor.logout');
Route::middleware(['auth:doctor'])->prefix('doctor')->group(function () {
    Route::GET('/home', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.home');
    Route::GET('/appointments', [App\Http\Controllers\Doctor\DashboardController::class, 'appointments'])->name('doctor.appointments');
   //change Password
    Route::GET('/changePassword',[App\Http\Controllers\Doctor\DashboardController::class, 'changepassword'])->name('doctor-change-password');
    Route::POST('/updatePassword',[App\Http\Controllers\Doctor\DashboardController::class, 'doctorUpdatePassword'])->name('doctor-update-password');

    //profile
    Route::get('/profile',[App\Http\Controllers\Doctor\ProfileController::class, 'index'])->name('doctor.profile');
});
