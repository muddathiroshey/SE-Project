<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\BidController;

class ValidateBidTest extends TestCase
{
    public function testValidateBidFailsWhenAmountIsLessThan500()
    {
        $controller = new class extends BidController {
            public function __construct() {
            }
        };
        $fakePostData = [
            'cover_letter' => str_repeat('a', 150),
            'bid_total' => 300, 
            'agree_accurate' => '1',
            'agree_qualified' => '1',
            'agree_terms' => '1'
        ];
        $errors = $controller->validateBid($fakePostData);
        $this->assertContains('Bid amount must be at least $500.', $errors);
    }
}