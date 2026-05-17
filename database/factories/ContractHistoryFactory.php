<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contract;
use App\Models\ContractHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractHistoryFactory extends Factory
{
    protected $model = ContractHistory::class;

    public function definition(): array
    {
        return [
            'contract_id' => Contract::factory(),

            'action' => fake()->randomElement([
                'created',
                'updated',
                'cancelled',
            ]),

            'field' => 'status',

            'old_value' => 'active',

            'new_value' => 'cancelled',
        ];
    }
}
