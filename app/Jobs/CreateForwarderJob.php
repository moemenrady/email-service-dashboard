<?php

namespace App\Jobs;

use App\Exceptions\MxRouteRateLimitException;
use App\Services\MxRouteApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateForwarderJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $domain,
        public string $alias,
        public array $destinations
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MxRouteApiService $api): void
    {
        try {
            $api->createForwarder(
                domain: $this->domain,
                alias: $this->alias,
                destinations: $this->destinations
            );

            Log::info("Queued forwarder created: {$this->alias}@{$this->domain}");
        } catch (MxRouteRateLimitException $e) {
            Log::warning("Rate limited while creating forwarder {$this->alias}@{$this->domain}. Releasing for {$e->getRetryAfter()}s.");
            $this->release($e->getRetryAfter());
        }
    }
}
