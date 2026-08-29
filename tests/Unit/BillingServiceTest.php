<?php

namespace Tests\Unit;

use App\Services\Billing\BillingService;
use App\Services\Billing\WalletService;
use PHPUnit\Framework\TestCase;

class BillingServiceTest extends TestCase
{
    public function test_normalize_credentials_accepts_array_and_json_string_values(): void
    {
        $service = new BillingService(new WalletService());
        $method = new \ReflectionMethod(BillingService::class, 'normalizeCredentials');
        $method->setAccessible(true);

        $this->assertSame(
            ['username' => 'admin', 'password' => 'secret'],
            $method->invoke($service, ['username' => 'admin', 'password' => 'secret'])
        );

        $this->assertSame(
            ['username' => 'admin', 'password' => 'secret'],
            $method->invoke($service, '{"username":"admin","password":"secret"}')
        );

        $this->assertSame([], $method->invoke($service, null));
    }

}
