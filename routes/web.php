<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\EbookRoyaltyController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\GeneratePdfController;

use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\GenReportController;
use App\Http\Controllers\PodTransactionController;
use App\Http\Controllers\PodTransactionControllerBETA;
use App\Http\Controllers\RejectedEbookTransactionController;
use App\Http\Controllers\RejectedPodTransactionController;
use App\Http\Controllers\RejectedPodTransactionControllerBETA;
use App\Http\Controllers\RoyaltyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\manualController;
use App\Http\Controllers\UserinfoController;
use App\Http\Controllers\GenPdfController;
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

Route::middleware(['guest'])->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return redirect(route('dashboard'));
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'index')->name('user.profile');
        Route::get('/profile/edit', 'edit')->name('user.edit');
        Route::post('/profile', 'updateProfile')->name('user.update-profile');
        Route::get('/profile/change-password', 'editPassword')->name('user.edit-password');
        Route::post('/profile/change-password', 'updatePassword')->name('user.update-password');
    });

    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/searchISBN', 'testdash')->name('dash.test');
        Route::post('/logout', 'logout')->name('logout');
    });
    Route::controller(manualController::class)->group(function () {
        Route::get('/manual', 'viewManual')->name('manual.view');
        Route::get('/version', 'viewVersion')->name('version');
       
        
    });
    Route::controller(AuthorController::class)->group(function () {
        Route::get('/authors', 'index')->name('author.index');
       
        Route::get('/authors/search', 'search')->name('author.search');
        Route::get('/authors/search/user', 'finduser')->name('author.search-user');
        Route::get('/authors/import', 'importPage')->name('author.import-page');
        Route::post('/authors/import', 'import')->name('author.import-bulk');
        Route::get('/authors/create', 'create')->name('author.create');
        Route::get('/authors/{author}', 'edit')->name('author.edit');
        Route::post('/authors/create', 'store')->name('author.store');
        Route::put('/authors/{author}', 'update')->name('author.update');
        Route::delete('/authors/{author}', 'delete')->name('author.delete');
        Route::delete('/authors/clearAll', 'clear')->name('author.clear');
    });

    Route::controller(BookController::class)->group(function () {
        Route::get('/books', 'index')->name('book.index');
        Route::get('/books/search', 'search')->name('book.search');
        Route::get('/books/search/author', 'searchAuthor')->name('book.getauthor');
        Route::get('/books/import', 'importPage')->name('book.import-page');
        Route::post('/books/import', 'import')->name('book.import-bulk');
        Route::get('/books/create', 'create')->name('book.create');
        Route::get('/books/{book}', 'edit')->name('book.edit');
        Route::post('/books/create', 'store')->name('book.store');
        Route::put('/books/{book}', 'update')->name('book.update');
        Route::delete('/books/{book}', 'delete')->name('book.delete');
    });
    Route::controller(RoyaltyController::class)->group(function () {
        Route::get('/royalties', 'index')->name('royalty.index');
        Route::get('/royalties/author', 'search')->name('royalty.search');
        Route::get('/royalties/sort', 'sort')->name('royalty.sort');
        Route::get('/royalties/filter', 'yearfil')->name('royalty.filter');
          
      
    });
    Route::controller(EbookRoyaltyController::class)->group(function () {
        Route::get('/EbookRoyalties', 'index')->name('er.index'); 
        Route::get('/EbookRoyalties/author', 'search')->name('er.search');
        Route::get('/EbookRoyalties/sort', 'sort')->name('er.sort');
        Route::get('/EbookRoyalties/filter', 'filter')->name('er.filter'); 
    });
   Route::controller(UserinfoController::class)->group(function () {
       Route::get('/usrinfo', 'index')->name('userinfo.index'); 
        Route::get('/usrinfo/user', 'searchUser')->name('userinfo.getuser');
        Route::get('/usrinfo/dept', 'getDept')->name('userinfo.filterdept');
        Route::get('/usrinfo/sort', 'sort')->name('usrinfo.sort');
        Route::post('/usrinfo/import', 'import')->name('usrinfo.import');
        Route::get('/usrinfo/register', 'create')->name('usrinfo.register');
        Route::post('/usrinfo/save', 'store')->name('usrinfo.store');
        Route::get('/usrinfo/import', 'importPage')->name('usrinfo.import-page');
         Route::get('/usrinfo/{users}', 'edit')->name('usrinfo.edit');
         Route::put('/usrinfo/{users}', 'update')->name('usrinfo.update');
         Route::get('/userinfo/{users}', 'delete')->name('usrinfo.delete');
        Route::get('/usrinfo/sortdata', 'sort')->name('usrinfo.filter'); 
    });
    

    Route::controller(PodTransactionController::class)->prefix('pod')->group(function () {
        Route::get('/', 'index')->name('pod.index');
        Route::get('/search/books', 'searchBooks')->name('pod.search');
        //for next shift work
        Route::get('/search/author', 'searchAuthor')->name('pod.search-author');
        Route::get('/filter/status', 'sortStatus')->name('pod.sort-status');
       Route::get('/filter/month', 'sortMonth')->name('pod.sort-month');
       // Route::get('/sort/status', 'sortStatus')->name('pod.sort-month');
        //
        Route::get('/filter/year', 'sort')->name('pod.sort');
        Route::get('/import', 'importPage')->name('pod.import-page');
        Route::post('/import', 'import')->name('pod.import-bulk');
        Route::get('/create', 'create')->name('pod.create');
        Route::post('/create', 'store')->name('pod.store');
        Route::get('/{pod}/edit', 'edit')->name('pod.edit');
        Route::put('/{pod}', 'update')->name('pod.update');
        Route::get('/{pod}', 'delete')->name('pod.delete');
        Route::get('/delete/all', 'clear')->name('pod.clear');
    });

    Route::controller(EbookController::class)->prefix('ebook')->group(function () {
        Route::get('/', 'index')->name('ebook.index');
        Route::get('/search', 'search')->name('ebook.search');
        //for next shift work
        Route::get('/search/books', 'searchbook')->name('ebook.search-book');
        //next shift work
        Route::get('/create', 'create')->name('ebook.create');
        Route::get('/import', 'importPage')->name('ebook.import-page');
        Route::post('/import', 'import')->name('ebook.import-bulk');
        Route::post('/create', 'store')->name('ebook.store');
        Route::get('/{ebook}/edit', 'edit')->name('ebook.edit');
        Route::put('/{ebook}', 'update')->name('ebook.update');
        Route::get('/{ebook}', 'delete')->name('ebook.delete');
        Route::get('/delete/all', 'clear')->name('ebook.clear');
        Route::get('/filter/year', 'year')->name('ebook.year');
        Route::get('/search/month', 'month')->name('ebook.month');
    });

    Route::prefix('rejecteds')->group(function () {
        Route::controller(RejectedPodTransactionController::class)->prefix('pods')->group(function () {
            Route::get('/', 'index')->name('rejecteds-pods.index');
            Route::get('/{rejected_pod}/edit', 'edit')->name('rejecteds-pods.edit');
            Route::put('/{rejected_pod}', 'update')->name('rejecteds-pods.update');
            Route::get('/{rejected_pod}/delete', 'delete')->name('rejecteds-pods.delete');
            Route::get('/delete', 'clear')->name('all-rejecteds-pods.clear');
            Route::get('/search', 'filterByyear')->name('find-rejecteds-pods.filter');
        });

        Route::controller(RejectedEbookTransactionController::class)->prefix('ebooks')->group(function () {
            Route::get('/', 'index')->name('rejecteds-ebooks.index');
            Route::get('/{rejected_ebook}/edit', 'edit')->name('rejecteds-ebooks.edit');
            Route::put('/{rejected_ebook}', 'update')->name('rejecteds-ebooks.update');
            Route::get('/{rejected_ebook}/delete', 'delete')->name('rejecteds-ebooks.delete');
            Route::get('/delete', 'clear')->name('all-rejecteds-ebooks.clear');
            Route::get('/getyear', 'year')->name('all-rejecteds-ebooks.filteryear');
        });
    });

   //test
   Route::controller(GenPdfController::class)->prefix('generate')->group(function () {
    Route::post('/', 'generate')->name('generate.pdsf');
});
    //end test
    Route::controller(GeneratePdfController::class)->prefix('generate')->group(function () {
        Route::post('/', 'generate')->name('generate.pdf');
    });

    Route::controller(GenerateReportController::class)->prefix('transaction')->group(function () {
        Route::get('/{author}', 'getBook')->name('transaction.get-book');
        // Route::get('/{author}/{book}', 'getYear')->name('transaction.get-year');
    });
    //test for isbn
    Route::controller(GenReportController::class)->prefix('marites')->group(function () {
        Route::get('/{author}', 'getBook')->name('marites.get-book');
        // Route::get('/{author}/{book}', 'getYear')->name('transaction.get-year');
    });
});
