<?php

namespace App\Providers;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Action::configureUsing(function(Action $action){
        //     if($action->getDefaultName() === 'create'){
        //           return $action->extraAttributes(['style' => 'color:white']);
        //     }
        // });

        TextColumn::macro('disableNumericFormatting', function (): static {
            $this->extraAttributes(['data-disable-numeric' => true]);

            return $this;
        });

        TextColumn::macro('currency', function () {
            $this->prefix(app_currency_symbol())
                ->default(0);

            return $this;
        });

        TextInput::macro('currency', function () {
            $this->prefix(app_currency_symbol())
                ->numeric()
                ->default(0);

            return $this;
        });

        TextColumn::configureUsing(function (TextColumn $column): void {
            $column->toggleable()
                ->sortable()
                ->searchable();

            $column->formatStateUsing(function ($state) use ($column) {
                if ($column->getExtraAttributes()['data-disable-numeric'] ?? false) {
                    return $state;
                }

                return is_numeric($state)
                    ? default_number_format((float) $state)
                    : $state;
            });
        });

        Column::configureUsing(function (Column $column) {
            $column->placeholder('---');
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->deferFilters(false)
                ->persistFiltersInSession()
                ->reorderableColumns()
                ->defaultDateDisplayFormat(app_date_format())
                ->defaultDateTimeDisplayFormat(app_date_time_format())
                ->deferColumnManager(false);
        });

        Select::configureUsing(function (Select $select) {
            $select->searchable()
                ->preload()
                ->optionsLimit(10);
        });

        ForceDeleteAction::configureUsing(function (ForceDeleteAction $action) {
            $action->action(function () use ($action): void {
                try {
                    $result = $action->process(static fn (Model $record): ?bool => $record->forceDelete());

                    if (! $result) {
                        $action->failure();

                        return;
                    }

                    $action->success();
                } catch (\Throwable $e) {
                    // Handle exception if process or forceDelete fails
                    $errorRef = strtoupper(Str::random(8));

                    Notification::make('record_deletion_error')
                        ->danger()
                        ->title('Error While Deleting Record')
                        ->body(sprintf(
                            'Database error while deleting record. SQLSTATE: %s | Code: %s | Ref: %s',
                            $e->errorInfo[0] ?? 'N/A',
                            $e->errorInfo[1] ?? $e->getCode(),
                            $errorRef
                        ))
                        ->send();

                    $action->failure();

                    // Optional: log the exception
                    // logger()->error('Deletion failed: '.$e->getMessage());
                }
            });
        });

        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action->action(function () use ($action): void {
                try {
                    $result = $action->process(static fn (Model $record): ?bool => $record->delete());

                    if (! $result) {
                        $action->failure();

                        return;
                    }

                    $action->success();

                } catch (\Throwable $e) {

                    $errorRef = strtoupper(Str::random(8));

                    Notification::make('record_deletion_error')
                        ->danger()
                        ->title('Error While Deleting Record')
                        ->body(sprintf(
                            'Database error while deleting record. SQLSTATE: %s | Code: %s | Ref: %s',
                            $e->errorInfo[0] ?? 'N/A',
                            $e->errorInfo[1] ?? $e->getCode(),
                            $errorRef
                        ))
                        ->send();

                    $action->failure();

                }
            });
        });

        Blueprint::macro('belongsToOutlet', function (bool $nullable = true) {
            $column = $this->foreignId('outlet_id');

            if ($nullable) {
                $column->nullable();
            }

            return $column->constrained('outlets')->restrictOnDelete();
        });

        Blueprint::macro('money', function (string $column) {
            return $this->decimal($column, 19, 4)->default(0);
        });

        Blueprint::macro('quantity', function (string $column) {
            return $this->decimal($column, 15, 3)->default(0);
        });

        FilamentAsset::registerCssVariables([
            'background-image' => 'url('.asset('images/background/header.png').')',
        ]);
    }
}
