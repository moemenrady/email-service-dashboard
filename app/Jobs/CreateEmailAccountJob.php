<?php

namespace App\Jobs;

use App\Exceptions\MxRouteRateLimitException;
use App\Services\MxRouteApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateEmailAccountJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $domain,
        public string $username,
        public string $password,
        public int $quota = 1024,
        public int $limit = 9600
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MxRouteApiService $api): void
    {
        try {
            $api->createEmailAccount(
                domain: $this->domain,
                username: $this->username,
                password: $this->password,
                quota: $this->quota,
                limit: $this->limit
            );

            Log::info("Queued email account created: {$this->username}@{$this->domain}");
        } catch (MxRouteRateLimitException $e) {
            Log::warning("Rate limited while creating {$this->username}@{$this->domain}. Releasing for {$e->getRetryAfter()} seconds.");
            $this->release($e->getRetryAfter());
        }
    }
}
