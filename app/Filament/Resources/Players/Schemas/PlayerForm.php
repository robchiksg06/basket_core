<?php

namespace App\Filament\Resources\Players\Schemas;

use Filament\Forms\Components\FileUpload;
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

                        TextInput::make('team')
                            ->label('Komanda')
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