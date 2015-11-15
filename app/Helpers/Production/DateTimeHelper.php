<?php namespace App\Helpers\Production;

use App\Helpers\DateTimeHelperInterface;

class DateTimeHelper implements DateTimeHelperInterface
{

    public function timezoneForStorage()
    {
        return new \DateTimeZone("UTC");
    }

    public function timezoneForPresentation()
    {
        return new \DateTimeZone("Asia/Tokyo");
    }

    public function now()
    {
        return new \DateTime('now', $this->timezoneForStorage());
    }

    public function dateTime($dateTimeStr)
    {
        return new \DateTime($dateTimeStr, $this->timezoneForStorage());
    }

    public function formatDate($dateTime)
    {
        $viewDateTime = clone $dateTime;
        $viewDateTime->setTimeZone($this->timezoneForPresentation());

        return $viewDateTime->format('Y-m-d');
    }

    public function formatTime($dateTime)
    {
        $viewDateTime = clone $dateTime;
        $viewDateTime->setTimeZone($this->timezoneForPresentation());

        return $viewDateTime->format('H:i');
    }

    public function formatDateTime($dateTime, $format = "Y-m-d H:i")
    {
        if (empty($dateTime)) {
            $dateTime = $this->now();
        }
        $viewDateTime = clone $dateTime;
        $viewDateTime->setTimeZone($this->timezoneForPresentation());

        return $viewDateTime->format($format);
    }

    public function convertToStorageDateTime($dateTimeString)
    {
        $viewDateTime = new \DateTime($dateTimeString, $this->timezoneForPresentation());
        $dateTime = clone $viewDateTime;
        $dateTime->setTimeZone($this->timezoneForStorage());

        return $dateTime;
    }

}