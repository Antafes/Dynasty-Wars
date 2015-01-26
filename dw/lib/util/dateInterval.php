<?php
/**
 * no namespace, because of the global character of this class!
 * @author Neithan
 */
class DWDateInterval extends DateInterval
{
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
}