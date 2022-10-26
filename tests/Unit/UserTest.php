<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDocumentShouldBeFormatted()
    {
        $user = new User([
            'document_id' => 12312312312
        ]);
        
        $this->assertSame('123.123.123-12', $user->document_id);
    }
}
