<?php

namespace Tests\Unit\Services\Contract;

use Tests\TestCase;
use App\Models\Contract;
use App\Models\ContractHistory;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\ContractHistoryService;
use InvalidArgumentException;

class ContractHistoryServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function should_create_history(): void
    {
        $service = app(
            ContractHistoryService::class
        );

        $contract = Contract::factory()
            ->create();

        $history = $service->create(
            $contract,
            'updated',
            'status',
            'active',
            'cancelled',
        );

        $this->assertInstanceOf(
            ContractHistory::class,
            $history
        );
    }

    #[Test]
    public function should_not_create_history_with_invalid_data(): void
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        $service = app(
            ContractHistoryService::class
        );

        $contract = Contract::factory()
            ->create();

        $service->create(
            $contract,
            '',
        );
    }

    #[Test]
    public function should_paginate_histories(): void
    {
        $service = app(
            ContractHistoryService::class
        );

        $contract = Contract::factory()
            ->create();

        ContractHistory::factory()
            ->count(15)
            ->create([
                'contract_id' => $contract->id,
            ]);

        $result = $service->paginate(
            $contract
        );

        $this->assertEquals(
            10,
            count($result->items())
        );
    }

    #[Test]
    public function should_return_empty_pagination(): void
    {
        $service = app(
            ContractHistoryService::class
        );

        $contract = Contract::factory()
            ->create();

        $result = $service->paginate(
            $contract
        );

        $this->assertCount(
            0,
            $result->items()
        );
    }
}
