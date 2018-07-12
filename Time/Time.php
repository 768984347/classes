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
    const DAY_HOUR = 24; //1 day = 24 hour
    const HOUR_MINUTE = 60; // 1 hour = 60 minute
    const MINUTE_SECOND = 60; // 1 minute = 60 second

    public function __construct($day = 0, $hour = 0, $minute = 0, $second = 0)
    {
        $this->setDay($day);
        $this->setHour($hour);
        $this->setMinute($minute);
        $this->setSecond($second);
    }

    /**
     * 设置天
     * @param $day
     * @return $this
     */
    public function setDay($day)
    {
        $this->day = is_numeric($day) ? $day : 0;
        return $this;
    }

    /**
     * 设计小时
     * @param $hour
     * @return $this
     */
    public function setHour($hour)
    {
        $this->hour = is_numeric($hour) ? $hour : 0;
        return $this;
    }

    /**
     * 设置分钟
     * @param $minute
     * @return $this
     */
    public function setMinute($minute)
    {
        $this->minute = is_numeric($minute) ? $minute : 0;
        return $this;
    }

    /**
     * 设置秒
     * @param $second
     * @return $this
     */
    public function setSecond($second)
    {
        $this->second = (int)$second;
        return $this;
    }

    /**
     * 获取所有属性转换成秒的总和
     * @return int
     */
    public function getSecond()
    {
        return $this->getDaySecond() + $this->getHourSecond() + $this->getMinuteSecond() + $this->second;
    }

    /**
     * 获取当前对象所有属性秒数总和转换成分钟
     * @return int
     */
    public function getMinute()
    {
        return $this->secondMinute($this->getSecond());
    }

    /**
     * 获取当前对象所有属性秒数总和转换成小时
     * @return int
     */
    public function getHour()
    {
        return $this->minuteHour($this->getMinute());
    }

    /**
     * 获取当前对象所有属性秒数的总和转换成天
     * @return int
     */
    public function getDay()
    {
        return $this->hourDay($this->getHour());
    }

    /**
     * 获取当前对象所有属性秒数的总和转换成day,hour,minute,second的整数顺序
     * @example
     *     input:  1.5 day
     *     output: ['day' => 1, 'hour' => 12, 'minute' => 0, 'second' => 0]
     * @return array
     */
    public function getAutoTime()
    {
        $auto_time = [];
        $second_sum = $this->getSecond();
        $one_day = self::MINUTE_SECOND * self::HOUR_MINUTE * self::DAY_HOUR;
        $one_hour = self::MINUTE_SECOND * self::HOUR_MINUTE;
        $one_minute = self::MINUTE_SECOND;
        if ($second_sum >= $one_day) {
            $auto_time['day'] = (int)(string)($second_sum / $one_day);
            $second_sum = $second_sum % $one_day;
        } else {
            $auto_time['day'] = 0;
        }

        if ($second_sum >= $one_hour) {
            $auto_time['hour'] = (int)(string)($second_sum / $one_hour);
            $second_sum = $second_sum % $one_hour;
        } else {
            $auto_time['hour'] = 0;
        }

        if ($second_sum >= $one_minute) {
            $auto_time['minute'] = (int)(string)($second_sum / $one_minute);
            $second_sum = $second_sum % $one_minute;
        } else {
            $auto_time['minute'] = 0;
        }

        $auto_time['second'] = $second_sum;

        return $auto_time;
    }

    /**
     * 获取当前day属性转换成秒
     * @return int
     */
    protected function getDaySecond()
    {
        if ($this->day)
            return $this->minuteSecond($this->hourMinute($this->dayHour($this->day)));
        return 0;
    }

    /**
     * 获取当前hour属性转换成秒
     * @return int
     */
    protected function getHourSecond()
    {
        if ($this->hour)
            return $this->minuteSecond($this->hourMinute($this->hour));
        return 0;
    }

    /**
     * 获取当前minute属性转换成秒
     * @return int
     */
    protected function getMinuteSecond()
    {
        if ($this->minute)
            return $this->minuteSecond($this->minute);
        return 0;
    }

    /**
     * 天转小时
     * @param $day
     * @return float|int
     */
    protected function dayHour($day)
    {
        return $day * self::DAY_HOUR;
    }

    /**
     * 小时转分钟
     * @param $hour
     * @return float|int
     */
    protected function hourMinute($hour)
    {
        return $hour * self::HOUR_MINUTE;
    }

    /**
     * 分钟转秒
     * @param $minute
     * @return int
     */
    protected function minuteSecond($minute)
    {
        return (int)(string)($minute * self::MINUTE_SECOND);
    }

    /**
     * 秒转分钟
     * @param $second
     * @return int
     */
    protected function secondMinute($second)
    {
        return (int)(string)($second / self::MINUTE_SECOND);
    }

    /**
     * 分钟转小时
     * @param $minute
     * @return int
     */
    protected function minuteHour($minute)
    {
        return (int)(string)($minute / self::HOUR_MINUTE);
    }

    /**
     * 小时转换天
     * @param $hour
     * @return int
     */
    protected function hourDay($hour)
    {
        return (int)(string)($hour / self::DAY_HOUR);
    }
}
