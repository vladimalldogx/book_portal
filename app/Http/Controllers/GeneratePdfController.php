<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatterHelper;
use App\Helpers\UtilityHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\PodTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberFormatter;
use PDF;

class GeneratePdfController extends Controller
{
    
    public function generate(Request $request)
    {
        $request->validate([
            'author' => 'required',
            
            'book' => 'required',
            'fromYear' => 'required',
            'fromMonth' => 'required',
            'toYear' => 'required',
            'toMonth' => 'required'
            
        ]);

            if($request->has('print')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                                            ->where('quantity','>', 0)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->orderBy('year', 'DESC' )->orderByRaw('month +0 DESC' )->orderBy('isbn','ASC')->orderBy('format','DESC')->get();

                    if(count($podTransactions) > 0){
                        if(!empty($author->specroyal)){
                            $ar = $author->specroyal;

                        }else{
                            // no special royalty you may deploy  if it's direct (0.15) and indirect (0.25)
                            $ar = 0.15;

                        }
                        $gr = PodTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                        ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                        ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                        ->select(PodTransaction::raw('sum(price * quantity *'.$ar.') as total'))->first();
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('year', $year)->where('month', $month)->first();

                                if($podFirst){

                                    
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * $ar,2) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podFirst->book->title,'market' => $pod->market,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2),  'royalty' =>'$'. $paperRoyalty]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * $ar ,2);
                                
                                    $pods->push(['title' => $podFirst->book->title,'market' => $pod->market,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Hardback', 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) , 'royalty' =>'$'. number_format($hardRoyalty,2)]);
                                    
                                }   
                            }
                        }
                        $countAllTransaction = number_format($podTransactions->sum('royalty'),2);
                        if($podTransactions->sum('quantity')){

                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total",
                            'quantity' => $podTransactions->sum('quantity'),
                           
                            
                            'royalty' => $gr->total,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                

                $grand_quantity = 0;
                $grand_royalty = 0.00;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += $pod['royalty'];
                           
                        }else{
                            $grand_royalty += $pod['royalty'];
                        } 
               
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = $grand_price;
                $totalPods['revenue'] = number_format($grand_revenue, 2);
                $totalPods['royalty'] = number_format($grand_royalty,2);
            }
              

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                    ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                    ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                    ->where('royalty', '<>', 0)
                    ->orderBy('year', 'DESC' )
                    ->orderByRaw('month +0 DESC' )
                    ->get();
        
                    if(count($ebookTransactions) > 0){
                        $eprev = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                        ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                        ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                        ->select(EbookTransaction::raw('sum(proceeds /2) as total'))->first();
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
        
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                                                       /* Get all WHOLESALE */
                                                                       $wholesale = $ebookTransactions->where('year', $year)->where('month', $month)->where('class_of_trade', 'WHOLESALE');
                                                                       $wquan = 0;
                                                                       $wrev = 0;
                                                                       $whigh = 0;
                                                                       $wroyal = 0;
                                                                       $wproc = 0;
                                                                       foreach ($wholesale as $webook){
                                                                         $wproc  += $webook->proceeds;
                                                                           $wquan += $webook->quantity;
                                                                           $wrev += $webook->price * $webook->quantity;
                                                                           if($webook->price > $whigh) { $whigh = $webook->price; }
                                                                           if ($webook->royalty > $wroyal) { $wroyal = $webook->royalty;}
                                                                       }
                                                                       
                                                                       $wroyal = number_format($wproc /2 ,2) ;
                                                                       $wrev  = number_format($wrev ,2);
                                                                       $ebooks->push(['title' => $ebook->book->title, 'cs'=>$ebook->teritorysold,'year' => $year, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $wquan, 'price' => $ebook->price, 'revenue' => $wrev, 'royalty' => $wroyal]);
                                   
                                                                       /* Get all  AGENCY  Transactions */
                                 
                                                                          
                                                                       $agency = $ebookTransactions->where('year', $year)->where('month', $month)->where('class_of_trade','!=' ,'WHOLESALE');
                                                                       $aquan = 0;
                                                                       $arev = 0;
                                                                       $ahigh = 0;
                                                                       $aroyal = 0;
                                                                       $aproc = 0;
                                                                       foreach ($agency as $aebook){
                                                                         $aproc  += $aebook->proceeds;
                                                                           $aquan += $aebook->quantity;
                                                                           $arev += $aebook->price * $aebook->quantity;
                                                                           if($aebook->price > $ahigh) { $whigh = $aebook->price; }
                                                                           if ($aebook->royalty > $aroyal) { $aroyal = $aebook->royalty;}
                                                                       }
                                   
                                                                       $aroyal = number_format($aproc / 2 ,2) ;
                                                                       $arev  = number_format($arev ,2);
                                                                       $ebooks->push(['title' => $ebook->book->title, 'year' => $year,'cs'=>$ebook->teritorysold, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $aquan, 'price' => $ebook->price, 'revenue' => $arev, 'royalty' => $aroyal]);
                                }
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' => $eprev->total,
                            'price' => $ebookTransactions[0]->price,
                           
                        ]);
                    }
                }
                $grande_quantity = 0;
                $grande_royalty = 0.00;
                $grande_price = 0;
                $grande_revenue = 0;
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        $grande_quantity += $ebook['quantity'];
                        if($grande_quantity > 1){
                            $grande_royalty += $ebook['royalty'];
                           
                        }else{
                            $grande_royalty += $ebook['royalty'];
                        } 
                        if($ebook['price'] > $grande_price) { $grande_price = $ebook['price']; }
                    }
                $totalEbooks['quantity'] = $grande_quantity;
                $totalEbooks['price'] = $grande_price;
                $totalEbooks['revenue'] = number_format($grande_revenue, 3);
                $totalEbooks['royalty'] = number_format($grande_royalty,2);
               
                }
        
               // $totalRoyalties = number_format($totalPods['royalty'] + $totalEbooks['royalty'],2);
                $currentDate = Carbon::now()->format(' m/d/Y g:i A');
                $imageUrl = asset('images/header.png');
          //print pdf
                $pdf = PDF::loadView('report.pdf',[
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    //'allRoyal' =>$totalRoyalties,
                    'currentDate' => $currentDate,
                    'imageUrl' => $imageUrl,
                ]);
              $authorName = $author->getFullName();
              $date = $request->fromMonth.$request->fromYear.htmlentities('-').$request->toMonth.$request->toYear ;
                return $pdf->download($authorName.$date.'Royalty.pdf');
        
            }
            elseif($request->has('preview')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                    ->where('quantity','>', 0)
                    ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                    ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                    ->orderBy('year', 'DESC' )
                    ->orderByRaw('month +0 DESC' )
                    ->orderBy('isbn','ASC')
                    ->orderBy('format','DESC')
                    ->get();

                    if(count($podTransactions) > 0){
                        if(!empty($author->specroyal)){
                            $ar = $author->specroyal;

                        }else{
                            $ar = 0.15;

                        }
                        
                        $gr = PodTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->select(PodTransaction::raw('sum(price * quantity *'.$ar.') as total'))->first();
                      
                      
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('quantity','>', 0)->where('year', $year)->where('month', $month);

                                if($podFirst){
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * $ar,3) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podTransactions[0]->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'market' => $pod->market,'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2),  'royalty' =>'$'. $paperRoyalty]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * $ar ,2);
                                
                                    $pods->push(['title' => $podTransactions[0]->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month,  'format' => 'Hardback', 'market' => $pod->market, 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) , 'royalty' =>'$'. number_format($hardRoyalty,3)]);
                                    
                                }   
                            }
                        }
                        $countAllTransaction = number_format($podTransactions->sum('royalty'),2);
                        if($podTransactions->sum('quantity')){

                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total (Royalty):",
                            'quantity' => $podTransactions->sum('quantity'),
                           
                            
                            'royalty' => $gr->total,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                

                $grand_quantity = 0;
                $grand_royalty = 0.00;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += $pod['royalty'];
                           
                        }else{
                            $grand_royalty += $pod['royalty'];
                        } 
               
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = $grand_price;
                $totalPods['revenue'] = number_format($grand_revenue, 3);
                $totalPods['royalty'] = number_format($grand_royalty,2);
            }
              
                
             

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'price' => 0  ,'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                                                ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                                ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                                ->where('royalty', '<>', 0)
                                                ->orderBy('year', 'DESC' )
                                                ->orderByRaw('month +0 DESC' )
                                                ->get();
        
                    if(count($ebookTransactions) > 0){
                        $eprev = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)->orwhere('isbn', $book)
                        ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                        ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                        ->select(EbookTransaction::raw('sum(proceeds /2) as total'))->first();
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                      /* Get all WHOLESALE */
                                      $wholesale = $ebookTransactions->where('year', $year)->where('month', $month)->where('class_of_trade', 'WHOLESALE');
                                      $wquan = 0;
                                      $wrev = 0;
                                      $whigh = 0;
                                      $wroyal = 0;
                                      $wproc = 0;
                                      foreach ($wholesale as $webook){
                                        $wproc  += $webook->proceeds;
                                          $wquan += $webook->quantity;
                                          $wrev += $webook->price * $webook->quantity;
                                          if($webook->price > $whigh) { $whigh = $webook->price; }
                                          if ($webook->royalty > $wroyal) { $wroyal = $webook->royalty;}
                                      }
                                      
                                      $wroyal = number_format($wproc /2 ,2) ;
                                      $wrev  = number_format($wrev ,2);
                                      $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'cs'=>$ebook->teritorysold, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $wquan, 'price' => $ebook->price, 'revenue' => $wrev, 'royalty' => $wroyal]);
  
                                      /* Get all  AGENCY  Transactions */

                                         
                                      $agency = $ebookTransactions->where('year', $year)->where('month', $month)->where('class_of_trade','!=' ,'WHOLESALE');
                                      $aquan = 0;
                                      $arev = 0;
                                      $ahigh = 0;
                                      $aroyal = 0;
                                      $aproc = 0;
                                      foreach ($agency as $aebook){
                                        $aproc  += $aebook->proceeds;
                                          $aquan += $aebook->quantity;
                                          $arev += $aebook->price * $aebook->quantity;
                                          if($aebook->price > $ahigh) { $ahigh = $aebook->price; }
                                          if ($aebook->royalty > $aroyal) { $aroyal = $aebook->royalty;}
                                      }
  
                                      $aroyal = number_format($aproc / 2 ,2) ;
                                      $arev  = number_format($arev ,2);
                                      $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'cs'=>$ebook->teritorysold, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $aquan, 'price' => $ebook->price, 'revenue' => $arev, 'royalty' => $aroyal]);



                                   
                                      }
  
                                  
                               
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total (Royalty):",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' =>  $eprev->total,
                            'price' => $ebookTransactions[0]->price,
                            
                        ]);
                    }
                }
                $grande_quantity = 0;
                $grande_royalty = 0.00;
                $grande_price = 0;
                $grande_revenue = 0;
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        $grande_quantity += $ebook['quantity'];
                        if($grande_quantity > 1){
                            $grande_royalty += $ebook['royalty'];
                           
                        }else{
                            $grande_royalty += $ebook['royalty'];
                        } 
                        if($ebook['price'] > $grande_price) { $grande_price = $ebook['price']; }
                    }
                $totalEbooks['quantity'] = $grande_quantity;
                $totalEbooks['price'] = $grande_price;
                $totalEbooks['revenue'] = number_format($grande_revenue, 3);
                $totalEbooks['royalty'] = $grande_royalty;
                  
                }
        
                $totalRoyalties = number_format((float) $totalPods['royalty'] + $totalEbooks['royalty'], 3);
              //  $numberFormatter = NumberFormatterHelper::numtowords($totalRoyalties);
                $currentDate = Carbon::now();
                // preview data 
                return view('prev',[
                    
                    
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'totalRoyalties' => $totalRoyalties,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    'currentDate' => $currentDate,
                   
                ]);
        
            }
            

           
        
       

    }
}