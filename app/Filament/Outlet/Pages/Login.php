<?php

namespace App\Filament\Outlet\Pages;

use Filament\Auth\Pages\Login as PagesLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends PagesLogin
{
    public function getHeading(): string|Htmlable
    {
        return __('Outlet Login');
    }
}
