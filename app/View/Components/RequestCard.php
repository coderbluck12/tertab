<?php

namespace App\View\Components;

use App\Models\Reference;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestCard extends Component
{
    public Reference $request;

    /**
     * Create a new component instance.
     */
    public function __construct(Reference $request)
    {
//        dd($request);
        $this->request = $request;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.request-card');
    }
}
