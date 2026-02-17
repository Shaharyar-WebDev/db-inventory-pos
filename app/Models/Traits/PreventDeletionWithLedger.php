<?php

namespace App\Models\Traits;

use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

trait PreventDeletionWithLedger
{
    protected static function bootPreventDeletionWithLedger(): void
    {
        static::deleting(function ($model) {
            if ($model->ledger()->exists()) {
                Notification::make('record_deletion_error')
                    ->danger()
                    ->title('Error While Deleting Record')
                    ->body('Cannot delete item with linked ledger entries')
                    ->send();

                throw new Halt();
            }
        });
    }
}
