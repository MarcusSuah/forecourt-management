<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\LoginLog;

class LoginChart extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $this->labels = collect(now()->subDays(6)->daysUntil(now()))
            ->map(fn($date) => $date->format('M d'))
            ->toArray();

        $this->data = collect(now()->subDays(6)->daysUntil(now()))
            ->map(fn($date) => LoginLog::whereDate('logged_in_at', $date)->count())
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.login-chart');
    }
}
