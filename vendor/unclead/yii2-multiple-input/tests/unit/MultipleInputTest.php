<?php

namespace unclead\multipleinput\tests\unit;

use unclead\multipleinput\tests\unit\data\TestModel;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;

/**
 * Class MultipleInputTest
 * @package unclead\multipleinput\tests\unit
 */
class MultipleInputTest extends TestCase
{
    public function testGuessColumn()
    {
        $model = new TestModel();

        $widget = new MultipleInput([
            'model' => $model,
            'attribute' => 'email',
        ]);

        $expected = [
            ['name' => 'email', 'type' => MultipleInputColumn::TYPE_TEXT_INPUT]
        ];

        $this->assertEquals($expected, $widget->columns);
    }

    public function testGlobalErrorGuessColumn()
    {
        $model = new TestModel();

        $widget = new MultipleInput([
            'model' => $model,
            'attribute' => 'email',
            'enableError' => true,
        ]);

        $expected = [
            ['name' => 'email', 'type' => MultipleInputColumn::TYPE_TEXT_INPUT, 'enableError' => true]
        ];

        $this->assertEquals($expected, $widget->columns);
    }

    public function testInitData()
    {
        $model = new TestModel();

        $dataExample = [
            ['email' => 'test@example.com'],
            ['email' => 'test@example.com'],
        ];

        $widget = new MultipleInput([
            'model' => $model,
            'attribute' => 'email',
            'data' => $dataExample
        ]);

        $this->assertEquals($dataExample, $widget->data);

        $model->email = ['test@example.com'];

        $widget = new MultipleInput([
            'model' => $model,
            'attribute' => 'email',
        ]);

        $this->assertEquals($model->email, $widget->data);
    }
}