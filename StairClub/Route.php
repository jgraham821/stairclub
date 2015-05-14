<?php
class Route
{
	private $_id;
	private $_name;
	private $_startTag;
	private $_endTag;
	private $_description;

	public function __construct($data)
	{
		$this->_id = $data['id'];
		$this->_name = $data['name'];
		$this->_startTag = $data['start_tag'];
		$this->_endTag = $data['end_tag'];
		$this->_description = $data['description'];
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getStartTag()
	{
		return $this->_startTag;
	}

	public function getEndTag()
	{
		return $this->_endTag;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function jsonSerialize()
	{
		return array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'start_tag' => $this->getStartTag(),
			'end_tag' => $this->getEndTag()
		);
	}
}