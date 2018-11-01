<?php
namespace common\servers\example\rule;

/**
 * Class Rule
 * @package common\servers\example\rule
 */
class Rule extends \common\servers\Service
{
    /**
     * @return string
     */
    public function index($name)
    {
        return 'this common\servers\examply\rule\Rule Class to ' . $name;
    }
}