<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ExpireDeals extends Command
{
    protected $signature = 'deals:expire';

    protected $description = 'Expire deal of the day products automatically';

    public function handle()
    {
        Product::where('is_deal', 1)
            ->whereNotNull('deal_end_at')
            ->where('deal_end_at', '<', now())
            ->update([
                'is_deal' => 0,
                'deal_end_at' => null,
            ]);

        $this->info('Expired deals cleared successfully.');
    }
}
