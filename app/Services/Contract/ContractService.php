<?php

namespace App\Services\Contract;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use DomainException;

class ContractService
{
    public function __construct(
        private readonly ContractHistoryService $historyService,
        protected ContractCalculatorService $calculatorService
    ) {}

    public function paginate(): LengthAwarePaginator
    {
        return Contract::query()
            ->with([
                'client',
                'items.service',
            ])
            ->latest()
            ->paginate(10)
            ->through(function (Contract $contract) {

                return array_merge(
                    $contract->toArray(),
                    [
                        'total' => $this->calculatorService
                            ->calculate($contract),
                    ]
                );
            });
    }

    public function create(
        array $data
    ): Contract {

        $client = Client::query()
            ->findOrFail($data['client_id']);

        if ($client->status === 'inactive') {

            throw new DomainException(
                'Inactive clients cannot create contracts.'
            );
        }

        $contract = Contract::query()
            ->create($data);

        $this->historyService->create(
            contract: $contract,
            action: 'created',
        );

        return $contract->refresh();
    }

    public function update(
        Contract $contract,
        array $data
    ): Contract {

        $original = $contract->getOriginal();

        $contract->update($data);

        foreach ($contract->getChanges() as $field => $newValue) {
            $action = 'updated';

            if ($field === 'updated_at') {
                continue;
            }

            if ($newValue == 'cancelled') {
                $action = 'cancelled';
            }

            $oldValue = $original[$field] ?? null;

            $this->historyService->create(
                contract: $contract,
                action: $action,
                field: $field,
                oldValue: $oldValue,
                newValue: $newValue,
            );
        }

        return $contract->refresh();
    }

    public function cancel(
        Contract $contract
    ): Contract {

        $oldStatus = $contract->status;

        $contract->update([
            'status' => 'cancelled',
        ]);

        $this->historyService->create(
            contract: $contract,
            action: 'cancelled',
            field: 'status',
            oldValue: $oldStatus,
            newValue: 'cancelled',
        );

        return $contract->refresh();
    }

    public function delete(Contract $contract): void
    {
        $contract->delete();
    }
}
