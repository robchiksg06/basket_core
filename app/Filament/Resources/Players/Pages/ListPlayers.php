<?php

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Resources\Players\PlayerResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('importStats')
                ->label('Importēt statistiku (CSV)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->url(route('players.import-stats')),
        ];
    }
}
