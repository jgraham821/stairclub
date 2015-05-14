<?php

namespace Repository;

use \PDO;

class RouteRepository
{
	private $_connection;

	public function __construct(PDO $connection)
	{
		$this->_connection = $connection;
	}

	public function findAll()
	{
		$sql = '
			SELECT *
			FROM route
		';

		$stmt = $this->_connection->prepare($sql);

		$stmt->execute();

		$results = array();
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $result)
		{
			$results[] = new \Route($result);
		}

		return $results;
	}
}