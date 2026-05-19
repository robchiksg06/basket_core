<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
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

                        Select::make('leagues')
                            ->label('Līgas')
                            ->relationship('leagues', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
                    ->columns(2),
            ]);
    }
}