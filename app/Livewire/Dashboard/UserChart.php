<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Login;
use Illuminate\Support\Carbon;

class UserChart extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates->push($day);
        }

        $this->labels = $dates->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();

        $this->data = $dates
            ->map(function ($date) {
                return Login::whereDate('created_at', $date)->count();
            })
            ->toArray();
    }

    public function render()
    {
        return view('components.dashboard.user-chart');
    }
}
