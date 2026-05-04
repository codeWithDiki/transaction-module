<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions\Tables;

use CodeWithDiki\TransactionModule\Enums\TransactionStatus;
use CodeWithDiki\TransactionModule\Facades\TransactionModule;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trx_id')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->money(currency: 'IDR', locale: 'id-ID')
                    ->sortable(),
                TextColumn::make('tax_amount')
                    ->money(currency: 'IDR', locale: 'id-ID')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->money(currency: 'IDR', locale: 'id-ID')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('failed_at')
                    ->dateTime()
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
                Action::make("update_status")
                    ->label("Update Status")
                    ->schema([
                        Select::make("status")
                            ->options(TransactionStatus::class)
                            ->required(),
                        Textarea::make("note")
                    ])
                    ->requiresConfirmation()
                    ->action(function(array $data, Model $record) {
                        
                        // You can also save the note to a related model or log it as needed
                        TransactionModule::setTransactionStatus(
                            transaction:$record, 
                            status:$data['status'], 
                            note:$data['note'] ?? null,
                            user:Auth::user()
                        );

                        Notification::make()
                            ->title("Transaction status updated")
                            ->body("The transaction status has been updated to {$data['status']->value}.")
                            ->success()
                            ->send();
                    })
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
