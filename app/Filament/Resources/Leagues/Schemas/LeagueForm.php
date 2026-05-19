<?php

namespace App\Filament\Resources\Leagues\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeagueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Līgas informācija')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nosaukums')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Apraksts')
                            ->rows(4),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('leagues')
                            ->disk('public'),
                    ])
                    ->columns(2),
            ]);
    }
}