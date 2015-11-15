<?php namespace App\Helpers;

interface DateTimeHelperInterface
{

    /**
     * Get default TimeZone for storage
     *
     * @return \DateTimeZone
     */
    public function timezoneForStorage();

    /**
     * Get default TimeZone for showing on the view
     *
     * @return \DateTimeZone
     */
    public function timezoneForPresentation();

    /**
     * Get Current DateTime
     *
     * @return \DateTime
     */
    public function now();

    /**
     * Get DateTime Object from string
     *
     * @param  string $dateTimeStr
     * @return \DateTime
     */
    public function dateTime($dateTimeStr);

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public function formatDate($dateTime);

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public function formatTime($dateTime);

    /**
     * @param \DateTime $dateTime
     * @param  string $format
     * @return string
     */
    public function formatDateTime($dateTime, $format = "Y-m-d H:i");

    /**
     * @param  string $dateTimeString
     * @return \DateTime
     */
    public function convertToStorageDateTime($dateTimeString);
}
