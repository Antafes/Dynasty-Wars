<?php
class DWDateTime extends DateTime
{
	/**
	 * default time format
	 * @var String
	 */
	private $defaultFormat = 'Y-m-d H:i:s';

	/**
	 * overridden constructor
	 * @param String $time
	 * @param DateTimeZone $object
	 * @return DWDateTime
	 */
	public function __construct($time = 'now', $object = null)
	{
		if (!$object) //has to be here, because no functions as default value allowed
			$object = new DateTimeZone(date_default_timezone_get());

		parent::__construct($time, $object);
		return $this;
	}

	/**
	 * adjusted diff method that returns a DWDateInterval object
	 * @param DateTime $datetime
	 * @return DWDateInterval
	 */
	public function diff(DateTime $datetime)
	{
		$diff = parent::diff($datetime);
		return new DWDateInterval($diff);
	}

	/**
	 * overridden method to return a DWDateTime object
	 * @param String $format
	 * @param String $time
	 * @param DateTimeZone $object
	 * @return DWDateTime
	 */
	public static function createFromFormat($format, $time, DateTimeZone $object = null)
	{
		if (!$object) //has to be here, because no functions as default value allowed
			$object = new DateTimeZone(date_default_timezone_get());

		$date = parent::createFromFormat($format, $time, $object);

		return new DWDateTime($date->format('Y-m-d H:i:s'));
	}

	/**
	 * overridden method having a default format
	 * @param String $format
	 * @return String
	 */
	public function format($format = null)
	{
		return parent::format($format ? $format : $this->defaultFormat);
	}
}