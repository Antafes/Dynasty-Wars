<?php
/**
 * no namespace, because of the global character of this class!
 * @author Neithan
 */
class DWDateInterval
{
	/**
	 * The given DateInterval
	 * @var DateInterval
	 */
	private $interval;

	/**
	 * @param DateInterval $interval
	 */
	public function __construct(DateInterval $interval)
	{
		$this->interval = $interval;
	}

	/**
	 * Get the seconds of the date interval.
	 * @return int
	 */
	public function getSeconds()
	{
		$seconds = $this->s;
		$seconds += $this->i * 60;
		$seconds += $this->h * 3600;
		$seconds += $this->interval->days * 3600 * 24;

		return ($this->interval->days ? $seconds * -1 : $seconds);
	}

	/**
	 * magic get function
	 * @param String $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->interval->$name;
	}

	/**
	 * magic set function
	 * @param String $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->interval->$name = $value;
	}

	/**
	 * wrapper for DateInterval::format()
	 * @param String $format
	 * @return String
	 */
	public function format($format)
	{
		return $this->interval->format($format);
    }
}