<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\Archive;
use App\Http\Controllers\PolicyController;

use App\Http\Controllers\ViewPDF;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth.sanctum')->group(function () {
  
});

  // Add department
  Route::post('/department', [DepartmentController::class, 'addDepartment']);

  // Retrieve department
  Route::get('/retrieve', [DepartmentController::class, 'getDepartments']);

  // Add forms
  Route::post('/form', [FormController::class, 'submitForm']);

  // Retrieve Forms per ID
  Route::get('/retrieve-forms/{data}', [FormController::class, 'retrieve_forms']);

  // Retrieve forms
  Route::get('/retrieve-forms', [FormController::class, 'getForms']);

  // Archive
  Route::put('/archive/{id}', [Archive::class, 'archiveDepartment']);
  Route::put('/archive-forms/{id}', [Archive::class, 'archiveForms']);

  // Upload Files
  Route::post('/upload', [FileController::class, 'upload']);
  Route::get('/retrieve-upload/{formId}', [FileController::class, 'retrieveUploads']);

  // View PDF
  Route::get('/get-file-content/{fileId}', [ViewPDF::class, 'getFileContent']);
  Route::get('/retrieve-policies/{polId}', [ViewPDF::class, 'getContentPolicies']);

  // Policies
  Route::post('/upload-policy', [PolicyController::class, 'upload']);
  Route::get('/retrieve-policies', [PolicyController::class, 'getPolicies']);
