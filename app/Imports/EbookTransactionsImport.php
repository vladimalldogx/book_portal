<?php

namespace App\Imports;

use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\NameHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\RejectedAuthor;
use App\Models\RejectedEbookTransaction;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EbookTransactionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
       $trandate = Date::excelToDateTimeObject($row['transactiondatetime'])->format('m/d/y');
       
        
        
        $name = $row['productauthors'];
        $name = (new HumanNameFormatterHelper)->parse($name);

        $author = Author::where('firstname', 'LIKE', NameHelper::normalize($name->FIRSTNAME) . "%")->where('lastname', 'LIKE', NameHelper::normalize($name->LASTNAME) . "%")->first();
    
        $date = Carbon::parse(Date::excelToDateTimeObject($row['transactiondatetime']));

       // dd($date->month);
        if ($author) {
            
            $ebookTransaction = EbookTransaction::where('instanceid',$row['instanceid'])->where('line_item_no', $row['lineitemid'])->where('month', $date->month)->where('year', $date->year)->first();
            $book = Book::where('title', $row['producttitle'])->first();
           
           
            $aro = $author->aro_user_id;
            $pubcon = $author->user_id;
     
            if (!$book) {
                    $createbook = Book::create([
                        'title' => $row['producttitle'],
                        'isbn' =>   $row['mainproductid'] ,
                        'author_id'=>  $author->id,
                        'author_user_id'=> $pubcon,
                         'author_aro_assign_user_id'=> $aro,
                        

                    ]);
                  
            }else{
               
                $chkbook = Book::where('title', $row['producttitle'])->first();
                if($chkbook){
                    if ($ebookTransaction) {
            
                        $ebookTransaction->update([
                         'author_assign_user_id'=> $pubcon,
                           'author_aro_assign_user_id'=> $aro,
                           'author_id' => $author->id,
                            'book_id' => $book->id,
                             'year' => $date->year,
                             'isbn' =>   $row['mainproductid'] ,
                            'month' => $date->month,
                            'line_item_no' => $row['lineitemid'],
                            'teritorysold'=> $row['salesterritory'],
                            'quantity' => $row['netsoldquantity'],
                           'price' => $row['unitprice'],
                            'proceeds' => $row['proceedsofsaleduepublisher'],
                            'royalty' => $row['proceedsofsaleduepublisher'] /2,
                        ]);
                         return;
                     }else{
                        return new EbookTransaction([
                            
                            'instanceid' => $row['instanceid'],
                            'author_assign_user_id'=> $pubcon,
                            'author_aro_assign_user_id'=> $aro,
                             'author_id' => $author->id,
                             'book_id' => $chkbook->id,
                             'isbn' =>   $row['mainproductid'] ,
                             'year' => $date->year,
                             'month' => $date->month,
                             'class_of_trade' => $row['classoftradesale'],
                             'agentid'=> $row['agentstransactionid'],
                             'line_item_no' => $row['lineitemid'],
                             'teritorysold'=>$row['salesterritory'],
                             'transactiondate' => $trandate,
                             'quantity' => $row['netsoldquantity'],
                             'price' => $row['unitprice'],
                             'proceeds' => $row['proceedsofsaleduepublisher'],
                             'royalty' => $row['proceedsofsaleduepublisher'] /2,
                         ]); 
                     }

                }
                 
            }
        } else {
            $rejectedTransaction = RejectedEbookTransaction::where('line_item_no', $row['lineitemid'])->where('instanceid' , $row['instanceid'])->where('month', $date->month)->where('year', $date->year)->first();
           if ($rejectedTransaction) {
             //$royalty  =  $row['netsoldquantity'] * $row['unitprice'] * 0.20;
                $rejectedTransaction->update([
                    'author_name' => $row['productauthors'],
                   'book_title' => $row['producttitle'],
                    'year' => $date->year,
                    'month' => $date->month,
                   'class_of_trade' => $row['classoftradesale'],
                   'agentid'=> $row['agentstransactionid'],
                   'teritorysold'=>$row['salesterritory'],
                   'transactiondate' => $trandate,
                    'line_item_no' => $row['lineitemid'],
                    'quantity' => $row['netsoldquantity'],
                    'price' => $row['unitprice'],
                   'proceeds' => $row['proceedsofsaleduepublisher'],
                    'royalty' =>number_format( $row['proceedsofsaleduepublisher'] /2 ,2)
                ]);
               return;
            }
           // $royalty  =  $row['netsoldquantity'] * $row['unitprice'] * 0.20;
            RejectedEbookTransaction::create([
                'instanceid' => $row['instanceid'],
                'author_name' => $row['productauthors'],
                'book_title' => $row['producttitle'],
                'isbn' =>   $row['mainproductid'],
                'year' => $date->year,
                'month' => $date->month,
                'transactiondate' => $trandate,
                'teritorysold'=>$row['salesterritory'],
                'agentid'=> $row['agentstransactionid'],
                'class_of_trade' => $row['classoftradesale'],
                'line_item_no' => $row['lineitemid'],
                'quantity' => $row['netsoldquantity'],
                'price' => $row['unitprice'],
                'proceeds' => $row['proceedsofsaleduepublisher'],
                'royalty' => $row['proceedsofsaleduepublisher'] /2
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
public function toArray($nullValue = null, $calculateFormulas = false, $formatData = true, ?string $endColumn = null)
{

}
}