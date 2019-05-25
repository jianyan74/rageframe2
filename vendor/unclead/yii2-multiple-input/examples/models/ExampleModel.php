<?php

namespace unclead\multipleinput\examples\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;

/**
 * Class ExampleModel
 * @package unclead\multipleinput\examples\actions
 */
class ExampleModel extends Model
{
    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';

    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;

    /**
     * @var array
     */
    public $phones;

    /**
     * @var array
     */
    public $schedule;

    /**
     * @var bool
     */
    public $enable;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $questions;

    public function init()
    {
        parent::init();
        $this->emails = [
            'test@test.com',
            'test2@test.com',
            'test3@test.com',
        ];

        $this->schedule = [
            [
                'day'       => '27.02.2015',
                'user_id'   => 31,
                'priority'  => 1,
                'enable'    => 1
            ],
            [
                'day'       => '27.02.2015',
                'user_id'   => 33,
                'priority'  => 2,
                'enable'    => 0
            ],
        ];

        $this->questions = [
            [
                'question' => 'test1',
                'answers' => [
                    [
                        'right' => 0,
                        'answer' => 'test1'
                    ],
                    [
                        'right' => 1,
                        'answer' => 'test2'
                    ]
                ]
            ]
        ];
    }


    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'min' => 5],
            ['emails', 'validateEmails'],
            ['phones', 'validatePhones'],
            ['schedule', 'validateSchedule', 'skipOnEmpty' => false],
            ['questions', 'validateQuestions']
        ];
    }

    public function attributes()
    {
        return [
            'emails',
            'phones',
            'title',
            'schedule',
            'questions'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => $this->attributes()
        ];
    }

    /**
     * Phone number validation
     *
     * @param $attribute
     */
    public function validatePhones($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        $multiple = true;
        if(!is_array($items)) {
            $multiple = false;
            $items = (array) $items;
        }

        foreach ($items as $index => $item) {
            $validator = new NumberValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . ($multiple ? '[' . $index . ']' : '');
                $this->addError($key, $error);
            }
        }
    }

    /**
     * Email validation.
     *
     * @param $attribute
     */
    public function validateEmails($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, $error);
            }
        }
    }

    public function validateSchedule($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) {
            $error = null;
            foreach (['user_id', 'priority'] as $name) {
                $error = null;
                $value = isset($row[$name]) ? $row[$name] : null;
                $requiredValidator->validate($value, $error);
                if (!empty($error)) {
                    $key = $attribute . '[' . $index . '][' . $name . ']';
                    $this->addError($key, $error);
                }
            }
        }
    }

    public function validateQuestions($attribute)
    {
        foreach($this->$attribute as $questionIndex => $question) {
            $this->internalValidateQuestion($questionIndex, $question);
            $this->internalValidateAnswers($questionIndex, $question['answers']);
        }
    }

    private function internalValidateQuestion($questionIndex, $question)
    {
        $requiredValidator = new RequiredValidator();
        $error = null;

        $value = ArrayHelper::getValue($question, 'question', null);
        $requiredValidator->validate($value, $error);
        if (!empty($error)) {
            $key = sprintf('questions[%d][question]', $questionIndex);
            $this->addError($key, $error);
        }
    }

    private function internalValidateAnswers($questionIndex, $answers)
    {
        $requiredValidator = new RequiredValidator();
        $error = null;

        foreach ($answers as $answerIndex => $answer) {
            $error = null;
            $value = ArrayHelper::getValue($answer, 'answer', null);
            $requiredValidator->validate($value, $error);
            if (!empty($error)) {
                $key = sprintf('questions[%d][answers][%d][answer]', $questionIndex, $answerIndex);
                $this->addError($key, $error);
            }
        }
    }
}