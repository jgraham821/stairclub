<?php

namespace Repository;

use \PDO;

class UserRepsository 
{
	private $_connection;

	public function __construct(PDO $connection = null)
	{
		$this->_connection = $connection;
	}

	public function find($username)
	{
		$stmt = $this->_connection->prepare('
			SELECT *
			FROM user
			WHERE username = ?
		');

		$stmt->bindParam(1, $username);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return new \User($result);
	}

	public function findAll()
	{
		$stmt = $this->_connection->prepare('
			SELECT * FROM user
		');

		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$users = array();
		foreach ($results as $key => $result) {
			$users[] = new \User($result);
		}

		return $users;
	}

	public function save(\User $user)
	{
		if ($user->getId() !== null) {
			return $this->update($user);
		}

		$stmt = $this->_connection->prepare('
			INSERT INTO user
				(username, firstname, lastname, email)
			VALUES
				(?, ?, ?, ?)
		');

		$stmt->bindParam(1, $user->getUsername());
		$stmt->bindParam(2, $user->getFirstname());
		$stmt->bindParam(3, $user->getLastname());
		$stmt->bindParam(4, $user->getEmail());

		return $stmt->execute();
	}

	private function update(\User $user)
	{
		$stmt = $this->_connection->prepare('
			UPDATE user
			SET username = ?,
				firstname = ?,
				lastname = ?,
				email = ?
			WHERE id = ?
		');

		$stmt->bindParam(1, $user->getUsername());
		$stmt->bindParam(2, $user->getFirstname());
		$stmt->bindParam(3, $user->getLastname());
		$stmt->bindParam(4, $user->getEmail());
		$stmt->bindParam(5, $user->getId());

		return $stmt->execute();
	}
}