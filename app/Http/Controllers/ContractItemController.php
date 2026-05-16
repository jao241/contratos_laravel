<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractItem\StoreContractItemRequest;
use App\Http\Requests\ContractItem\UpdateContractItemRequest;

use App\Models\Contract;
use App\Models\ContractItem;

use App\Services\ContractItemService;

use Illuminate\Http\JsonResponse;

class ContractItemController extends Controller
{
    public function __construct(
        protected ContractItemService $itemService
    ) {}

    public function store(
        StoreContractItemRequest $request,
        Contract $contract
    ): JsonResponse {
        $this->authorize('addItem', $contract);

        $items = $this->itemService->createMany($contract, array_values($request->validated()));

        return response()->json($items, 201);
    }

    public function update(
        UpdateContractItemRequest $request,
        ContractItem $contractItem
    ): JsonResponse {
        $this->authorize('update', $contractItem);

        $contractItem = $this->itemService->update(
            $contractItem,
            $request->validated()
        );

        return response()->json($contractItem);
    }

    public function destroy(
        ContractItem $contractItem
    ): JsonResponse {
        $this->authorize('delete', $contractItem);

        $this->itemService->delete($contractItem);

        return response()->json(
            null,
            204
        );
    }
}
