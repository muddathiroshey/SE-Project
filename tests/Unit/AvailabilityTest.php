<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use App\Controllers\BidController;
class AvailabilityTest extends TestCase
{
    public function testAvailabilityPayloadReturnsJsonString()
    {$controller = new class extends BidController {
            public function __construct() { }
        };
        $slots = ['Monday 9AM', 'Tuesday 10AM'];
        $result = $controller->availabilityPayload($slots);
        $expectedJson = '["Monday 9AM","Tuesday 10AM"]';
        $this->assertEquals($expectedJson, $result);
    }
}