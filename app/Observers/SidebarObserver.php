<?php

namespace App\Observers;

use App\Models\Sidebar;
use App\Services\SidebarService;

class SidebarObserver
{
    public function saved(Sidebar $sidebar): void
    {
        SidebarService::clearCache();
    }

    public function deleted(Sidebar $sidebar): void
    {
        SidebarService::clearCache();
    }

    public function restored(Sidebar $sidebar): void
    {
        SidebarService::clearCache();
    }
}