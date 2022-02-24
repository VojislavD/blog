<?php

use App\Http\Livewire\Show;
use App\Http\Livewire\Welcome;
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

Route::redirect('/', '/admin');
Route::redirect('/login', '/admin/login')->name('login');

// Route::get('/', Welcome::class)->name('welcome');
// Route::get('/posts/{post:slug}', Show::class)->name('posts.show');
