<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Komandas informācija')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nosaukums')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('logo')
                            ->image()
                            ->directory('team-logos')
                            ->disk('public')
                            ->label('Logo'),

                        TextInput::make('country')
                            ->label('Valsts')
                            ->maxLength(100),

                        TextInput::make('league')
                            ->label('Līga')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}