<?php

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
		$sql = 'SELECT * FROM dw_user WHERE uid="'.mysql_real_escape_string($UID).'"';
		return $this->fill($sql);
	}

	function loadByUserName($userName){
		$sql = 'SELECT * FROM dw_user where nick="'.mysql_real_escape_string($userName).'"';
		return $this->fill($sql);
	}

	private function fill($sql){

		$result = lib_util_mysqlQuery($sql);
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
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->cities = lib_util_mysqlQuery($sql, true);

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

	public function setPW($newPW){
		$newPW = md5($newPW);
		$PWResult = false;

		lib_util_mysql_TransactionBegin();
		$SQL = 'UPDATE dw_user SET password = "'.mysql_real_escape_string($newPW).'"
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"
			AND password = "'.mysql_real_escape_string($this->pw).'"';

		$result = lib_util_mysqlQuery($SQL);

		if($result){
			$SQL = 'SELECT password FROM dw_user WHERE uid = "'.mysql_real_escape_string($this->uid).'"';
			$result = lib_util_mysqlQuery($SQL);

			if ($result == $newPW){
			lib_util_mysql_TransactionCommit();
			$PWResult = True;
			}else{
			lib_util_mysql_TransactionRollback();
			}
		}else{
			lib_util_mysql_TransactionRollback();
		}
		return $PWResult;
		/*
		* if ($_SESSION['lid'])
		$_SESSION['lid'] = $id;
		else
		setcookie("lid", $id, time()+604800, "", ".dynasty-wars.de");
		*/
	}

	public function getEMail(){
		return $this->email;
	}

	public function setMail($newMail){
		$sql = 'UPDATE dw_user SET email = "'.mysql_real_escape_string($newMail).'"
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"
			AND email = "'.mysql_real_escape_string($this->email).'"';
		return (bool)(lib_util_mysqlQuery($sql));
	}

	public function getBlocked(){
		return $this->blocked;
	}

	public function setBlocked()
	{
		$sql = '
			UPDATE dw_user
			SET blocked = 1
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->blocked = 1;
		return lib_util_mysqlQuery($sql);
	}

	public function unsetBlocked()
	{
		$sql = '
			UPDATE dw_user
			SET blocked = 0
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->blocked = 0;
		return lib_util_mysqlQuery($sql);
	}

	public function getRegDate(){
		return $this->regdate;
	}

	public function getGameRank(){
		return $this->game_rank;
	}

	public function setGameRank($rank)
	{
		$sql = '
			UPDATE dw_user
			SET game_rank = '.mysql_real_escape_string($rank).'
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->game_rank = $rank;
		return lib_util_mysqlQuery($sql);
	}

	public function getRankID(){
		return $this->rankid;
	}

	public function setRankID($id)
	{
		$sql = '
			UPDATE dw_user
			SET rankid = '.mysql_real_escape_string($id).'
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		return lib_util_mysqlQuery($sql);
	}

	public function getCID(){
		return $this->cid;
	}

	public function setCID($cid)
	{
		$sql = '
			UPDATE dw_user
			SET cid = '.mysql_real_escape_string($cid).'
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		return lib_util_mysqlQuery($sql);
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

	public function setDescription($NewValue){
		$sql = 'UPDATE dw_user SET email = "'.mysql_real_escape_string($NewValue).'"
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"';
		return (bool)(lib_util_mysqlQuery($sql));
	}

	public function getLastLogin(){
		return $this->last_login;
	}

	public function setLastLogin()
	{
		$sql = '
			UPDATE dw_user
			SET last_login_datetime = NOW()
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		return lib_util_mysqlQuery($sql);
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
		$lastLogin = new DateTime($this->last_login);
		$interval5 = new DateInterval('P5D');
		$interval14 = new DateInterval('P14D');
		$tmp = new DateTime();
		$sub1 = $tmp->sub($interval5);
		$tmp = new DateTime();
		$sub2 = $tmp->sub($interval14);

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
			SET status = '.mysql_real_escape_string($status).'
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->status = $status;
		return lib_util_mysqlQuery($sql);
	}

	public function unsetStatus()
	{
		$sql = '
			UPDATE dw_user
			SET status = ""
			WHERE uid = '.mysql_real_escape_string($this->uid).'
		';
		$this->status = '';
		return lib_util_mysqlQuery($sql);
	}

	public function getLanguage()
	{
		$check = lib_dal_general_checkLanguageIsActive($this->language);

		if (!$check)
			$this->language = lib_dal_general_getFallbackLanguage();

		return $this->language;
	}

	public function setLanguage($NewValue){
		$sql = 'UPDATE dw_user SET language = "'.mysql_real_escape_string($NewValue).'"
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"';
		return (bool)(lib_util_mysqlQuery($sql));
	}

	public function getReligion(){
		return $this->religion;
	}

	public function getDeactivated(){
		return $this->deactivated;
	}

	public function setDeactivated(){
		$sql = 'UPDATE dw_user SET deactivated = 1
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"';
		return (bool)(lib_util_mysqlQuery($sql));
	}

	public function getPoints(){
		$sql = 'SELECT unit_points, building_points FROM dw_user
			WHERE uid = "'.mysql_real_escape_string($this->uid).'"';
		return lib_util_mysqlQuery($sql);
	}


	public function createId(){
		$uidPos = rand(1, 9);
		$uidLen = strlen($this->uid);
		$id = substr($this->pw, 0, $uidPos);
		$id .= $uid;
		$id .= substr($this->pw, $uidPos, -3);
		$id .= $uidPos;
		if ($uidLen < 10) {
			$id .= 0;
		}
		$id .= $uidLen;
		$id .= substr($this->pw, -3);
		return $id;
	}

	public function checkId(){
		$uidPos = substr($id, -6, 1);
		$uidLen = substr($id, -5, 2);
		$pw = substr($id, 0, $uidPos);
		$pw .= substr($id, $uidPos+$uidLen, -6);
		$pw .= substr($id, -3);
		$uid = substr($id, $uidPos, $uidLen);
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
}
?>
