<?php
namespace Time;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-07-11
 * Time: 16:19
 */

class Time
{
    protected $day;
    protected $hour;
    protected $minute;
    protected $second;
    const DAY_HOUR = 24;
    const HOUR_MINUTE = 60;
    const MINUTE_SECOND = 60;

    public function __construct($day = 0, $hour = 0, $minute = 0, $second = 0)
    {
        $this->setDay($day);
        $this->setHour($hour);
        $this->setMinute($minute);
        $this->setSecond($second);
    }

    public function setDay($day)
    {
        $this->day = is_numeric($day) ? $day : 0;
        return $this;
    }

    public function setHour($hour)
    {
        $this->hour = is_numeric($hour) ? $hour : 0;
        return $this;
    }

    public function setMinute($minute)
    {
        $this->minute = is_numeric($minute) ? $minute : 0;
        return $this;
    }

    public function setSecond($second)
    {
        $this->second = (int)$second;
        return $this;
    }

    /**
     * 获取时间戳
     * @return int
     */
    public function getTimeStamp()
    {
        return $this->getDayTimeStamp() + $this->getHourTimeStamp() + $this->getMinuteStamp() + $this->second;
    }

    public function getMinute()
    {
        return $this->secondMinute($this->getTimeStamp());
    }

    public function getHour()
    {
        return $this->minuteHour($this->getMinute());
    }

    public function getDay()
    {
        return $this->hourDay($this->getHour());
    }

    public function getAutoTime()
    {
        $auto_time = [];
        $timestamp = $this->getTimeStamp();
        $one_day = self::MINUTE_SECOND * self::HOUR_MINUTE * self::DAY_HOUR;
        $one_hour = self::MINUTE_SECOND * self::HOUR_MINUTE;
        $one_minute = self::MINUTE_SECOND;
        if ($timestamp > $one_day) {
            $auto_time['day'] = (int)(string)($timestamp / $one_day);
            $timestamp = $timestamp % $one_day;
        } else {
            $auto_time['day'] = 0;
        }

        if ($timestamp > $one_hour) {
            $auto_time['hour'] = (int)(string)($timestamp / $one_hour);
            $timestamp = $timestamp % $one_hour;
        } else {
            $auto_time['hour'] = 0;
        }

        if ($timestamp > $one_minute) {
            $auto_time['minute'] = (int)(string)($timestamp / $one_minute);
            $timestamp = $timestamp % $one_minute;
        } else {
            $auto_time['minute'] = 0;
        }

        $auto_time['second'] = $timestamp;

        return $auto_time;
    }

    protected function getDayTimeStamp()
    {
        if ($this->day)
            return $this->minuteSecond($this->hourMinute($this->dayHour($this->day)));
        return 0;
    }

    protected function getHourTimeStamp()
    {
        if ($this->hour)
            return $this->minuteSecond($this->hourMinute($this->hour));
        return 0;
    }

    protected function getMinuteStamp()
    {
        if ($this->minute)
            return $this->minuteSecond($this->minute);
        return 0;
    }

    protected function dayHour($day)
    {
        return $day * self::DAY_HOUR;
    }

    protected function hourMinute($hour)
    {
        return $hour * self::HOUR_MINUTE;
    }

    protected function minuteSecond($minute)
    {
        return (int)(string)($minute * self::MINUTE_SECOND);
    }

    protected function secondMinute($second)
    {
        return (int)(string)($second / self::MINUTE_SECOND);
    }

    protected function minuteHour($minute)
    {
        return (int)(string)($minute / self::HOUR_MINUTE);
    }

    protected function hourDay($hour)
    {
        return (int)(string)($hour / self::DAY_HOUR);
    }
}
