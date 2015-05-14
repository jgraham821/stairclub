<?php

namespace Repository;

use \PDO;

class ResultRepository
{
	private $_connection;

	public function __construct(PDO $connection)
	{
		$this->_connection = $connection;
	}

	public function find($username)
	{
		$stmt = $this->_connection->prepare('
			SELECT *
			FROM result
			JOIN user ON (result.user_id = user.id)
			WHERE username = ?
		');

		$stmt->bindParam(1, $username);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return new \Result($username, $result);
	}

	public function findTop5()
	{
		$sql = '
			SELECT username, count(*) AS total 
			FROM result 
			JOIN user ON (user.id = result.user_id)
			GROUP BY user_id 
			ORDER BY total desc
		';

		$stmt = $this->_connection->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findAll(array $params = array())
	{
		$sql = '
			SELECT user.username, result.*
			FROM result
			JOIN user ON (result.user_id = user.id)
		';

		if (isset($params['username']))
		{
			$sql.= " WHERE user.username = '" . $params['username'] . "'";
		}

		if (isset($params['order_by']))
		{
			$sql.= ' ORDER BY ' . $params['order_by'];
			if (isset($params['order']))
			{
				$sql.= ' ' . $params['order'];
			}
		}

		if (isset($params['limit']))
		{
			$sql.= ' LIMIT ' . $params['limit'];
		}

		$stmt = $this->_connection->prepare($sql);

		$stmt->execute();

		$results = array();
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $result)
		{
			$results[] = new \Result($result['username'], $result);
		}

		return $results;
	}

	public function save(\User $user, \Result $result)
	{
		if ($result->getId() !== null)
		{
			return $this->update($result);
		}

		$stmt = $this->_connection->prepare('
			INSERT INTO result
				(user_id, time, date)
			VALUES
				(?, ?, ?)
		');

		$stmt->bindParam(1, $user->getId());
		$stmt->bindParam(2, $result->getTime());
		$stmt->bindParam(3, $result->getDate());

		return $stmt->execute();
	}
}