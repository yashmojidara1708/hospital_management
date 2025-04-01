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

//forgot-password
//Route::get('/forgotpassword',[App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgotpassword'])->name('admin.forgotpassword');
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

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');

    Route::post('/settings/update', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');

    // Appointment
    Route::get('/appointments', [App\Http\Controllers\Admin\AppointmentsController::class, 'index'])->name('admin.appointments');
    Route::post('/appointmentslist', [App\Http\Controllers\Admin\AppointmentsController::class, 'appointmentslist'])->name('admin.appointmentslist');
    Route::post('/appointments/save', [App\Http\Controllers\Admin\AppointmentsController::class, 'save'])->name('save.appointments');
    Route::get('/appointments/edit', [App\Http\Controllers\Admin\AppointmentsController::class, 'edit'])->name('edit.appointments');
    Route::post('/appointments/delete', [App\Http\Controllers\Admin\AppointmentsController::class, 'delete'])->name('delete.appointments');
    Route::post('/appointments/toggle-status', [App\Http\Controllers\Admin\AppointmentsController::class, 'toggleStatus'])->name('appointments.toggleStatus');
    Route::get('/appointments/getTimeSlots', [App\Http\Controllers\Admin\AppointmentsController::class, 'getTimeSlots'])->name('appointments.getTimeSlots');
    Route::GET('/appointments/checkAvailability', [App\Http\Controllers\Admin\AppointmentsController::class, 'checkAvailability'])->name('appointments.checkAvailability');


    // rooms routes
    Route::get('/rooms-category', [App\Http\Controllers\Admin\RoomCategoryController::class, 'index'])->name('admin.rooms.category');
    Route::post('/rooms-category/save', [App\Http\Controllers\Admin\RoomCategoryController::class, 'store'])->name('admin.rooms.category.save');
    Route::post('/rooms-category/list', [App\Http\Controllers\Admin\RoomCategoryController::class, 'show'])->name('admin.rooms.category.list');
    Route::get('/rooms-category/edit', [App\Http\Controllers\Admin\RoomCategoryController::class, 'edit'])->name('admin.rooms.category.edit');
    Route::post('/rooms-category/delete', [App\Http\Controllers\Admin\RoomCategoryController::class, 'delete'])->name('admin.rooms.category.delete');

    // rooms routes
    Route::get('/rooms', [App\Http\Controllers\Admin\RoomController::class, 'index'])->name('admin.rooms');
    Route::post('/rooms/save', [App\Http\Controllers\Admin\RoomController::class, 'store'])->name('admin.rooms.save');
    Route::post('/rooms/list', [App\Http\Controllers\Admin\RoomController::class, 'show'])->name('admin.rooms.list');
    Route::get('/rooms/edit', [App\Http\Controllers\Admin\RoomController::class, 'edit'])->name('admin.rooms.edit');
    Route::post('/rooms/delete', [App\Http\Controllers\Admin\RoomController::class, 'delete'])->name('admin.rooms.delete');

    // Admit Patient
    Route::get('/admit-patient', [App\Http\Controllers\Admin\AdmitPatientController::class, 'index'])->name('admin.admit-patient');
    Route::post('/admit-patient/list', [App\Http\Controllers\Admin\AdmitPatientController::class, 'list'])->name('admin.admit-patient.list');
    Route::post('/admit-patient/save', [App\Http\Controllers\Admin\AdmitPatientController::class, 'save'])->name('admin.admit-patient.save');
    Route::get('/admit-patient/edit', [App\Http\Controllers\Admin\AdmitPatientController::class, 'edit'])->name('admin.admit-patient.edit');
    Route::post('/admit-patient/delete', [App\Http\Controllers\Admin\AdmitPatientController::class, 'delete'])->name('admin.admit-patient.delete');
});

Route::any('/doctor/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('doctor.logout');
Route::middleware(['auth:doctor'])->prefix('doctor')->group(function () {
    Route::GET('/home', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.home');
    Route::GET('/appointments', [App\Http\Controllers\Doctor\DashboardController::class, 'appointments'])->name('doctor.appointments');
    Route::GET('/getAppointmentDetails', [App\Http\Controllers\Doctor\DashboardController::class, 'getAppointmentDetails'])->name('doctor.getappointmentdetails');

    //patients
    Route::GET('/patients', [App\Http\Controllers\Doctor\PatientController::class, 'patients'])->name('doctor.patients');
    Route::GET('/patientslist', [App\Http\Controllers\Doctor\PatientController::class, 'patientslist'])->name('doctor.patientslist');
    Route::GET('/patientprofile/{id}', [App\Http\Controllers\Doctor\PatientController::class, 'patientprofile'])->name('doctor.patientprofile');
    Route::GET('/patientprofile/{id}/appointments', [App\Http\Controllers\Doctor\PatientController::class, 'fetchAppointments'])->name('doctor.patient.fetchAppointments');
    Route::GET('/patientprofile/{id}/prescriptions', [App\Http\Controllers\Doctor\PatientController::class, 'fetchprescriptions'])->name('doctor.patient.fetchprescriptions');

    //prescription
    Route::get('/prescription', [App\Http\Controllers\Doctor\PrescriptionController::class, 'index'])->name('doctor.prescription');
    // Invoice
    Route::GET('/invoice/{id}', [App\Http\Controllers\Doctor\PrescriptionController::class, 'showInvoice'])
        ->name('doctor.invoice');

    //medicine
    Route::GET('/getmedicine', [App\Http\Controllers\Doctor\MedicineController::class, 'getmedicine'])->name('doctor.getmedicine');
    Route::POST('/save-prescription', [App\Http\Controllers\Doctor\MedicineController::class, 'saveprescription'])->name('doctor.save.prescription');

    //change Password
    Route::GET('/changePassword', [App\Http\Controllers\Doctor\ChangepasswordController::class, 'changepassword'])->name('doctor-change-password');
    Route::POST('/updatePassword', [App\Http\Controllers\Doctor\ChangepasswordController::class, 'doctorUpdatePassword'])->name('doctor-update-password');
    //profile
    Route::get('/profile', [App\Http\Controllers\Doctor\ProfileController::class, 'index'])->name('doctor.profile');

    Route::get('/doctor/fetch-appointments', [DashboardController::class, 'fetchUpdatedAppointments'])->name('doctor.fetchAppointments');

    // Appoinment status update route
    Route::POST('/update-appointment-status', [DashboardController::class, 'updateAppointmentStatus'])->name('doctor.updateAppointmentStatus');

    // Update all appoinment
    Route::post('/update-all-appointments-status', [DashboardController::class, 'updateAllAppointmentsStatus'])
        ->name('doctor.updateAllAppointmentsStatus');

    // Prescription Delete from doctor
    Route::post('/prescriptions/{id}/delete', [App\Http\Controllers\Doctor\PrescriptionController::class, 'destroy']);


    // prescription data fetch by id
    Route::get('/prescription/{id}/edit', [App\Http\Controllers\Doctor\PrescriptionController::class, 'edit'])
        ->name('doctor.prescription.edit');
});
