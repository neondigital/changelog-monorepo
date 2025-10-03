<?php

namespace Neondigital\ChangelogFilament;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use Neondigital\ChangelogFilament\Filament\Pages\ChangelogPage;
use Stephenjude\FilamentTwoFactorAuthentication\Pages\Challenge;

class ChangelogPlugin implements Plugin
{
    public function getId(): string
    {
        return 'blog';
    }

    public function register(Panel $panel): void
    {
        $panel->routes(fn () => [
            Route::get('/changelog', ChangelogPage::class)->name('changelog.show'),
        ])->userMenuItems([
            MenuItem::make()
                ->url(fn (): string => $panel->route('changelog.show'))
                ->label('Changelog')
            ->icon('heroicon-o-square-3-stack-3d'),
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}