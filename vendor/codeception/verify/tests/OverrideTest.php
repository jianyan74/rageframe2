<?php
include __DIR__.'/../vendor/autoload.php';

class OverrideTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        
    }

    protected function tearDown()
    {
        \Codeception\Verify::$override = false;
    }
    
    public function testVerifyCanBeOverridden()
    {
        \Codeception\Verify::$override = MyVerify::class;
        $this->assertInstanceOf(MyVerify::class, verify(null));
    }

}

class MyVerify extends \Codeception\Verify {

}