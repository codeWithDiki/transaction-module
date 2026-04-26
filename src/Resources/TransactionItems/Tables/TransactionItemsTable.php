<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('itemable_type')
                    ->searchable(),
                TextColumn::make('itemable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money(currency: 'IDR', locale: 'id-ID')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->money(currency: 'IDR', locale: 'id-ID')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    
                ]),
            ]);
    }
}
