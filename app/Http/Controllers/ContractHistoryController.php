<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\JsonResponse;
use App\Services\Contract\ContractHistoryService;
use App\Http\Requests\ContractHistory\StoreContractHistoryRequest;

class ContractHistoryController extends Controller
{
    public function __construct(
        private readonly ContractHistoryService $service
    ) {}

    public function index(
        Contract $contract
    ): JsonResponse {
        $this->authorize(
            'viewAny',
            $contract
        );

        return response()->json(
            $this->service->paginate(
                $contract
            )
        );
    }

    public function store(
        StoreContractHistoryRequest $request,
        Contract $contract
    ): JsonResponse {
        $history = $this->service->create(
            $contract,
            $request->validated()
        );

        return response()->json(
            $history,
            201
        );
    }
}
