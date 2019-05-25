<?php

namespace omnilight\scheduling\Tests;

use omnilight\scheduling\Event;
use yii\mutex\Mutex;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function buildCommandData()
    {
        return [
            ['php -i', '/dev/null', "php -i > /dev/null 2>&1 &"],
            ['php -i', '/my folder/foo.log', "php -i > /my folder/foo.log 2>&1 &"],
        ];
    }

    /**
     * @dataProvider buildCommandData
     * @param $command
     * @param $outputTo
     * @param $result
     */
    public function testBuildCommandSendOutputTo($command, $outputTo, $result)
    {
        $event = new Event($this->getMock(Mutex::className()), $command);
        $event->sendOutputTo($outputTo);
        $this->assertSame($result, $event->buildCommand());
    }
}