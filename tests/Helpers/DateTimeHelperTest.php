<?php

namespace Tests\Helpers;

use Tests\TestCase;

class DateTimeHelperTest extends TestCase
{
    public function testGetInstance()
    {
        /** @var  \App\Helpers\DateTimeHelperInterface $helper */
        $helper = \App::make(\App\Helpers\DateTimeHelperInterface::class);
        $this->assertNotNull($helper);
    }

    public function testTimeZoneForPresentation()
    {
        /** @var  \App\Helpers\DateTimeHelperInterface $helper */
        $helper = \App::make('App\Helpers\DateTimeHelperInterface');

        $this->assertEquals(config('app.default_presentation_timezone'), $helper->getPresentationTimeZoneString());

        $timeZone = $helper->timezoneForPresentation();
        $this->assertEquals(config('app.default_presentation_timezone'), $timeZone->getName());

        $newTimeZone = 'Asia/Bangkok';
        $helper->setPresentationTimeZone($newTimeZone);
        $this->assertEquals($newTimeZone, $helper->getPresentationTimeZoneString());

        $timeZone = $helper->timezoneForPresentation();
        $this->assertEquals($newTimeZone, $timeZone->getName());

        $now = $helper->now();

        $bangkokNow = clone $now;
        $bangkokNow->setTimezone($timeZone);

        $this->assertEquals($bangkokNow->format('Y-m-d H:i:s'), $helper->formatDateTime($now, 'Y-m-d H:i:s'));
        $this->assertEquals($bangkokNow->format('Y-m-d'), $helper->formatDate($now));
        $this->assertEquals($bangkokNow->format('H:i'), $helper->formatTime($now));

        $helper->clearPresentationTimeZone();
        $this->assertEquals(config('app.default_presentation_timezone'), $helper->getPresentationTimeZoneString());

        $timeZone = $helper->timezoneForPresentation();
        $this->assertEquals(config('app.default_presentation_timezone'), $timeZone->getName());

        $defaultNow = clone $now;
        $defaultNow->setTimezone($timeZone);

        $this->assertEquals($defaultNow->format('Y-m-d H:i:s'), $helper->formatDateTime($now, 'Y-m-d H:i:s'));
        $this->assertEquals($defaultNow->format('Y-m-d'), $helper->formatDate($now));
        $this->assertEquals($defaultNow->format('H:i'), $helper->formatTime($now));

        $fromTimestamp = $helper->fromTimestamp($now->timestamp);
        $this->assertEquals($defaultNow->format('Y-m-d'), $helper->formatDate($fromTimestamp));
        $this->assertEquals($defaultNow->format('Y-m-d'), $helper->formatDate($fromTimestamp));
        $this->assertEquals($defaultNow->format('H:i'), $helper->formatTime($fromTimestamp));
    }

    public function testTimeZoneForStorage()
    {
        /** @var  \App\Helpers\DateTimeHelperInterface $helper */
        $helper = \App::make('App\Helpers\DateTimeHelperInterface');

        $timeZone = $helper->timezoneForStorage();
        $this->assertEquals(config('app.timezone'), $timeZone->getName());

        $now = $helper->now();
        $this->assertEquals($timeZone->getName(), $now->getTimezone()->getName());

        $time = $helper->dateTime('2018-01-01 10:10:10');
        $this->assertEquals($timeZone->getName(), $time->getTimezone()->getName());

        $newTimeZone = 'Asia/Bangkok';
        $time = $helper->dateTime('2018-01-01 10:10:10', new \DateTimeZone($newTimeZone), new \DateTimeZone($newTimeZone));
        $this->assertEquals($newTimeZone, $time->getTimezone()->getName());
    }
}
