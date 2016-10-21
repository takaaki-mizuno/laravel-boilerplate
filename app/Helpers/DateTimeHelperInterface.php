<?php

namespace App\Helpers;

interface DateTimeHelperInterface
{
    /**
     * Get default TimeZone for storage.
     *
     * @return \DateTimeZone
     */
    public function timezoneForStorage();

    /**
     * @param string $timezone
     */
    public function setPresentationTimeZone($timezone = null);

    /**
     */
    public function clearPresentationTimeZone();

    /**
     * @return string
     */
    public function getPresentationTimeZoneString();

    /**
     * Get default TimeZone for showing on the view.
     *
     * @return \DateTimeZone
     */
    public function timezoneForPresentation();

    /**
     * Get Current DateTime.
     *
     * @param \DateTimeZone $timezone
     *
     * @return \Carbon\Carbon
     */
    public function now(\DateTimeZone $timezone = null);

    /**
     * Convert Unix TimeStamp to Carbon(DateTime).
     *
     * @param int           $timeStamp
     * @param \DateTimeZone $timezone
     *
     * @return \Carbon\Carbon
     */
    public function fromTimestamp($timeStamp, \DateTimeZone $timezone = null);

    /**
     * Get DateTime Object from string.
     *
     * @param string        $dateTimeStr
     * @param \DateTimeZone $timezoneFrom
     * @param \DateTimeZone $timezoneTo
     *
     * @return \Carbon\Carbon
     */
    public function dateTime($dateTimeStr, \DateTimeZone $timezoneFrom = null, \DateTimeZone $timezoneTo = null);

    /**
     * @param \DateTime     $dateTime
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function formatDate($dateTime, \DateTimeZone $timezone = null);

    /**
     * @param \DateTime     $dateTime
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function formatTime($dateTime, \DateTimeZone $timezone = null);

    /**
     * @param \DateTime|null $dateTime
     * @param string         $format
     * @param \DateTimeZone  $timezone
     *
     * @return string
     */
    public function formatDateTime($dateTime, $format = 'Y-m-d H:i', \DateTimeZone $timezone = null);

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getDateFormatByLocale($locale = null);

    /**
     * @param string $dateTimeString
     *
     * @return \DateTime
     */
    public function convertToStorageDateTime($dateTimeString);

    /**
     * @param  \DateTime $dateTime
     * @return \DateTime
     */
    public function changeToPresentationTimeZone($dateTime);

}
