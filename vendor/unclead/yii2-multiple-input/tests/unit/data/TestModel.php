<?php

namespace unclead\multipleinput\tests\unit\data;

use yii\base\Model;

/**
 * Class TestModel
 * @package unclead\multipleinput\tests\unit\data
 */
class TestModel extends Model
{
    /**
     * @var string
     */
    public $email;

    public function rules()
    {
        return [
            ['email', 'safe']
        ];
    }
}