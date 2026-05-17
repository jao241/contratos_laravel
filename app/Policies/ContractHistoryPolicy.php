<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContractHistory;

class ContractHistoryPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(
        ?User $user,
        ContractHistory $history
    ): bool {
        return true;
    }
}
