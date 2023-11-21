<?php

namespace App\View\Components;
use App\Models\PodTransaction;
use App\Models\EbookTransaction;
use Illuminate\View\Component;

class Transactioncount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $count;
    public function __construct()
    {
        //count 1/3/23 phase 2
        $pods = PodTransaction::where('quantity' ,'>' , 0)->count();
        $ebooks = EbookTransaction::where('quantity' ,'>' , 0)->count();
        $this->count = $pods + $ebooks;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.transactioncount');
    }
}
