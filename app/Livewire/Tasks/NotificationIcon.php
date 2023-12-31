<?php
// app/Http/Livewire/NotificationIcon.php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationIcon extends Component
{
    protected $listeners = ['notificationRead' => 'updateIcon'];

    public $hasUnreadNotifications = false;

    public function mount()
    {
        $this->hasUnreadNotifications = auth()->user()->unreadNotifications->isNotEmpty();
    }

    public function updateIcon()
    {
        $this->hasUnreadNotifications = auth()->user()->unreadNotifications->isNotEmpty();
    }

    public function render()
    {
        return view('livewire.notification-icon');
    }
}
