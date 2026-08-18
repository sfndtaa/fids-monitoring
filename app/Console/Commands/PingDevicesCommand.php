<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Services\PingService;
use Illuminate\Console\Command;

class PingDevicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fids:ping {--timeout=800 : Timeout in milliseconds} {--location= : Filter by location}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping all registered FIDS devices and update their statuses';

    /**
     * Execute the console command.
     */
    public function handle(PingService $pingService): int
    {
        $timeout = (int) $this->option('timeout');
        $location = $this->option('location');

        $query = Device::query();
        if ($location) {
            $query->where('location', $location);
        }

        $devices = $query->orderBy('device_name')->get();
        $total = $devices->count();

        if ($total === 0) {
            $this->warn('No devices found to ping.');
            return self::SUCCESS;
        }

        $this->info("Starting ping for {$total} device(s) (Timeout: {$timeout}ms)...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $onlineCount = 0;
        $offlineCount = 0;
        $warningCount = 0;

        foreach ($devices as $device) {
            $res = $pingService->pingDevice($device, $timeout);
            if ($res['status'] === 'online') {
                $onlineCount++;
            } elseif ($res['status'] === 'warning') {
                $warningCount++;
            } else {
                $offlineCount++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Pinged', $total],
                ['Online', $onlineCount],
                ['Warning', $warningCount],
                ['Offline', $offlineCount],
            ]
        );

        $this->info('Ping monitoring completed successfully.');
        return self::SUCCESS;
    }
}
