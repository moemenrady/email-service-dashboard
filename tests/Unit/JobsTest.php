<?php

namespace Tests\Unit;

use App\Jobs\CreateEmailAccountJob;
use App\Jobs\CreateForwarderJob;
use App\Services\MxRouteApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobsTest extends TestCase
{
    public function test_create_email_account_job_executes_api_call(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/moemen-team.com/email-accounts' => Http::response([
                'success' => true,
                'data' => [],
            ], 201),
        ]);

        $api = new MxRouteApiService();
        $job = new CreateEmailAccountJob('moemen-team.com', 'testqueued', 'Password123!', 1024, 9600);

        $job->handle($api);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.mxroute.com/domains/moemen-team.com/email-accounts'
                && $request['username'] === 'testqueued';
        });
    }

    public function test_create_forwarder_job_executes_api_call(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/moemen-team.com/forwarders' => Http::response([
                'success' => true,
                'data' => [],
            ], 201),
        ]);

        $api = new MxRouteApiService();
        $job = new CreateForwarderJob('moemen-team.com', 'support', ['help@domain.com']);

        $job->handle($api);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.mxroute.com/domains/moemen-team.com/forwarders'
                && $request['alias'] === 'support';
        });
    }
}
