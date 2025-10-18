<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public function __construct(public ?string $id = null, public ?string $name = null, public $value = 1, public bool $checked = false)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.checkbox');
    }
}
