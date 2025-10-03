<?php

namespace Neondigital\ChangelogFilament\Filament\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Neondigital\Changelog\Facades\Changelog;

class ChangelogPage extends Page
{
    use InteractsWithInfoLists;

    protected static string $route = 'changelog';

    protected static string $view = 'changelog::pages.changelog';

    public function getTitle(): string
    {
        return 'Changelog';
    }

    public function infolist(Infolist $infolist)
    {
        $latestRelease = Changelog::getLatestRelease();

        $releases = Changelog::getReleases();

        return $infolist->state([
            'items' => $releases,
        ])->schema([
            Infolists\Components\RepeatableEntry::make('items')->hiddenLabel()->schema([
                Infolists\Components\Group::make([
                    Infolists\Components\TextEntry::make('date')->formatStateUsing(
                        fn ($state) => now()->parse($state)->format('d/m/Y')
                    )->size('lg')->hiddenLabel()->columnSpan(2),
                    Infolists\Components\TextEntry::make('title')->size('lg')->hiddenLabel()->columnSpan(10),
                ])->columns(16),
                Infolists\Components\RepeatableEntry::make('changes')
                    ->hiddenLabel()
                    ->contained(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('type')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'addition' => 'info',
                                'improvement' => 'success',
                                'fix' => 'danger',
                            })->formatStateUsing(
                                fn ($state) => strtoupper($state)
                            )->hiddenLabel()->columnSpan(2),
                        Infolists\Components\TextEntry::make('title')->hiddenLabel()->columnSpan(14),
                    ])->columns(16),
                Infolists\Components\TextEntry::make('body')->hiddenLabel()->html(),
            ])->placeholder('No changelogs to view')
        ]);
    }
}