<?php
class Result implements JsonSerializable
{
	private $_id;
	private $_username;
	private $_time;

	public function __construct($username, array $data)
	{
		$this->_username = $username;
		$this->_time = $data['time'];
		$this->_id   = isset($data['id']) ? $data['id'] : null;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getUsername()
	{
		return $this->_username;
	}

	public function getTime()
	{
		return $this->_time;
	}

	public function jsonSerialize() {
        return array(
        	'id' => $this->getId(),
        	'username' => $this->getUsername(),
        	'time' => $this->getTime(),
        );
    }
}