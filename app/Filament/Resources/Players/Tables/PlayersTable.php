<?php

namespace App\Filament\Resources\Players\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Bilde')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-player.png')),

                TextColumn::make('name')
                    ->label('Spēlētājs')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position')
                    ->label('Pozīcija')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                TextColumn::make('height')
                    ->label('Augums')
                    ->suffix(' cm')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('team')
                    ->label('Komanda')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Izveidots')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atjaunināts')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Rediģēt'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Dzēst atlasītos'),
                ]),
            ]);
    }
}