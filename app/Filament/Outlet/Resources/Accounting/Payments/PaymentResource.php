<?php

namespace App\Filament\Outlet\Resources\Accounting\Payments;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Accounting\Payment;
use App\Support\Traits\HasTimestampColumns;
use Filament\Support\Icons\Heroicon;
use App\Filament\Outlet\Resources\Accounting\Payments\Pages\EditPayment;
use App\Filament\Outlet\Resources\Accounting\Payments\Pages\ViewPayment;
use App\Filament\Outlet\Resources\Accounting\Payments\Pages\ListPayments;
use App\Filament\Outlet\Resources\Accounting\Payments\Pages\CreatePayment;
use App\Filament\Outlet\Resources\Accounting\Payments\Schemas\PaymentForm;
use App\Filament\Outlet\Resources\Accounting\Payments\Tables\PaymentsTable;
use App\Filament\Outlet\Resources\Accounting\Payments\Schemas\PaymentInfolist;

class PaymentResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    use HasTimestampColumns;

    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $recordTitleAttribute = 'payment_number';

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            // 'view' => ViewPayment::route('/{record}'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
