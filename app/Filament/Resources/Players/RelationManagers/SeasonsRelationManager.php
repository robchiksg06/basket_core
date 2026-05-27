<?php

namespace App\Filament\Resources\Players\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeasonsRelationManager extends RelationManager
{
    protected static string $relationship = 'seasons';

    protected static ?string $title = 'Sezonas statistika';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pamata info')->schema([
                TextInput::make('season')
                    ->label('Sezona')
                    ->required()
                    ->placeholder('2024/25')
                    ->maxLength(20),
                TextInput::make('team_name')
                    ->label('Komanda')
                    ->maxLength(255),
                TextInput::make('games_played')
                    ->label('Spēles')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ])->columns(3),

            Section::make('Statistika (vidēji/spēle)')->schema([
                TextInput::make('points_per_game')
                    ->label('PPG (Punkti)')
                    ->numeric()
                    ->default(0)
                    ->step(0.1),
                TextInput::make('rebounds_per_game')
                    ->label('RPG (Atlēcieni)')
                    ->numeric()
                    ->default(0)
                    ->step(0.1),
                TextInput::make('assists_per_game')
                    ->label('APG (Piespēles)')
                    ->numeric()
                    ->default(0)
                    ->step(0.1),
                TextInput::make('steals_per_game')
                    ->label('SPG (Pārtverti)')
                    ->numeric()
                    ->default(0)
                    ->step(0.1),
                TextInput::make('blocks_per_game')
                    ->label('BPG (Bloki)')
                    ->numeric()
                    ->default(0)
                    ->step(0.1),
            ])->columns(5),

            Section::make('Precizitāte (%)')->schema([
                TextInput::make('field_goal_pct')
                    ->label('FG%')
                    ->numeric()
                    ->step(0.1)
                    ->suffix('%'),
                TextInput::make('three_point_pct')
                    ->label('3P%')
                    ->numeric()
                    ->step(0.1)
                    ->suffix('%'),
                TextInput::make('free_throw_pct')
                    ->label('FT%')
                    ->numeric()
                    ->step(0.1)
                    ->suffix('%'),
            ])->columns(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('season')
            ->defaultSort('season', 'desc')
            ->columns([
                TextColumn::make('season')->label('Sezona')->sortable(),
                TextColumn::make('team_name')->label('Komanda')->placeholder('—'),
                TextColumn::make('games_played')->label('Sp')->alignCenter(),
                TextColumn::make('points_per_game')->label('PPG')->alignCenter(),
                TextColumn::make('rebounds_per_game')->label('RPG')->alignCenter(),
                TextColumn::make('assists_per_game')->label('APG')->alignCenter(),
                TextColumn::make('steals_per_game')->label('SPG')->alignCenter(),
                TextColumn::make('blocks_per_game')->label('BPG')->alignCenter(),
                TextColumn::make('field_goal_pct')->label('FG%')->alignCenter()->placeholder('—')->suffix('%'),
                TextColumn::make('three_point_pct')->label('3P%')->alignCenter()->placeholder('—')->suffix('%'),
                TextColumn::make('free_throw_pct')->label('FT%')->alignCenter()->placeholder('—')->suffix('%'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()->label('Pievienot sezonu'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
