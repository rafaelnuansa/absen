<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ToggleDark extends Component
{
    public $darkModeActive;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->darkModeActive = session('dark_mode_active', false);
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->darkModeActive = session('dark_mode_active', false);
    }

    /**
     * Toggle dark mode.
     *
     * @return void
     */
    public function toggleDarkMode()
    {
        $this->darkModeActive = !$this->darkModeActive;
        session(['dark_mode_active' => $this->darkModeActive]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.toggle-dark');
    }
}
