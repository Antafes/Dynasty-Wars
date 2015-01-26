<?php
/**
 * no namespace, because of the global character of this class!
 * @author Neithan
 */
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
	public function __construct($time = 'now', DateTimeZone $object = null)
	{
		//manually set the timezone to UTC https://bugs.php.net/bug.php?id=52480
		date_default_timezone_set('UTC');

		if (!$object) //has to be here, because no functions as default value allowed
			$object = new DateTimeZone(date_default_timezone_get());

		parent::__construct($time, $object);
		return $this;
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