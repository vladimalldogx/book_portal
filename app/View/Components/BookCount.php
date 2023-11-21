<?php

namespace App\View\Components;
use App\Models\Book;
use Illuminate\View\Component;

class BookCount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $count;
    public function __construct()
    {
        $this->count = Book::count('id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.book-count');
    }
}
