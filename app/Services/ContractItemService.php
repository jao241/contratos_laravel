<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractItem;
use Illuminate\Database\Eloquent\Collection;

class ContractItemService
{
    public function create(
        Contract $contract,
        array $data
    ): ContractItem {
        return $contract->items()->create($data);
    }

    public function createMany(Contract $contract, array $items): Collection
    {
        $contract->items()->insert(
            collect($items)->map(fn(array $item) => [
                'contract_id' => $contract->id,
                'service_id'  => $item['service_id'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ])->toArray()
        );

        return $contract->items()->whereIn('service_id', collect($items)->pluck('service_id'))->get();
    }

    public function update(
        ContractItem $item,
        array $data
    ): ContractItem {
        $item->update($data);

        return $item->refresh();
    }

    public function delete(ContractItem $item): void
    {
        $item->delete();
    }
}
