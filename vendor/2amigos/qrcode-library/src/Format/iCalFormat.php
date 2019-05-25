<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Format;

/**
 * iCal creates a valid iCal format string
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package Da\QrCode\Format
 */
class iCalFormat extends AbstractFormat
{
    /**
     * @var string the event summary
     */
    public $summary;
    /**
     * @var integer the unix timestamp of the start date of the event
     */
    public $startTimestamp;
    /**
     * @var integer the unix timestamp of the end date of the event
     */
    public $endTimestamp;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        $data = [];
        $data[] = "BEGIN:VEVENT";
        $data[] = "SUMMARY:{$this->summary}";
        $data[] = "DTSTART:{$this->unixToiCal($this->startTimestamp)}";
        $data[] = "DTEND:{$this->unixToiCal($this->endTimestamp)}";
        $data[] = "END:VEVENT";

        return implode("\n", $data);
    }

    /**
     * Converts a unix timestamp to iCal format. Timezones are assumed to be included into the timestamp.
     *
     * @param int $value the unix timestamp to convert
     *
     * @return bool|string the formatted date
     */
    protected function unixToiCal($value)
    {
        return date("Ymd\THis\Z", $value);
    }
}
