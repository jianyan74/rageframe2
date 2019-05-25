<?php

/**
 * Class CurlTest
 */
class CurlTest extends \Codeception\Test\Unit
{
    // ################################################### Class methods ###############################################

    /**
     * No sense test
     */
    public function testInit()
    {
        $curl = new linslin\yii2\curl\Curl();
        expect($curl instanceof linslin\yii2\curl\Curl)->equals(true);
    }
}

