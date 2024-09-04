<?php

/**
 * Class to calculate sunset time
 * In default the current time is used
 */
class Sunset {
    
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
        12 => [],
    ];

    private const HOLIDAY_FULL_DAYS_IN_CHUL = [
        1 => [15, 22],
        7 => [15, 21],
        9 => [6],
        12 => [],
    ];

    private const HOLIDAY_DAYS_END_IN_CHUL = [
        1 => [16, 23],
        7 => [16, 22],
        9 => [7],
        12 => [],
    ];

    private const BOUNDARIES = [
        'north' => 33.3, // Northernmost latitude
        'south' => 29.5, // Southernmost latitude
        'east' => 35.9, // Easternmost longitude
        'west' => 34.2, // Westernmost longitude
    ];

    private const LIGHTING_TIME_BEFORE_SUNSET = 40;
    private const STAR_APPERING_TIME_AFTER_SUNSET = 30;

    /** @var DateTime */
    private $now;
    /** @var int */
    private $dayOfWeek;
    /** @var int */
    private $dayOfMonth;
    /** @var int */
    private $month;
    /** @var int[] */
    private $todaySunset = null;
    /** @var float */
    private $latitude = 31.7683;
    /** @var float */
    private $longitude = 35.2137;
    /** @var string */
    private $timeZoneString = 'Asia/Jerusalem';
    /** @var DateTimeZone */
    private $timeZone;
    /** @var int */
    private $unixCurrentTime;
    /** @var int */
    private $unixSunsetTime;
    /** @var bool */
    private $isOutsideEretzIsrael = false;
    /** @var string */
    public $cloudFlareData = '';

    /**
     * @param DateTime|null $time
     */
    public function __construct(DateTime $time = null) {
        if(
            isset($_SERVER) && 
            isset( $_SERVER['HTTP_CF_TIMEZONE'] ) && 
            !empty( $_SERVER['HTTP_CF_TIMEZONE'] ) &&
            in_array( $_SERVER['HTTP_CF_TIMEZONE'], timezone_identifiers_list() )) {
            $this->timeZoneString = $_SERVER['HTTP_CF_TIMEZONE'];
        }
        $this->timeZone = new DateTimeZone($this->timeZoneString);
        if ($time) {
            $this->now = $time;
        } else {
            $this->now = new DateTime('now', $this->timeZone);
        }
        $this->dayOfWeek = (int)$this->now->format("w");
        $this->dayOfMonth = (int)$this->now->format("j");
        $this->month = (int)$this->now->format("n");
        $this->unixCurrentTime = $this->now->getTimestamp();
        if (isset($_SERVER) && isset($_SERVER['HTTP_CF_IPLATITUDE']) && isset($_SERVER['HTTP_CF_IPLONGITUDE'])) {
            $this->latitude = (float)$_SERVER['HTTP_CF_IPLATITUDE'];
            $this->longitude = (float)$_SERVER['HTTP_CF_IPLONGITUDE'];
            $this->isOutsideEretzIsrael = $this->checkIfIsOutsideEretzIsrael();
        }
        $this->calculateDinamicSunset();
        $this->setCloudFlareData();
    }

    public function setCloudFlareData() {
        // Check if the keys exist before using them
        $timezone = isset($_SERVER['HTTP_CF_TIMEZONE']) ? $_SERVER['HTTP_CF_TIMEZONE'] : 'default_timezone';
        $latitude = isset($_SERVER['HTTP_CF_IPLATITUDE']) ? $_SERVER['HTTP_CF_IPLATITUDE'] : 'default_latitude';
        $longitude = isset($_SERVER['HTTP_CF_IPLONGITUDE']) ? $_SERVER['HTTP_CF_IPLONGITUDE'] : 'default_longitude';
        $this->cloudFlareData = "this timezone string = {$this->timeZoneString} server = {$timezone}, this latitude = {$this->latitude} server = {$latitude}, this longitude = {$this->longitude} server = {$longitude}";
    }

    private function checkIfIsOutsideEretzIsrael() {
        // Check if the latitude is outside the north-south boundaries
        if ($this->latitude > self::BOUNDARIES['north'] || $this->latitude < self::BOUNDARIES['south']) {
            return true;
        }
        // Check if the longitude is outside the east-west boundaries
        if ($this->longitude > self::BOUNDARIES['east'] || $this->longitude < self::BOUNDARIES['west']) {
            return true;
        }
        // The location is inside Eretz Israel
        return false;
    }

    private function calculateDinamicSunset() {
        $sunInfo = date_sun_info( strtotime( $this->now->format("Y-m-d") ), $this->latitude, $this->longitude);
        if ($sunInfo === false || !array_key_exists('sunset', $sunInfo) || $sunInfo['sunset'] === false || $sunInfo['sunset'] === true) {
            $this->setTodayDefaultSunset();
            return false;
        }
        $this->unixSunsetTime = (int)$sunInfo['sunset'];
        return $this->unixSunsetTime;
    }

    public function getTodaySunset() {
        if ($this->todaySunset) {
            return $this->todaySunset;
        }
        return $this->calculateDinamicSunset();
    }

    private function setTodayDefaultSunset() {
        // default sunset time for Jerusalem
        $sunInfo = date_sun_info( strtotime( $this->now->format("Y-m-d") ), 31.7683, 35.2137 );
        if ($sunInfo === false || !array_key_exists('sunset', $sunInfo) || $sunInfo['sunset'] === false || $sunInfo['sunset'] === true) {
            return false;
        }
        $this->unixSunsetTime = (int)$sunInfo['sunset'];
        return $this->unixSunsetTime;
    }

    public function isNowHoliday() {
        [ $hebrewMonth, $hebrewDay ] = $this->tsToHebrew();
        if (!array_key_exists($hebrewMonth, self::HOLIDAY_DAYS_START)) {
            return false;
        }
        // Rosh Hashana first day is anyway holiday
        if ($hebrewMonth == 1 && $hebrewDay == 1) {
            return true;
        }

        if( $this->isOutsideEretzIsrael && in_array($hebrewDay, self::HOLIDAY_FULL_DAYS_IN_CHUL[$hebrewMonth])) {
            return true;
        }

        if (in_array($hebrewDay, self::HOLIDAY_DAYS_START[$hebrewMonth])) {
            return $this->unixCurrentTime >= ($this->unixSunsetTime - self::LIGHTING_TIME_BEFORE_SUNSET * 60 );
            
        } 
        if (
            in_array($hebrewDay, self::HOLIDAY_DAYS_END[$hebrewMonth]) || 
            ($this->isOutsideEretzIsrael && in_array($hebrewDay, self::HOLIDAY_DAYS_END_IN_CHUL[$hebrewMonth])) 
            ) {
            return $this->unixCurrentTime < ($this->unixSunsetTime + self::STAR_APPERING_TIME_AFTER_SUNSET * 60 );
        }
        return false;
    }

    public function isNowShabbat() {
        if ($this->dayOfWeek == 5 ) {
            if( $this->unixCurrentTime >= ($this->unixSunsetTime - self::LIGHTING_TIME_BEFORE_SUNSET * 60 ) ) {
                return true;
            }
        } elseif($this->dayOfWeek == 6 ) {
            if ( $this->unixCurrentTime < ($this->unixSunsetTime + self::STAR_APPERING_TIME_AFTER_SUNSET * 60 ) ) {
                return true;
            }
        }
        return $this->isNowHoliday();
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
	 * @return int[]
	 */
	private function tsToHebrew() {
		# Parse date
		$year = (int)$this->now->format("Y");
		$month = $this->month;
		$day = $this->dayOfMonth;

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
