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

	public function findAll()
	{
		$stmt = $this->_connection->prepare('
			SELECT user.username, result.*
			FROM result
			JOIN user ON (result.user_id = user.id)
		');

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
				(user_id, time)
			VALUES
				(?, ?)
		');

		$stmt->bindParam(1, $user->getId());
		$stmt->bindParam(2, $result->getTime());

		return $stmt->execute();
	}
}