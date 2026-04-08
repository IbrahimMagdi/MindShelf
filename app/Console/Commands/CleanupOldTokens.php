<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\PersonalAccessToken;

#[Signature('tokens:cleanup')]
#[Description('Delete tokens not used for 3 days')]
class CleanupOldTokens extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = PersonalAccessToken::where('last_used_at', '<', now()->subDays(3))
            ->orWhere(function ($query) {
                $query->whereNull('last_used_at')
                    ->where('created_at', '<', now()->subDays(3));
            })
            ->delete();

        $this->info("✅ Deleted {$count} inactive tokens.");

        \Log::info("Token cleanup: {$count} tokens deleted");
    }
}
