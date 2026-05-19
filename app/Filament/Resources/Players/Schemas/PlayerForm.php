<?php

namespace App\Filament\Resources\Players\Schemas;

use App\Models\Team;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Spēlētāja informācija')
                    ->schema([
                        TextInput::make('name')
                            ->label('Vārds, uzvārds')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('position')
                            ->label('Pozīcija')
                            ->placeholder('PG, SG, SF, PF, C')
                            ->maxLength(50),

                        TextInput::make('height')
                            ->label('Augums (cm)')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(300),

                        Select::make('team_id')
                            ->label('Komanda')
                            ->options(Team::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $team = Team::find($state);
                                $set('team', $team?->name);
                            }),

                        TextInput::make('team')
                            ->label('Komandas nosaukums')
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->label('Spēlētāja bilde')
                            ->image()
                            ->disk('public')
                            ->directory('players')
                            ->visibility('public')
                            ->imageEditor(),
                    ])
                    ->columns(2),
            ]);
    }
}