<?php

namespace App\View\Components\Employee;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banner extends Component
{
    public $greeting;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Mendapatkan waktu saat ini
        $currentTime = Carbon::now();

        // Menentukan salam sesuai dengan waktu
        if ($currentTime->hour < 12) {
            $this->greeting = 'Good Morning';
        } elseif ($currentTime->hour < 18) {
            $this->greeting = 'Good Afternoon';
        } else {
            $this->greeting = 'Good Evening';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.employee.banner', [
            'greeting' => $this->greeting,
        ]);
    }
}
