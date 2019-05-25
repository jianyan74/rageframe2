<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2018
 * @package yii2-date-range
 * @version 1.7.0
 */

namespace kartik\daterange;

use yii\base\Model;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;

/**
 * DateRangeBehavior automatically fills the specified attributes with the parsed date range values.
 *
 * @author Cosmo <daixianceng@gmail.com>
 */
class DateRangeBehavior extends Behavior
{
    /**
     * @var Model the owner model of this behavior.
     */
    public $owner;

    /**
     * @var string the attribute that containing date range value.
     */
    public $attribute = 'date_range';

    /**
     * @var string the attribute that will receive date value formatted by [[dateFormat]]. Required when [[singleDate]]
     * is set to `true`.
     */
    public $dateAttribute;

    /**
     * @var string the attribute that will receive range start value formatted by [[dateStartFormat]]. Required when
     * [[singleDate]] is set to `false`.
     */
    public $dateStartAttribute;

    /**
     * @var string the attribute that will receive range end value formatted by [[dateEndFormat]]. Required when
     * [[singleDate]] is set to `false`.
     */
    public $dateEndAttribute;

    /**
     * @var string|null|false the PHP date format string. It will be used to format the date value.
     * - If set to `null`, this will auto convert the date value into a Unix timestamp.
     * - If set to `false`, there is no formatting action on the date value.
     * @see [[dateAttribute]]
     */
    public $dateFormat;

    /**
     * @var string|null|false the PHP date format string. It will be used to format the range start value.
     * - If set to `null`, this will auto convert the range start value into a Unix timestamp.
     * - If set to `false`, there is no formatting action on the range start value.
     * @see [[dateStartAttribute]]
     */
    public $dateStartFormat;

    /**
     * @var string|null|false the PHP date format string. It will be used to format the range end value.
     * - If set to `null`, this will auto convert the range end value into a Unix timestamp.
     * - If set to `false`, there is no formatting action on the range end value.
     * @see [[dateEndAttribute]]
     */
    public $dateEndFormat;

    /**
     * @var boolean whether the attribute is a single date.
     */
    public $singleDate = false;

    /**
     * @var string the date range separator.
     */
    public $separator;

    /**
     * Parses the given date into a Unix timestamp.
     *
     * @param string $date a date string
     *
     * @return integer|false a Unix timestamp. False on failure.
     */
    protected static function dateToTime($date)
    {
        return strtotime($date);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->singleDate) {
            if (!isset($this->dateAttribute)) {
                throw new InvalidConfigException('The "dateAttribute" property must be specified.');
            }
        } else {
            if (!isset($this->dateStartAttribute) || !isset($this->dateEndAttribute)) {
                throw new InvalidConfigException(
                    'The "dateStartAttribute" and "dateEndAttribute" properties must be specified.'
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Model::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * Handles owner 'afterValidate' event.
     *
     * @param \yii\base\Event $event event instance.
     * @throws InvalidValueException
     */
    public function afterValidate($event)
    {
        if ($this->owner->hasErrors() || $event->name != Model::EVENT_AFTER_VALIDATE) {
            return;
        }
        $dateRangeValue = $this->owner->{$this->attribute};
        if (empty($dateRangeValue)) {
            return;
        }
        if ($this->singleDate) {
            $this->setOwnerAttribute($this->dateAttribute, $this->dateFormat, $dateRangeValue);
        } else {
            $separator = empty($this->separator) ? ' - ' : $this->separator;
            $dates = explode($separator, $dateRangeValue, 2);
            if (count($dates) !== 2) {
                throw new InvalidValueException("Invalid date range: '{$dateRangeValue}'.");
            }
            $this->setOwnerAttribute($this->dateStartAttribute, $this->dateStartFormat, $dates[0]);
            $this->setOwnerAttribute($this->dateEndAttribute, $this->dateEndFormat, $dates[1]);
        }
    }

    /**
     * Evaluates the attribute value and assigns it to the given attribute.
     *
     * @param string $attribute the owner attribute name
     * @param string $dateFormat the PHP date format string
     * @param string $date a date string
     */
    protected function setOwnerAttribute($attribute, $dateFormat, $date)
    {
        if ($dateFormat === false) {
            $this->owner->$attribute = $date;
        } else {
            $timestamp = static::dateToTime($date);
            if ($dateFormat === null) {
                $this->owner->$attribute = $timestamp;
            } else {
                $this->owner->$attribute = $timestamp !== false ? date($dateFormat, $timestamp) : false;
            }
        }
    }
}
