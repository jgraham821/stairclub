<?php
class Result
{
	private $_id;
	private $_username;
	private $_routeId;
	private $_time;

	public function __construct($username, array $data)
	{
		$this->_username = $username;
		$this->_time     = $data['time'];
		$this->_id       = isset($data['id']) ? $data['id'] : null;
		$this->_routeId  = isset($data['route_id']) ? $data['route_id'] : 1;
		$this->_date     = isset($data['date']) ? $data['date'] : date("Y-m-d H:i:s");
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getRouteId()
	{
		return $this->_routeId;
	}

	public function getUsername()
	{
		return $this->_username;
	}

	public function getTime()
	{
		return $this->_time;
	}

	public function getDate()
	{
		$date = new DateTime($this->_date);
		return $date->format('M j @ g:i:s a');
	}

	public function jsonSerialize() {
        return array(
        	'id'       => $this->getId(),
        	'route_id' => $this->getRouteId(),
        	'username' => $this->getUsername(),
        	'time'     => $this->getTime(),
        	'date'     => $this->getDate()
        );
    }
}