<?php

namespace CodeWithDiki\TransactionModule\Resources\Customers;

use CodeWithDiki\TransactionModule\Resources\Customers\Pages\CreateCustomer;
use CodeWithDiki\TransactionModule\Resources\Customers\Pages\EditCustomer;
use CodeWithDiki\TransactionModule\Resources\Customers\Pages\ListCustomers;
use CodeWithDiki\TransactionModule\Resources\Customers\Pages\ViewCustomer;
use CodeWithDiki\TransactionModule\Resources\Customers\Schemas\CustomerForm;
use CodeWithDiki\TransactionModule\Resources\Customers\Schemas\CustomerInfolist;
use CodeWithDiki\TransactionModule\Resources\Customers\Tables\CustomersTable;
use BackedEnum;
use CodeWithDiki\TransactionModule\Models\Customer;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static null|\UnitEnum|string $navigationGroup = 'Transaction Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
