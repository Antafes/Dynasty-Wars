<?php
class DWDateInterval extends DateInterval
{
	/**
	 * Contains the total number of days between the starting and ending dates.
	 * Had to use a private variable, because the public days isn't writeable
	 * @var int
	 */
	private $dwDays;

	/**
	 * @param int $y
	 * @param int $m
	 * @param int $d
	 * @param int $h
	 * @param int $i
	 * @param int $s
	 * @param int $invert
	 * @param int $days
	 */
	public function __construct($y, $m, $d, $h, $i, $s, $invert = null, $days = null)
	{
		parent::__construct('P'.$y.'Y'.$m.'M'.$d.'DT'.$h.'H'.$i.'M'.$s.'S');

		if ($invert)
			$this->invert = $invert;

		$this->dwDays = $days;
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
		$seconds += $this->dwDays * 3600 * 24;

		return ($this->invert ? $seconds * -1 : $seconds);
	}
}