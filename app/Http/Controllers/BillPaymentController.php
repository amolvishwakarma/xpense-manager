<?php

namespace App\Http\Controllers;

use App\Actions\AddTransactionAction;
use App\Enums\BillStatusEnum;
use App\Http\Requests\BillPaymentRequest;
use App\Models\Account;
use App\Models\BillInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillPaymentController extends Controller
{
    public function __invoke(BillPaymentRequest $request, AddTransactionAction $addTransactionAction)
    {
        $data = $request->validated();

        $billInstance = BillInstance::with('bill.biller')->find($data['bill_instance_id']);
        $account = Account::find($data['account_id']);

        $transactionData = [
            'account_id' => $account->id,
            'category_id' => $billInstance->bill->biller->category_id,
            'amount' => $data['amount'],
            'date' => $data['paid_date'],
            'description' => $data['notes'],
        ];

        DB::transaction(function () use ($addTransactionAction, $transactionData, $billInstance, $account, $data) {
            $transaction = $addTransactionAction->execute(
                data: $transactionData,
                category: $billInstance->bill->biller->category,
                account: $account,
                user: Auth::user(),
            );

            $billInstance->update([
                'transaction_id' => $transaction->id,
                'amount' => $data['amount'],
                'status' => BillStatusEnum::PAID,
                'paid_date' => $data['paid_date'],
                'notes' => $data['notes'],
            ]);
        });

        return redirect()->back()
            ->with('success', 'Bill paid successfully');
    }
}
