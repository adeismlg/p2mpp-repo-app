<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/program', [CourseController::class, 'index'])->name('courses.index');
Route::get('/program/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/dokumen/{document}', [DocumentController::class, 'show'])->name('documents.show');
Route::get('/dokumen/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Panel admin — dua level akses
|--------------------------------------------------------------------------
| Middleware 'auth' disediakan oleh Laravel Breeze setelah
| `php artisan breeze:install blade` dijalankan.
|
| Middleware 'staff' (App\Http\Middleware\EnsureUserIsStaff) = admin ATAU
| editor, boleh masuk panel & kelola dokumen/berita/program.
|
| Middleware 'admin' (App\Http\Middleware\EnsureUserIsAdmin) = khusus admin,
| dipakai untuk kelola kategori dokumen & manajemen staff.
|
| Daftarkan kedua alias ini di bootstrap/app.php (lihat README).
*/

Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('dokumen', AdminDocumentController::class)
        ->parameters(['dokumen' => 'document'])
        ->names('documents')
        ->except(['show']);

    Route::resource('berita', AdminNewsController::class)
        ->parameters(['berita' => 'news'])
        ->names('news')
        ->except(['show']);

    Route::resource('program', AdminCourseController::class)
        ->parameters(['program' => 'course'])
        ->names('courses')
        ->except(['show']);

    // Khusus admin: kategori dokumen & manajemen staff
    Route::middleware('admin')->group(function () {
        Route::resource('kategori', CategoryController::class)
            ->parameters(['kategori' => 'category'])
            ->names('categories')
            ->except(['show']);

        Route::get('staff', [AdminUserController::class, 'index'])->name('users.index');
        Route::put('staff/{user}', [AdminUserController::class, 'update'])->name('users.update');
    });
});

//require __DIR__.'/auth.php'; // dibuat otomatis oleh Laravel Breeze
