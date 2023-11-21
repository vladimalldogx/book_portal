<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'book_id',
        'instance_id',
        'isbn',
        'market',
        'year',
        'month',
        'flag',
        'status',
        'format',
        'quantity',
        'price',
        'royalty',
        'author_aro_assign_user_id',
        'author_assign_user_id',
        
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
