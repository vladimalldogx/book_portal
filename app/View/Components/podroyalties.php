<?php

namespace App\View\Components;
use App\Models\PodTransaction;
use Illuminate\View\Component;

class podroyalties extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $count;
    public function __construct()
    {
        $podcount =
       // $this->count = PodTransaction::select(PodTransaction::raw('sum(price * quantity * 0.15) as total'))->get();
        $this->count = PodTransaction::where('quantity' ,'<>' , 0)->sum('quantity');
     }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.podroyalties');
    }
}
