<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Story;
use Carbon\Carbon;

class DeactivateExpiredStories extends Command
{
    protected $signature = 'stories:deactivate';
    protected $description = 'Deactivate stories that are older than 24 hours';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiryDate = Carbon::now()->subHours(24);
        Story::where('created_at', '<', $expiryDate)->where('status', 'active')->update(['status' => 'inactive']);
        $this->info('Expired stories have been deactivated.');
    }
}
