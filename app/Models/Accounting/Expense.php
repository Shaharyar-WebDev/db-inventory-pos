<?php

namespace App\Models\Accounting;

use App\BelongsToOutlet;
use App\Enums\TransactionType;
use App\Models\Accounting\Account;
use App\Models\Accounting\AccountLedger;
use App\Models\Accounting\ExpenseCategory;
use App\Models\Accounting\ExpenseLedger;
use App\Models\Accounting\PaymentMethod;
use App\Models\Traits\HasDocumentNumber;
use App\Models\Traits\HasTransactionType;
use App\Models\Traits\ResolvesDocumentNumber;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToOutlet, HasDocumentNumber, HasTransactionType, ResolvesDocumentNumber;

    protected $fillable = [
        'expense_number',
        'account_id',
        'expense_category_id',
        'attachments',
        'payment_method_id',
        'amount',
        'description',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public static string $documentNumberColumn = 'expense_number';

    public static string $documentNumberPrefix = 'EXP';

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function accountLedger()
    {
        return $this->morphOne(AccountLedger::class, 'source');
    }

    public function expenseLedger()
    {
        return $this->morphOne(ExpenseLedger::class, 'source');
    }

    public static function booted()
    {
        static::saved(function ($expense) {

            AccountLedger::updateOrCreate(
                [
                    'source_type' => Expense::class,
                    'source_id' => $expense->id,
                ],
                [
                    'account_id' => $expense->account_id,
                    'amount' => -$expense->amount,
                    'transaction_type' => TransactionType::EXPENSE,
                    'remarks' => "Expense Recorded: {$expense->expense_number}",
                ]
            );

            ExpenseLedger::updateOrCreate(
                [
                    'source_type' => Expense::class,
                    'source_id' => $expense->id,
                ],
                [
                    'expense_id' => $expense->id,
                    'amount' => $expense->amount,
                    'transaction_type' => TransactionType::EXPENSE,
                    'remarks' => "Expense Created: {$expense->expense_number}",
                ]
            );
        });

        static::deleting(function ($expense) {
            $expense->accountLedger()->delete();
            $expense->expenseLedger()->delete();
        });
    }
}
