<?php

/**
 * Class to calculate sunset time
 * The sunsets are calculated for Jerusalem by UTC time
 * In default the current time is used
 */
class Sunset {
    private const sunsets = [
        "1-1" => "2:47:13",
        "1-10" => "2:54:14",
        "1-11" => "2:55:04",
        "1-12" => "2:55:55",
        "1-13" => "2:56:47",
        "1-14" => "2:57:39",
        "1-15" => "2:58:32",
        "1-16" => "2:59:25",
        "1-17" => "3:00:18",
        "1-18" => "3:01:12",
        "1-19" => "3:02:06",
        "1-2" => "2:47:56",
        "1-20" => "3:03:00",
        "1-21" => "3:03:54",
        "1-22" => "3:04:49",
        "1-23" => "3:05:44",
        "1-24" => "3:06:39",
        "1-25" => "3:07:34",
        "1-26" => "3:08:29",
        "1-27" => "3:09:24",
        "1-28" => "3:10:19",
        "1-29" => "3:11:14",
        "1-3" => "2:48:41",
        "1-30" => "3:12:09",
        "1-31" => "3:13:03",
        "1-4" => "2:49:26",
        "1-5" => "2:50:12",
        "1-6" => "2:50:59",
        "1-7" => "2:51:46",
        "1-8" => "2:52:35",
        "1-9" => "2:53:24",
        "10-1" => "3:25:17",
        "10-10" => "3:14:03",
        "10-11" => "3:12:51",
        "10-12" => "3:11:40",
        "10-13" => "3:10:29",
        "10-14" => "3:09:19",
        "10-15" => "3:08:10",
        "10-16" => "3:07:01",
        "10-17" => "3:05:53",
        "10-18" => "3:04:46",
        "10-19" => "3:03:40",
        "10-2" => "3:24:00",
        "10-20" => "3:02:35",
        "10-21" => "3:01:31",
        "10-22" => "3:00:27",
        "10-23" => "2:59:25",
        "10-24" => "2:58:24",
        "10-25" => "2:57:23",
        "10-26" => "2:56:24",
        "10-27" => "2:55:26",
        "10-28" => "2:54:29",
        "10-29" => "2:53:33",
        "10-3" => "3:22:44",
        "10-30" => "2:52:39",
        "10-31" => "2:51:45",
        "10-4" => "3:21:28",
        "10-5" => "3:20:13",
        "10-6" => "3:18:58",
        "10-7" => "3:17:43",
        "10-8" => "3:16:29",
        "10-9" => "3:15:16",
        "11-1" => "2:50:53",
        "11-10" => "2:44:03",
        "11-11" => "2:43:25",
        "11-12" => "2:42:48",
        "11-13" => "2:42:13",
        "11-14" => "2:41:39",
        "11-15" => "2:41:07",
        "11-16" => "2:40:37",
        "11-17" => "2:40:08",
        "11-18" => "2:39:41",
        "11-19" => "2:39:15",
        "11-2" => "2:50:02",
        "11-20" => "2:38:51",
        "11-21" => "2:38:29",
        "11-22" => "2:38:09",
        "11-23" => "2:37:50",
        "11-24" => "2:37:33",
        "11-25" => "2:37:18",
        "11-26" => "2:37:04",
        "11-27" => "2:36:53",
        "11-28" => "2:36:43",
        "11-29" => "2:36:35",
        "11-3" => "2:49:12",
        "11-30" => "2:36:29",
        "11-4" => "2:48:24",
        "11-5" => "2:47:37",
        "11-6" => "2:46:51",
        "11-7" => "2:46:07",
        "11-8" => "2:45:24",
        "11-9" => "2:44:43",
        "12-1" => "2:36:24",
        "12-10" => "2:37:04",
        "12-11" => "2:37:17",
        "12-12" => "2:37:32",
        "12-13" => "2:37:48",
        "12-14" => "2:38:06",
        "12-15" => "2:38:26",
        "12-16" => "2:38:48",
        "12-17" => "2:39:11",
        "12-18" => "2:39:35",
        "12-19" => "2:40:02",
        "12-2" => "2:36:21",
        "12-20" => "2:40:29",
        "12-21" => "2:40:58",
        "12-22" => "2:41:29",
        "12-23" => "2:42:01",
        "12-24" => "2:42:34",
        "12-25" => "2:43:09",
        "12-26" => "2:43:45",
        "12-27" => "2:44:22",
        "12-28" => "2:45:01",
        "12-29" => "2:45:40",
        "12-3" => "2:36:20",
        "12-30" => "2:46:21",
        "12-31" => "2:47:03",
        "12-4" => "2:36:21",
        "12-5" => "2:36:24",
        "12-6" => "2:36:28",
        "12-7" => "2:36:35",
        "12-8" => "2:36:43",
        "12-9" => "2:36:52",
        "2-1" => "3:13:58",
        "2-10" => "3:22:01",
        "2-11" => "3:22:53",
        "2-12" => "3:23:45",
        "2-13" => "3:24:37",
        "2-14" => "3:25:28",
        "2-15" => "3:26:19",
        "2-16" => "3:27:10",
        "2-17" => "3:28:00",
        "2-18" => "3:28:50",
        "2-19" => "3:29:39",
        "2-2" => "3:14:53",
        "2-20" => "3:30:29",
        "2-21" => "3:31:17",
        "2-22" => "3:32:06",
        "2-23" => "3:32:54",
        "2-24" => "3:33:42",
        "2-25" => "3:34:29",
        "2-26" => "3:35:16",
        "2-27" => "3:36:03",
        "2-28" => "3:36:49",
        "2-29" => "3:37:35",
        "2-3" => "3:15:47",
        "2-4" => "3:16:41",
        "2-5" => "3:17:35",
        "2-6" => "3:18:29",
        "2-7" => "3:19:22",
        "2-8" => "3:20:15",
        "2-9" => "3:21:08",
        "3-1" => "3:38:21",
        "3-10" => "3:44:58",
        "3-11" => "3:45:41",
        "3-12" => "3:46:23",
        "3-13" => "3:47:06",
        "3-14" => "3:47:48",
        "3-15" => "3:48:29",
        "3-16" => "3:49:11",
        "3-17" => "3:49:53",
        "3-18" => "3:50:34",
        "3-19" => "3:51:15",
        "3-2" => "3:39:06",
        "3-20" => "3:51:56",
        "3-21" => "3:52:37",
        "3-22" => "3:53:18",
        "3-23" => "3:53:58",
        "3-24" => "3:54:39",
        "3-25" => "3:55:19",
        "3-26" => "3:56:00",
        "3-27" => "3:56:40",
        "3-28" => "3:57:20",
        "3-29" => "3:58:01",
        "3-3" => "3:39:51",
        "3-30" => "3:58:41",
        "3-31" => "3:59:21",
        "3-4" => "3:40:36",
        "3-5" => "3:41:20",
        "3-6" => "3:42:04",
        "3-7" => "3:42:48",
        "3-8" => "3:43:32",
        "3-9" => "3:44:15",
        "4-1" => "4:00:01",
        "4-10" => "4:06:04",
        "4-11" => "4:06:45",
        "4-12" => "4:07:26",
        "4-13" => "4:08:07",
        "4-14" => "4:08:47",
        "4-15" => "4:09:28",
        "4-16" => "4:10:10",
        "4-17" => "4:10:51",
        "4-18" => "4:11:32",
        "4-19" => "4:12:13",
        "4-2" => "4:00:42",
        "4-20" => "4:12:55",
        "4-21" => "4:13:36",
        "4-22" => "4:14:18",
        "4-23" => "4:15:00",
        "4-24" => "4:15:42",
        "4-25" => "4:16:24",
        "4-26" => "4:17:06",
        "4-27" => "4:17:48",
        "4-28" => "4:18:30",
        "4-29" => "4:19:12",
        "4-3" => "4:01:22",
        "4-30" => "4:19:54",
        "4-4" => "4:02:02",
        "4-5" => "4:02:42",
        "4-6" => "4:03:23",
        "4-7" => "4:04:03",
        "4-8" => "4:04:43",
        "4-9" => "4:05:24",
        "5-1" => "4:20:37",
        "5-10" => "4:26:58",
        "5-11" => "4:27:40",
        "5-12" => "4:28:22",
        "5-13" => "4:29:03",
        "5-14" => "4:29:45",
        "5-15" => "4:30:26",
        "5-16" => "4:31:07",
        "5-17" => "4:31:48",
        "5-18" => "4:32:29",
        "5-19" => "4:33:09",
        "5-2" => "4:21:19",
        "5-20" => "4:33:49",
        "5-21" => "4:34:29",
        "5-22" => "4:35:08",
        "5-23" => "4:35:47",
        "5-24" => "4:36:25",
        "5-25" => "4:37:03",
        "5-26" => "4:37:41",
        "5-27" => "4:38:18",
        "5-28" => "4:38:54",
        "5-29" => "4:39:30",
        "5-3" => "4:22:02",
        "5-30" => "4:40:05",
        "5-31" => "4:40:39",
        "5-4" => "4:22:44",
        "5-5" => "4:23:26",
        "5-6" => "4:24:09",
        "5-7" => "4:24:51",
        "5-8" => "4:25:33",
        "5-9" => "4:26:15",
        "6-1" => "4:41:13",
        "6-10" => "4:45:40",
        "6-11" => "4:46:05",
        "6-12" => "4:46:29",
        "6-13" => "4:46:52",
        "6-14" => "4:47:13",
        "6-15" => "4:47:34",
        "6-16" => "4:47:53",
        "6-17" => "4:48:11",
        "6-18" => "4:48:28",
        "6-19" => "4:48:44",
        "6-2" => "4:41:46",
        "6-20" => "4:48:58",
        "6-21" => "4:49:11",
        "6-22" => "4:49:23",
        "6-23" => "4:49:33",
        "6-24" => "4:49:42",
        "6-25" => "4:49:49",
        "6-26" => "4:49:55",
        "6-27" => "4:50:00",
        "6-28" => "4:50:03",
        "6-29" => "4:50:05",
        "6-3" => "4:42:18",
        "6-30" => "4:50:05",
        "6-4" => "4:42:50",
        "6-5" => "4:43:21",
        "6-6" => "4:43:50",
        "6-7" => "4:44:19",
        "6-8" => "4:44:47",
        "6-9" => "4:45:14",
        "7-1" => "4:50:04",
        "7-10" => "4:48:45",
        "7-11" => "4:48:29",
        "7-12" => "4:48:10",
        "7-13" => "4:47:51",
        "7-14" => "4:47:30",
        "7-15" => "4:47:07",
        "7-16" => "4:46:43",
        "7-17" => "4:46:17",
        "7-18" => "4:45:50",
        "7-19" => "4:45:21",
        "7-2" => "4:50:02",
        "7-20" => "4:44:51",
        "7-21" => "4:44:20",
        "7-22" => "4:43:47",
        "7-23" => "4:43:12",
        "7-24" => "4:42:36",
        "7-25" => "4:41:59",
        "7-26" => "4:41:20",
        "7-27" => "4:40:40",
        "7-28" => "4:39:58",
        "7-29" => "4:39:15",
        "7-3" => "4:49:57",
        "7-30" => "4:38:31",
        "7-31" => "4:37:46",
        "7-4" => "4:49:52",
        "7-5" => "4:49:44",
        "7-6" => "4:49:36",
        "7-7" => "4:49:25",
        "7-8" => "4:49:13",
        "7-9" => "4:49:00",
        "8-1" => "4:36:59",
        "8-10" => "4:29:03",
        "8-11" => "4:28:05",
        "8-12" => "4:27:05",
        "8-13" => "4:26:05",
        "8-14" => "4:25:03",
        "8-15" => "4:24:01",
        "8-16" => "4:22:57",
        "8-17" => "4:21:53",
        "8-18" => "4:20:48",
        "8-19" => "4:19:42",
        "8-2" => "4:36:11",
        "8-20" => "4:18:35",
        "8-21" => "4:17:27",
        "8-22" => "4:16:19",
        "8-23" => "4:15:10",
        "8-24" => "4:14:00",
        "8-25" => "4:12:49",
        "8-26" => "4:11:38",
        "8-27" => "4:10:26",
        "8-28" => "4:09:13",
        "8-29" => "4:08:00",
        "8-3" => "4:35:22",
        "8-30" => "4:06:46",
        "8-31" => "4:05:32",
        "8-4" => "4:34:31",
        "8-5" => "4:33:39",
        "8-6" => "4:32:46",
        "8-7" => "4:31:52",
        "8-8" => "4:30:57",
        "8-9" => "4:30:01",
        "9-1" => "4:04:17",
        "9-10" => "3:52:46",
        "9-11" => "3:51:28",
        "9-12" => "3:50:10",
        "9-13" => "3:48:51",
        "9-14" => "3:47:32",
        "9-15" => "3:46:13",
        "9-16" => "3:44:54",
        "9-17" => "3:43:35",
        "9-18" => "3:42:16",
        "9-19" => "3:40:57",
        "9-2" => "4:03:02",
        "9-20" => "3:39:38",
        "9-21" => "3:38:19",
        "9-22" => "3:37:00",
        "9-23" => "3:35:41",
        "9-24" => "3:34:22",
        "9-25" => "3:33:03",
        "9-26" => "3:31:45",
        "9-27" => "3:30:27",
        "9-28" => "3:29:09",
        "9-29" => "3:27:51",
        "9-3" => "4:01:46",
        "9-30" => "3:26:34",
        "9-4" => "4:00:30",
        "9-5" => "3:59:14",
        "9-6" => "3:57:57",
        "9-7" => "3:56:40",
        "9-8" => "3:55:22",
        "9-9" => "3:54:04"
    ];
    private const HOLIDAY_DAYS_START = [
        1 => [9, 14, 21],
        7 => [14, 20],
        9 => [5],
        12 => [29]
    ];
    private const HOLIDAY_DAYS_END = [
        1 => [2, 10, 15, 22],
        7 => [15, 21],
        9 => [6],
    ];

    private const LIGHTING_TIME_BEFORE_SUNSET = 40;
    private const STAR_APPERING_TIME_AFTER_SUNSET = 30;

    /** @var DateTime */
    private $now;
    /** @var int */
    private $nowMinute;
    /** @var int */
    private $nowHour;
    /** @var int */
    private $dayOfWeek;
    /** @var int */
    private $dayOfMonth;
    /** @var int */
    private $month;
    /** @var int[] */
    private $todaySunset = null;
    /** @var bool */
    private $isBefore;
    /** @var bool */
    private $isAfter;

    /**
     * @param DateTime|null $time
     */
    public function __construct(DateTime $time = null) {
        if ($time) {
            $this->now = $time;
        } else {
            $this->now = new DateTime('now', new DateTimeZone('UTC'));
        }
        $this->nowHour = (int)$this->now->format("G");
        $this->nowMinute = (int)$this->now->format("i");
        $this->dayOfWeek = (int)$this->now->format("w");
        $this->dayOfMonth = (int)$this->now->format("j");
        $this->month = (int)$this->now->format("n");
        $this->todaySunset = $this->setTodaySunset();
        $this->isBefore = $this->nowHour < 13;
        $this->isAfter = $this->nowHour > 18;
        $this->nowHour -= 12;
    }

    public function getTodaySunset() {
        if ($this->todaySunset) {
            return $this->todaySunset;
        }
        return $this->setTodaySunset();
    }

    private function setTodaySunset() {
        $sunset = explode(":", self::sunsets[$this->month . "-" . $this->dayOfMonth]);
        $this->todaySunset = [(int)$sunset[0], (int)$sunset[1]];
        return $this->todaySunset;
    }

    public function getSunsetHour() {
        return $this->getTodaySunset()[0];
    }

    public function getSunsetMinute() {
        return $this->getTodaySunset()[1];
    }

    private function lightingTime() {
        [$sunsetHour, $sunsetMinute] = $this->getTodaySunset();
        $lightingMinute = (int)$sunsetMinute - self::LIGHTING_TIME_BEFORE_SUNSET;
        $lightingHour = (int)$sunsetHour;
        if ($lightingMinute < 0) {
            $lightingMinute += 60;
            $lightingHour -= 1;
        }
        return [$lightingHour, $lightingMinute];
    }

    private function starApperingTime() {
        [$sunsetHour, $sunsetMinute] = $this->getTodaySunset();
        $starApperingMinute = (int)$sunsetMinute + self::STAR_APPERING_TIME_AFTER_SUNSET;
        $starApperingHour = (int)$sunsetHour;
        if ($starApperingMinute >= 60) {
            $starApperingMinute -= 60;
            $starApperingHour += 1;
        }
        return [$starApperingHour, $starApperingMinute];
    }

    public function isNowHoliday() {
        [ $hebrewMonth, $hebrewDay ] = $this->tsToHebrew(wfTimestampNow());
        if (!array_key_exists($hebrewMonth, self::HOLIDAY_DAYS_START)) {
            return false;
        }
        // Rosh Hashana first day is anyway holiday
        if ($hebrewMonth == 1 && $hebrewDay == 1) {
            return true;
        }
        if (in_array($hebrewDay, self::HOLIDAY_DAYS_START[$hebrewMonth])) {
            if ( $this->isBefore ) {
                return false;
            }
            [$lightingHour,$lightingMinute] = $this->lightingTime();
            return $this->nowHour > $lightingHour || ($this->nowHour == $lightingHour && $this->nowMinute >= $lightingMinute);   
        } 
        if (in_array($hebrewDay, self::HOLIDAY_DAYS_END[$hebrewMonth])) {
            if ( $this->isAfter ) {
                return false;
            }
            [$starApperingHour,$starApperingMinute] = $this->starApperingTime();
            return $this->nowHour < $starApperingHour || ($this->nowHour == $starApperingHour && $this->nowMinute < $starApperingMinute);
        }
        return false;
    }

    public function isNowShabbat() {
        if ($this->dayOfWeek == 5 && !$this->isBefore) {
            [$lightingHour,$lightingMinute] = $this->lightingTime($this->month, $this->dayOfMonth);
            if ($this->nowHour > $lightingHour || ($this->nowHour == $lightingHour && $this->nowMinute >= $lightingMinute)) {
                return true;
            }
        } elseif($this->dayOfWeek == 6 && !$this->isAfter) {
            [$starApperingHour,$starApperingMinute] = $this->starApperingTime($this->month, $this->dayOfMonth);
            if ($this->nowHour < $starApperingHour || ($this->nowHour == $starApperingHour && $this->nowMinute < $starApperingMinute)) {
                return true;
            }
        }
        return $this->isNowHoliday( $this->nowHour, $this->nowMinute );
    }

    /**
	 * Converting Gregorian dates to Hebrew dates.
	 *
	 * Based on a JavaScript code by Abu Mami and Yisrael Hersch
	 * (abu-mami@kaluach.net, http://www.kaluach.net), who permitted
	 * to translate the relevant functions into PHP and release them under
	 * GNU GPL.
	 *
	 * The months are counted from Tishrei = 1. In a leap year, Adar I is 13
	 * and Adar II is 14. In a non-leap year, Adar is 6.
	 *
	 * @param string $ts
	 *
	 * @return int[]
	 */
	private static function tsToHebrew( $ts ) {
		# Parse date
		$year = (int)substr( $ts, 0, 4 );
		$month = (int)substr( $ts, 4, 2 );
		$day = (int)substr( $ts, 6, 2 );

		# Calculate Hebrew year
		$hebrewYear = $year + 3760;

		# Month number when September = 1, August = 12
		$month += 4;
		if ( $month > 12 ) {
			# Next year
			$month -= 12;
			$year++;
			$hebrewYear++;
		}

		# Calculate day of year from 1 September
		$dayOfYear = $day;
		for ( $i = 1; $i < $month; $i++ ) {
			if ( $i == 6 ) {
				# February
				$dayOfYear += 28;
				# Check if the year is leap
				if ( $year % 400 == 0 || ( $year % 4 == 0 && $year % 100 > 0 ) ) {
					$dayOfYear++;
				}
			} elseif ( $i == 8 || $i == 10 || $i == 1 || $i == 3 ) {
				$dayOfYear += 30;
			} else {
				$dayOfYear += 31;
			}
		}

		# Calculate the start of the Hebrew year
		$start = self::hebrewYearStart( $hebrewYear );

		# Calculate next year's start
		if ( $dayOfYear <= $start ) {
			# Day is before the start of the year - it is the previous year
			# Next year's start
			$nextStart = $start;
			# Previous year
			$year--;
			$hebrewYear--;
			# Add days since previous year's 1 September
			$dayOfYear += 365;
			if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
				# Leap year
				$dayOfYear++;
			}
			# Start of the new (previous) year
			$start = self::hebrewYearStart( $hebrewYear );
		} else {
			# Next year's start
			$nextStart = self::hebrewYearStart( $hebrewYear + 1 );
		}

		# Calculate Hebrew day of year
		$hebrewDayOfYear = $dayOfYear - $start;

		# Difference between year's days
		$diff = $nextStart - $start;
		# Add 12 (or 13 for leap years) days to ignore the difference between
		# Hebrew and Gregorian year (353 at least vs. 365/6) - now the
		# difference is only about the year type
		if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
			$diff += 13;
		} else {
			$diff += 12;
		}

		# Check the year pattern, and is leap year
		# 0 means an incomplete year, 1 means a regular year, 2 means a complete year
		# This is mod 30, to work on both leap years (which add 30 days of Adar I)
		# and non-leap years
		$yearPattern = $diff % 30;
		# Check if leap year
		$isLeap = $diff >= 30;

		# Calculate day in the month from number of day in the Hebrew year
		# Don't check Adar - if the day is not in Adar, we will stop before;
		# if it is in Adar, we will use it to check if it is Adar I or Adar II
		$hebrewDay = $hebrewDayOfYear;
		$hebrewMonth = 1;
		$days = 0;
		while ( $hebrewMonth <= 12 ) {
			# Calculate days in this month
			if ( $isLeap && $hebrewMonth == 6 ) {
				# Leap year - has Adar I, with 30 days, and Adar II, with 29 days
				$days = 30;
				if ( $hebrewDay <= $days ) {
					# Day in Adar I
					$hebrewMonth = 13;
				} else {
					# Subtract the days of Adar I
					$hebrewDay -= $days;
					# Try Adar II
					$days = 29;
					if ( $hebrewDay <= $days ) {
						# Day in Adar II
						$hebrewMonth = 14;
					}
				}
			} elseif ( $hebrewMonth == 2 && $yearPattern == 2 ) {
				# Cheshvan in a complete year (otherwise as the rule below)
				$days = 30;
			} elseif ( $hebrewMonth == 3 && $yearPattern == 0 ) {
				# Kislev in an incomplete year (otherwise as the rule below)
				$days = 29;
			} else {
				# Odd months have 30 days, even have 29
				$days = 30 - ( $hebrewMonth - 1 ) % 2;
			}
			if ( $hebrewDay <= $days ) {
				# In the current month
				break;
			} else {
				# Subtract the days of the current month
				$hebrewDay -= $days;
				# Try in the next month
				$hebrewMonth++;
			}
		}

		return [ $hebrewMonth, $hebrewDay ];
	}

    /**
	 * This calculates the Hebrew year start, as days since 1 September.
	 * Based on Carl Friedrich Gauss algorithm for finding Easter date.
	 * Used for Hebrew date.
	 *
	 * @param int $year
	 *
	 * @return int
	 */
	private static function hebrewYearStart( $year ) {
		$a = ( 12 * ( $year - 1 ) + 17 ) % 19;
		$b = ( $year - 1 ) % 4;
		$m = 32.044093161144 + 1.5542417966212 * $a + $b / 4.0 - 0.0031777940220923 * ( $year - 1 );
		if ( $m < 0 ) {
			$m--;
		}
		$Mar = intval( $m );
		if ( $m < 0 ) {
			$m++;
		}
		$m -= $Mar;

		$c = ( $Mar + 3 * ( $year - 1 ) + 5 * $b + 5 ) % 7;
		if ( $c == 0 && $a > 11 && $m >= 0.89772376543210 ) {
			$Mar++;
		} elseif ( $c == 1 && $a > 6 && $m >= 0.63287037037037 ) {
			$Mar += 2;
		} elseif ( $c == 2 || $c == 4 || $c == 6 ) {
			$Mar++;
		}

		$Mar += intval( ( $year - 3761 ) / 100 ) - intval( ( $year - 3761 ) / 400 ) - 24;
		return $Mar;
	}
}
