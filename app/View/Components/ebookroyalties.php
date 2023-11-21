<?php

namespace App\View\Components;
use App\Models\EbookTransaction;

use Illuminate\View\Component;

class ebookroyalties extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $count;
    public function __construct()
    {
       $ec = EbookTransaction::where('quantity' ,'<>' , 0)->sum('quantity');
      //  $ec = EbookTransaction::where('quantity' ,'!=' , 0)->select(EbookTransaction::raw('sum(proceeds /2) As royalty'))->first();
        $this->count = $ec;
      // $this->count = EbookTransaction::where('quantity' ,'>' , 0)->count('royalty');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ebookroyalties');
    }
}
