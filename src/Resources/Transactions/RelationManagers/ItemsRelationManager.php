<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions\RelationManagers;

use CodeWithDiki\TransactionModule\Resources\TransactionItems\TransactionItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = TransactionItemResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
