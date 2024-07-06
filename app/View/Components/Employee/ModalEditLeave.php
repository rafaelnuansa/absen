<?php

namespace App\View\Components\Employee;

use Illuminate\View\Component;

class ModalEditLeave extends Component
{
    public $leave;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $leave
     * @return void
     */
    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.employee.modal-edit-leave')
            ->with('leave', $this->leave);
    }
}
