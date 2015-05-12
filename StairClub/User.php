<?php
class User implements JsonSerializable
{
	private $_id;
	private $_username;
	private $_firstname;
	private $_lastname;
	private $_email;

	public function __construct(array $data = null)
	{
		if (is_array($data))
		{
			$this->_id        = isset($data['id']) ? $data['id'] : null;
			$this->_username  = $data['username'];
			$this->_firstname = isset($data['firstname']) ? $data['firstname'] : null;
			$this->_lastname  = isset($data['lastname']) ? $data['lastname'] : null;
			$this->_email     = isset($data['email']) ? $data['email'] : null;
		}
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getUsername()
	{
		return $this->_username;
	}

	public function setUsername($username)
	{
		$this->_username = $username;
	}

	public function getFirstname()
	{
		return $this->_firstname;
	}

	public function setFirstname($firstname)
	{
		$this->_firstname = $firstname;
	}

	public function getLastname()
	{
		return $this->_lastname;
	}

	public function setLastname($lastname)
	{
		$this->_lastname = $lastname;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($email)
	{
		$this->_email = $email;
	}

	public function jsonSerialize() {
        return array(
        	'id' => $this->getId(),
        	'username' => $this->getUsername(),
        	'firstname' => $this->getFirstname(),
        	'lastname' => $this->getLastname(),
        	'email' => $this->getEmail()
        );
    }
}