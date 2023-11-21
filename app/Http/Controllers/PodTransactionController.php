<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Imports\PodFakesImport;
use App\Imports\PodTransactionsImport;
use App\Jobs\SavePodTransaction;
use App\Models\Author;
use App\Models\Book;
use App\Models\PodFake;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PodTransaction;
use Maatwebsite\Excel\Facades\Excel;

class PodTransactionController extends Controller
{
    public function index()
    {
        if( auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3 ){
        
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            return view('pod.index', [
                'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10)
            ], compact('books' ,'authors', 'month','year'));
        }
        else if( auth()->user()->usertype() == 4 ){
            if(auth()->user()->dept()=='SALES'){
                $authors = Author::where('user_id',auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id', auth()->user()->key())->get();
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
               return view('pod.index', [
                    'pod_transactions' => PodTransaction::where('author_assign_user_id' , auth()->user()->key())->where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10)
                 ], compact('books','authors' , 'month','year'));
            }elseif(auth()->user()->dept()=='ARO'){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            return view('pod.index', [
                'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10)
            ], compact('books' ,'authors', 'month','year'));
            }
                
        }   
        
    }
    
    
    public function searchBooks(Request $request)
    {
        if( auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3 ){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            $pod = PodTransaction::where('quantity','>',0 )->where('book_id', $request->book_id)->paginate(10);
           
            if ($request->book_id == 'all') {
              $pod = PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10);
              return view('pod.index', [
                'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
            ], compact('books' ,'authors','month','year'));
            }else{
                return view('pod.index', [
                    'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
                ], compact('books' ,'authors','month','year'));
            }
           
          
        }elseif(auth()->user()->usertype() == 4){
            if(auth()->user()->dept()=="SALES"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('user_id',auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id', auth()->user()->key())->get();
                $pod = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id', auth()->user()->key())->where('book_id', $request->book_id)->paginate(10);
               
                if ($request->book_id == 'all') {
                    $pod = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id', auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10);
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
                    ], compact('books' ,'authors','month','year'));
                }
               
              
            }elseif(auth()->user()->dept()=='ARO'){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('aro_user_id',auth()->user()->key())->get();
                $books = Book::where('author_aro_assign_user_id', auth()->user()->key())->get();
                $pod = PodTransaction::where('quantity','>',0 )->where('author_aro_assign_user_id', auth()->user()->key())->where('book_id', $request->book_id)->paginate(10);
               
                if ($request->book_id == 'all') {
                    $pod = PodTransaction::where('quantity','>',0 )->where('author_aro_assign_user_id', auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10);
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $pod, 'books' => $books , 'authors' => $authors
                    ], compact('books' ,'authors','month','year'));
                }        
            }       
        }  
    }
     //not yet implemented nor no function as of 01-21-23
    public function searchAuthor(Request $request){
        //for next shift operation
       
        if(auth()->user()->usertype()== 1 || auth()->user()->usertype()== 2 || auth()->user()->usertype()== 3 ){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();

            if($request->author_id == 'all'){
                $authors = Author::all();
                $books = Book::all();
                return view('pod.index', [
                    'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10)
                ], compact('books' ,'authors','month','year'));
            }else{
                return view('pod.index', [
                    'pod_transactions' => PodTransaction::where('quantity','>',0 )->where('author_id', $request->author_id)->paginate(10), 'books' => $books , 'authors' => $authors
                ],compact('month','year'));
    
            }
           

        }elseif(auth()->user()->usertype()== 4){
            //sales
            if(auth()->user()->dept()== "SALES" ){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('user_id',auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id', auth()->user()->key())->get();
                if($request->author_id == 'all'){
                    $authors = Author::where('user_id' , auth()->user()->key())->get();
                  $books = Book::where('author_assign_user_id' , auth()->user()->key())->get();
                    return view('pod.index', [
                        'pod_transactions' => PodTransaction::where('quantity','>',0 )->where('author_assign_user_id', auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10)
                    ], compact('books' ,'authors','month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => PodTransaction::where('quantity','>',0 )->where('author_assign_user_id', auth()->user()->key())->where('author_id', $request->author_id)->paginate(10), 'books' => $books , 'authors' => $authors
                    ],compact('month','year'));
                }
              

            }
            //aro 
            elseif(auth()->user()->dept()== "ARO"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::all();
                $books = Book::all();
                
                if($request->author_id == 'all'){
                    $authors = Author::all();
                    $books = Book::all();
                    return view('pod.index', [
                        'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10)
                    ], compact('books' ,'authors','month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => PodTransaction::where('quantity','>',0 )->where('author_id', $request->author_id)->paginate(10), 'books' => $books , 'authors' => $authors
                    ],compact('month','year'));
                }
               
            }
        }
    }
    //
    public function sort(Request $request){
        if( auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3 ){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            $pod = PodTransaction::where('quantity','>',0 )->where('status', $request->status)->paginate(10);
            $pody = PodTransaction::where('quantity','>',0 )->where('year', $request->years)->paginate(10);
           
            if($request->years=='all'){
               // $pody = PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10);
               return view('pod.index', [
                'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10), 
            ],compact('books' ,'authors', 'month','year'));
            }else{
                return view('pod.index', [
                    'pod_transactions' => $pody, 
                ],compact('books' ,'authors', 'month','year'));
            }
           
        }elseif( auth()->user()->usertype() == 4){
            if(auth()->user()->dept()=="SALES"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('user_id' , auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id' , auth()->user()->key())->get(); 
                $pody = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->where('year', $request->years)->paginate(10);
    
                if($request->years=='all'){
                    return view('pod.index', [
                        'pod_transactions' =>  PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10), 
                    ],compact('books' ,'authors', 'month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $pody, 
                    ],compact('books' ,'authors', 'month','year'));
                }
            } 
            else if(auth()->user()->dept()=="ARO"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::all();
                $books = Book::all();
                $pody = PodTransaction::where('quantity','>',0 )->where('year', $request->years)->paginate(10);
               
                if($request->years=='all'){
                
                    return view('pod.index', [
                        'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10), 
                    ],compact('books' ,'authors', 'month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $pody, 
                    ],compact('books' ,'authors', 'month','year'));
                }    
             }      
        }
    }
  
    public function sortStatus(Request $request){
        if(auth()->user()->usertype()== 1 || auth()->user()->usertype()== 2 || auth()->user()->usertype()== 3){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            $pod = PodTransaction::where('quantity','>',0 )->where('status', $request->status)->paginate(10);
            if($request->status == 'all'){
                $pod = PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10);
            }
            return view('pod.index', [
                'pod_transactions' => $pod,
            ],compact('books' ,'authors', 'month','year'));
        }
        elseif(auth()->user()->usertype()== 4){
            if(auth()->user()->dept()=="SALES"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('user_id' , auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id' , auth()->user()->key())->get();
                $pod = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->where('status', $request->status)->paginate(10);
                if($request->status == 'all'){
                    $pod = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10);
                }
                return view('pod.index', [
                    'pod_transactions' => $pod,
                ],compact('books' ,'authors', 'month','year'));
            }
            elseif(auth()->user()->dept()=="ARO"){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            $pod = PodTransaction::where('quantity','>',0 )->where('status', $request->status)->paginate(10);

            if($request->status == 'all'){
                $pod = PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10);
            }
            return view('pod.index', [
                'pod_transactions' => $pod,
            ],compact('books' ,'authors', 'month','year'));
            }
        }
       
    }
    public function sortMonth(Request $request){
        if(auth()->user()->usertype()== 1 || auth()->user()->usertype()== 2 || auth()->user()->usertype()== 3){
            $month = MonthHelper::getMonths();
            $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
            $authors = Author::all();
            $books = Book::all();
            $podm = PodTransaction::where('quantity','>',0 )->where('month', $request->months)->paginate(10);
            if($request->months=='all'){
             
                return view('pod.index', [
                    'pod_transactions' => PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10), 
                ],compact('books' ,'authors', 'month','year'));
            }else{
                return view('pod.index', [
                    'pod_transactions' => $podm, 
                ],compact('books' ,'authors', 'month','year'));
            }
        }
        elseif(auth()->user()->usertype() == 4 ){
            if(auth()->user()->dept()=="SALES"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::where('user_id' , auth()->user()->key())->get();
                $books = Book::where('author_assign_user_id' , auth()->user()->key())->get();
                $podm = PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->where('month', $request->months)->paginate(10);
           
                if($request->months=='all'){
               
                  return view('pod.index', [
                    'pod_transactions' => PodTransaction::where('quantity','>',0 )->where('author_assign_user_id' , auth()->user()->key())->orderBy('created_at', 'DESC')->paginate(10), 
                ],compact('books' ,'authors', 'month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $podm, 
                    ],compact('books' ,'authors', 'month','year'));
                }
            }elseif(auth()->user()->dept()=="ARO"){
                $month = MonthHelper::getMonths();
                $year =PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year;
                $authors = Author::all();
                $books = Book::all();
                $podm = PodTransaction::where('quantity','>',0 )->where('month', $request->months)->paginate(10);
                if($request->months=='all'){
                    
                    return view('pod.index', [
                        'pod_transactions' =>PodTransaction::where('quantity','>',0 )->orderBy('created_at', 'DESC')->paginate(10), 
                    ],compact('books' ,'authors', 'month','year'));
                }else{
                    return view('pod.index', [
                        'pod_transactions' => $podm, 
                    ],compact('books' ,'authors', 'month','year'));
                }
            }
        }
    }
    public function clear(){
       PodTransaction::truncate();
       return back();
    }
    public function create()
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('pod.create', compact('months', 'authors', 'books'));
    }

    public function importPage()
    {
        return view('pod.import', [
            'months' => MonthHelper::getMonths(),
            'year' => PodTransaction::select('year')->orderBy('year', 'desc')->first() ?? now()->year
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'year' => 'required',
            'month' => 'required'
        ]);

        ini_set('max_execution_time', -1);
        Excel::import(new PodTransactionsImport($request->year, $request->month), $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        
        return back()->with('success', 'Data successfully imported');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author' => 'required',
            'book_title' => 'required',
            'year' => 'required',
            'month' => 'required',
            'flag' => 'required',
            'format' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);
        $x = $request->format;
        $format = strtoupper(substr($x ,-3));
        $instanceid  = "RM".$request->year.$request->month.substr($request->isbn,-4).$format;

        $getRevenue = $request->quantity * $request->price;
        $royalty = number_format($getRevenue * 0.15 ,3);
        $pod = PodTransaction::create([
            'author_id' => $request->author,
            'book_id' => $request->book_title,
            'instance_id'=>$instanceid,
            'year' => $request->year,
            'month' => $request->month,
            'flag' => $request->flag,
            'status' => $request->status,
            'format' => $request->format,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'royalty' => number_format($royalty , 3)
        ]);


        return redirect(route('pod.create'))->with('success', 'Transaction successfully saved');
    }

    public function delete(PodTransaction $pod)
    {
        $pod->delete();
        return redirect()->route('pod.index')->with('success', 'Transaction successfully deleted');
    }

    public function edit(PodTransaction $pod)
    {
        $months = MonthHelper::getMonths();
        $authors = Author::all();
        $books = Book::all();
        return view('pod.edit', compact('pod', 'months', 'authors', 'books'));
    }

    public function update(Request $request, PodTransaction $pod)
    {
        $request->validate([
            'author' => 'required',
            'isbn' => 'required',
            'book_title' => 'required',
            'year' => 'required',
            'month' => 'required',
            'flag' => 'required',
            'format' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);
        $x = $request->format;
        $format = strtoupper(substr($x ,-3));
        $year =$request->year ;
        $month= $request->month;
        $instanceid  = "RM".$year.$month.substr($request->isbn,-3).$format;
      
        $royalty = $request->quantity * $request->price * 0.15;
        $pod->update([
            'author_id' => $request->author,
            'book_id' => $request->book_title,
            'instance_id'=>$instanceid,
            'year' => $request->year,
            'month' => $request->month,
            'flag' => $request->flag,
            'status' => $request->status,
            'format' => $request->format,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'royalty' => number_format($royalty,3)
        ]);

        $book = Book::where('id', $request->book_title)->first();
       if($book){
        $book->update([
            'book_id' => $request->book_title,
            'author_id' => $request->author

        ]);
       }


        return redirect(route('pod.edit', ['pod' => $pod]))->with('success', 'Transaction successfully updated');
    }
}
