<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login;

class CustomLogin extends Login
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function getHeading(): string
    {
        return 'Login to Your Account';
    }

    public function getSubheading(): ?string
    {
        return 'Enter your credentials to access the admin panel';
    }

    protected function getView(): string
    {
        return 'filament.auth.custom-login';
    }
}