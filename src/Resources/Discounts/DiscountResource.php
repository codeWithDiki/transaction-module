<?php

namespace CodeWithDiki\TransactionModule\Resources\Discounts;

use CodeWithDiki\TransactionModule\Resources\Discounts\Pages\CreateDiscount;
use CodeWithDiki\TransactionModule\Resources\Discounts\Pages\EditDiscount;
use CodeWithDiki\TransactionModule\Resources\Discounts\Pages\ListDiscounts;
use CodeWithDiki\TransactionModule\Resources\Discounts\Pages\ViewDiscount;
use CodeWithDiki\TransactionModule\Resources\Discounts\Schemas\DiscountForm;
use CodeWithDiki\TransactionModule\Resources\Discounts\Schemas\DiscountInfolist;
use CodeWithDiki\TransactionModule\Resources\Discounts\Tables\DiscountsTable;
use BackedEnum;
use CodeWithDiki\TransactionModule\Models\Discount;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScissors;

    protected static null|\UnitEnum|string $navigationGroup = 'Transaction Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModel(): string
    {
        return config('transaction-module.discount_class', self::$model);
    }

    public static function form(Schema $schema): Schema
    {
        return DiscountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DiscountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscounts::route('/'),
            'create' => CreateDiscount::route('/create'),
            'view' => ViewDiscount::route('/{record}'),
            'edit' => EditDiscount::route('/{record}/edit'),
        ];
    }
}
