<?php

namespace App\Services\Contract;

use App\Models\Contract;
use App\Models\ContractHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class ContractHistoryService
{
    public function create(
        Contract $contract,
        string $action,
        ?string $field = null,
        mixed $oldValue = null,
        mixed $newValue = null,
    ): ContractHistory {

        if (blank($action)) {

            throw new InvalidArgumentException(
                'Action is required.'
            );
        }

        return $contract->histories()->create([
            'action' => $action,
            'field' => $field,
            'old_value' => $this->normalizeValue($oldValue),
            'new_value' => $this->normalizeValue($newValue),
        ]);
    }

    private function normalizeValue(
        mixed $value
    ): ?string {

        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }

    public function paginate(
        Contract $contract
    ): LengthAwarePaginator {
        return $contract->histories()
            ->latest()
            ->paginate(10);
    }
}
