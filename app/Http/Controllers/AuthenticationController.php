<?php

namespace App\Http\Controllers;

use App\Helpers\MonthHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function home(){
        return view('homepage.index');
    }
   
    public function dashboard(Request $request)
    {
        if(auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3){
        $months = MonthHelper::getMonths();
        $books = [];
        $authors = Author::all();
       
        if($request->author){
            foreach($authors as $author){
                if($request->author ==$authors->id){
                    $books = PodTransaction::where('author_id', $authors->id)->first();
                    $salesOp = PodTransaction::where('author_id', $authors->id)->first();
                }
            }
        }

    
       return view('dashboard', compact('authors', 'books', 'months'));

        }
        else if(auth()->user()->usertype() == 4 ){
            if(auth()->user()->dept()=='SALES'){
                $months = MonthHelper::getMonths();
                $books = [];
                $authors = Author::where('user_id', auth()->user()->key())->get();
                if($request->author){
                    foreach($authors as $author){
                        if($request->author ==$authors->id){
                            $books = PodTransaction::where('author_id', $authors->id)->first();
                            $salesOp = PodTransaction::where('author_id', $authors->id)->first();
                        }
                    }
                }

                return view('dashboard', compact('authors', 'books', 'months'));  
            }elseif(auth()->user()->dept()=='ARO'){
                $months = MonthHelper::getMonths();
                $books = [];
                $authors = Author::all();
                if($request->author){
                    foreach($authors as $author){
                        if($request->author ==$authors->id){
                            $books = PodTransaction::where('author_id', $authors->id)->first();
                            $salesOp = PodTransaction::where('author_id', $authors->id)->first();
                        }
                    }
                }

                 return view('dashboard', compact('authors', 'books', 'months'));
            }
        
        }
        
    }
    public function testdash(Request $request){

        $months = MonthHelper::getMonths();
        $books = [];
        $authors = Author::all();
        $bookfind = Book::all();
      
        if($request->author){
            foreach($authors as $author){
                if($request->author ===$authors->id){
                    $books = PodTransaction::where('author_id', $authors->id)->first();
                    $salesOp = PodTransaction::where('author_id', $authors->id)->first();
                }
            }
        }

       return view('dashboard1', compact('authors', 'books', 'months','bookfind'));
       

        
}
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
