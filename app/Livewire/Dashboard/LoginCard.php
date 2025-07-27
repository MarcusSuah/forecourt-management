<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\LoginLog;



class LoginCard extends Component
{
     public $todayCount = 0;

    public function mount()
    {
        $this->todayCount = LoginLog::whereDate('logged_in_at', today())->count();
    }
    public function render()
    {
        return view('livewire.dashboard.login-card');
    }
}
