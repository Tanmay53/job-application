<?php

use App\Http\Controllers\CandidateController;
use App\Models\Candidate;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [CandidateController::class, 'create'])->name('jobApplication');
Route::post('/submit', [CandidateController::class, 'store'])->name('saveCandidate');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::middleware(['can:list candidates'])->get('/dashboard', [CandidateController::class, 'index'])->name('dashboard');

    Route::middleware(['can:list candidates'])->get('/candidate/profile/{candidate}', [CandidateController::class, 'profile'])->name('candidatProfile');

    Route::middleware(['can:delete candidates'])->post('/candidate/delete/{candidate}', [CandidateController::class, 'destroy'])->name('deleteCandidate');
});
