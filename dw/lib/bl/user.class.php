<?php
namespace bl\user;

/**
 * Description of UserCls
 *
 * @author Jan
 */
class UserCls {
	private $uid;
	private $nick;
	private $pw;
	private $email;
	private $blocked;
	private $regdate;
	private $game_rank;
	private $rankid;
	private $cid;
	private $description;
	private $last_login;
	private $status;
	private $language;
	private $religion;
	private $deactivated;
	private $cities;

	public function UserCls(){

	}

	function loadByUID($UID){
		$sql = 'SELECT * FROM dw_user WHERE uid = '.\util\mysql\sqlval($UID).'';
		return $this->fill($sql);
	}

	function loadByUserName($userName){
		$sql = 'SELECT * FROM dw_user where nick = '.\util\mysql\sqlval($userName).'';
		return $this->fill($sql);
	}

	private function fill($sql){

		$result = \util\mysql\query($sql);
		if (!$result){
			return false;
		}

		$this->uid = $result['uid'];
		$this->nick = $result['nick'];
		$this->pw = $result['password'];
		$this->email = $result['email'];
		$this->blocked = $result['blocked'];
		$this->regdate = $result['registration_datetime'];
		$this->game_rank = $result['game_rank'];
		$this->rankid = $result['rankid'];
		$this->cid = $result['cid'];
		$this->description = $result['description'];
		$this->last_login =  $result['last_login_datetime'];
		$this->status = $result['status'];
		$this->language = $result['language'];
		$this->religion = $result['religion'];
		$this->deactivated = $result['deactivated'];

		$sql = '
			SELECT
				map_x,
				map_y,
				city,
				maincity
			FROM dw_map
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->cities = \util\mysql\query($sql, true);

		return true;
	}


	public function getUID(){
		return $this->uid;
	}

	public function getNick(){
		return $this->nick;
	}

	public function getPW(){
		return $this->pw;
	}

	public function setPW($newPW, $repeatedPassword = null)
	{
		if (!isset($repeatedPassword) || $newPW === $repeatedPassword)
		{
			$newPW = md5($newPW);
			$PWResult = false;

			\util\mysql\transactionBegin();
			$SQL = '
				UPDATE dw_user
				SET password = '.\util\mysql\sqlval($newPW).'
				WHERE uid = '.\util\mysql\sqlval($this->uid).'
					AND password = '.\util\mysql\sqlval($this->pw).'
			';

			$result = \util\mysql\query($SQL);

			if($result)
			{
				$SQL = '
					SELECT password FROM dw_user
					WHERE uid = '.\util\mysql\sqlval($this->uid).'
				';
				$result = \util\mysql\query($SQL);

				if ($result == $newPW)
				{
					\util\mysql\transactionCommit();
					$PWResult = True;
				}
				else
					\util\mysql\transactionRollback();
			}
			else
			{
				\util\mysql\transactionRollback();
			}

			return $PWResult;
			/*
			* if ($_SESSION['lid'])
			$_SESSION['lid'] = $id;
			else
			setcookie("lid", $id, time()+604800, "", ".dynasty-wars.de");
			*/
		}
		else
			return false;
	}

	public function getEMail(){
		return $this->email;
	}

	public function setMail($newMail){
		$sql = '
			UPDATE dw_user
			SET email = '.\util\mysql\sqlval($newMail).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
				AND email = '.\util\mysql\sqlval($this->email).'
		';
		return (bool)(\util\mysql\query($sql));
	}

	public function getBlocked(){
		return $this->blocked;
	}

	public function setBlocked()
	{
		$sql = '
			UPDATE dw_user
			SET blocked = 1
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->blocked = 1;
		return \util\mysql\query($sql);
	}

	public function unsetBlocked()
	{
		$sql = '
			UPDATE dw_user
			SET blocked = 0
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->blocked = 0;
		return \util\mysql\query($sql);
	}

	public function getRegDate()
	{
		global $lang;

		$date = \DWDateTime::createFromFormat('Y-m-d H:i:s', $this->regdate);

		return $date->format($lang['timeformat']);
	}

	public function getGameRank(){
		return $this->game_rank;
	}

	public function setGameRank($rank)
	{
		$sql = '
			UPDATE dw_user
			SET game_rank = '.\util\mysql\sqlval($rank).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->game_rank = $rank;
		return \util\mysql\query($sql);
	}

	public function getRankID(){
		return $this->rankid;
	}

	public function setRankID($id)
	{
		$sql = '
			UPDATE dw_user
			SET rankid = '.\util\mysql\sqlval($id).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return \util\mysql\query($sql);
	}

	public function getCID(){
		return $this->cid;
	}

	public function setCID($cid)
	{
		$sql = '
			UPDATE dw_user
			SET cid = '.\util\mysql\sqlval($cid).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return \util\mysql\query($sql);
	}

	public function getClanObj(){
		$clan = New ClanCls;

		if ($clan->load($this->cid)){
			return $clan;
		}
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($newValue){
		$sql = '
			UPDATE dw_user
			SET description = '.\util\mysql\sqlval($newValue).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return (bool)(\util\mysql\query($sql));
	}

	public function getLastLogin()
	{
		global $lang;

		$date = \DWDateTime::createFromFormat('Y-m-d H:i:s', $this->last_login);

		return $date->format($lang['timeformat']);
	}

	public function setLastLogin()
	{
		$sql = '
			UPDATE dw_user
			SET last_login_datetime = NOW()
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return \util\mysql\query($sql);
	}

	/**
	 * returns a state of the last login
	 * red = more than 14 days not logged in
	 * yellow = more than 5 days not logged in
	 * green = less or equal than 5 days not logged in
	 * @author Neithan
	 * @return String
	 */
	public function checkLastLogin()
	{
		$lastLogin = new \DWDateTime($this->last_login);
		$tmp = new \DWDateTime();
		$sub1 = $tmp->sub(new DateInterval('P5D'));
		$tmp = new \DWDateTime();
		$sub2 = $tmp->sub(new DateInterval('P14D'));

		if ($lastLogin < $sub2)
			return 'red';
		elseif ($lastLogin < $sub1)
			return 'yellow';
		else
			return 'green';
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status)
	{
		$sql = '
			UPDATE dw_user
			SET status = '.\util\mysql\sqlval($status).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->status = $status;
		return \util\mysql\query($sql);
	}

	public function unsetStatus()
	{
		$sql = '
			UPDATE dw_user
			SET status = ""
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		$this->status = '';
		return \util\mysql\query($sql);
	}

	public function getLanguage()
	{
		$check = \dal\general\checkLanguageIsActive($this->language);

		if (!$check)
			$this->language = \dal\general\getFallbackLanguage();

		return $this->language;
	}

	public function setLanguage($newValue){
		$sql = '
			UPDATE dw_user
			SET language = '.\util\mysql\sqlval($newValue).'
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return (bool)(\util\mysql\query($sql));
	}

	public function getReligion(){
		return $this->religion;
	}

	public function getDeactivated(){
		return $this->deactivated;
	}

	public function setDeactivated(){
		$sql = '
			UPDATE dw_user
			SET deactivated = 1
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return (bool)(\util\mysql\query($sql));
	}

	public function getPoints(){
		$sql = '
			SELECT unit_points, building_points FROM dw_points
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		return \util\mysql\query($sql);
	}


	public function createId(){
		$uidPos = rand(1, 9);
		$uidLen = strlen($this->uid);
		$id = substr($this->pw, 0, $uidPos);
		$id .= $this->uid;
		$id .= substr($this->pw, $uidPos, -3);
		$id .= $uidPos;
		if ($uidLen < 10) {
			$id .= 0;
		}
		$id .= $uidLen;
		$id .= substr($this->pw, -3);
		return $id;
	}

	public function checkId($id)
	{
		$uidPos = substr($id, -6, 1);
		$uidLen = substr($id, -5, 2);
		$pw = substr($id, 0, $uidPos);
		$pw .= substr($id, $uidPos+$uidLen, -6);
		$pw .= substr($id, -3);
		$this->uid = substr($id, $uidPos, $uidLen);

		if ($pw === $this->pw) {
			return 1;
		} else {
			return 0;
		}
	}

	public function getUIDFromId($id) {
		if ($_COOKIE['lid']){
			$id = $_COOKIE['lid'];
		}elseif($_SESSION['lid']){
			$id = $_SESSION['lid'];
		}

		$uidPos = substr($id, -6, 1);
		$uidLen = substr($id, -5, 2);
		return (int)substr($id, $uidPos, $uidLen);
	}

	public function getAllCities()
	{
		return $this->cities;
	}

	public function getMainCity()
	{
		foreach ($this->cities as $city)
			if ($city['maincity'])
				return $city;
	}

	public function setReligionToChristianity()
	{
		//set the new religion
		$sql = '
			UPDATE dw_user
			SET religion = 2
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
		';
		\util\mysql\query($sql);

		//remove all built buddhist temples
		$sql = '
			DELETE FROM dw_buildings
			WHERE uid = '.\util\mysql\sqlval($this->uid).'
				AND kind = 18
		';
		\util\mysql\query($sql);
	}
}