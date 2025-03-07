<?php

namespace App\Traits;

use Carbon\Carbon;
use DateTime;

trait DateFormat
{
    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public function formatDate(string $date, string $format = 'unix'): string
    {
        if ($format == 'unix') {
            return Carbon::parse($date)->unix();
        } else {
            return Carbon::parse($date)->format($format);
        }
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public function formatTimestampUnix(string $date, string $format = 'm/d/Y'): string
    {
        return Carbon::createFromTimestamp($date)->format($format);
    }

    /**
     * @param string $date
     * @return string
     */
    public function diffForHumans(string $date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }

    /**
     * @param string $date
     * @return string
     */
    public function remainingDays(string $date): string
    {
        return Carbon::now()->diffInDays($date);
    }

    public function isValidDate($date, $format = 'm/d/Y')
    {
        $dateTimeObject = DateTime::createFromFormat($format, $date);
        if ($dateTimeObject !== false) {
            return true;
        };
        return false;
    }

    public function isAfterDate(string $dateValue, string $valueCompare)
    {
        return strtotime($dateValue) <= strtotime($valueCompare);
    }

    public function isBeforeDate(string $dateValue, string $valueCompare)
    {
        return strtotime($dateValue) >= strtotime($valueCompare);
    }

    public function formatToYearMonthDay(string $date, string $format = 'Y/m/d')
    {
        return Carbon::createFromFormat('m/d/Y', $date)->format($format);
    }
}
