<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

/*
| Route 'dashboard' ini WAJIB ada meskipun tidak dipakai langsung di
| navigasi kita sendiri — Breeze & Fortify menunjuk ke sini secara
| default (navigasi Breeze, redirect setelah login berhasil, dan
| middleware RedirectIfAuthenticated). Kita jadikan cuma pengalih:
| staff/admin diarahkan ke panel admin, user biasa ke beranda.
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user && in_array($user->role, ['admin', 'editor'], true)) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/pelatihan', [CourseController::class, 'index'])->name('courses.index');
Route::get('/pelatihan/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/dokumen/{document}', [DocumentController::class, 'show'])->name('documents.show');
Route::get('/dokumen/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

Route::get('/halaman/{page}', [PageController::class, 'show'])->name('pages.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Keep the Breeze endpoint available while Fortify uses /user/password.
    Route::put('/password', [\Laravel\Fortify\Http\Controllers\PasswordController::class, 'update']);
});

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

    Route::resource('halaman', AdminPageController::class)
        ->parameters(['halaman' => 'page'])
        ->names('pages')
        ->except(['show']);

    // Khusus admin: kategori dokumen, manajemen staff, & struktur menu
    Route::middleware('admin')->group(function () {
        Route::resource('kategori', CategoryController::class)
            ->parameters(['kategori' => 'category'])
            ->names('categories')
            ->except(['show']);

        Route::get('staff', [AdminUserController::class, 'index'])->name('users.index');
        Route::put('staff/{user}', [AdminUserController::class, 'update'])->name('users.update');

        Route::resource('menu', AdminMenuController::class)->except(['show']);
        Route::put('menu/{menu}/naik', [AdminMenuController::class, 'moveUp'])->name('menu.up');
        Route::put('menu/{menu}/turun', [AdminMenuController::class, 'moveDown'])->name('menu.down');
    });
});

//require __DIR__.'/auth.php'; // dibuat otomatis oleh Laravel Breeze
