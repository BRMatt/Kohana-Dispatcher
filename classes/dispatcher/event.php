<?php

/**
 * Dispatcher event
 *
 * Encapsulates event variables
 *
 * @author Matt Button <matthew@sigswitch.com>
 */
Class Dispatcher_Event Implements ArrayAccess, Countable
{
	/**
	 * Arguments for this event
	 * 
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * Flag for whether or not arguments are editable by callbacks
	 * Should only be settable by constructor
	 * @var bool
	 */
	protected $arguments_mutable = FALSE;

	/**
	 * Constructor
	 *
	 * @param array $arguments Arguments for this event
	 */
	public function __construct(array $arguments = array(), $arguments_mutable = FALSE)
	{
		$this->arguments			= $arguments;
		$this->arguments_mutable	= (bool) $arguments_mutable;
	}

	/**
	 * Magic getter
	 *
	 * We pass by reference because php has a thing about not allowing you to
	 * modify arrays returned by __get()
	 *
	 * @see http://bugs.php.net/bug.php?id=39449
	 * @param  string $var Name of var to get
	 * @return mixed       Variable's value
	 */
	public function __get($var)
	{
		return $this->$var;
	}

	/**
	 * Implementation of Countable::count()
	 *
	 * Counts the number of attributes
	 *
	 * @return integer
	 */
	public function count()
	{
		return count($this->arguments);
	}

	/**
	 * Implements ArrayAccess::offsetExists()
	 *
	 * Checks that offset exists in $this->arguments
	 *
	 * @param  string|integer $offset Offset to check
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->arguments[$offset]);
	}

	/**
	 * Implements ArrayAccess::offsetGet()
	 *
	 * Checks that offset exists in $this->arguments
	 *
	 * @param  string|integer $offset Argument to get value of
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->arguments[$offset];
	}

	/**
	 * Implements ArrayAccess::offsetSet()
	 *
	 * Sets an attribute
	 *
	 * @param string|integer  $offset  Index
	 * @param mixed           $value   New value
	 */
	public function offsetSet($offset, $value)
	{
		if( ! $this->arguments_mutable)
		{
			throw new Kohana_Exception('Cannot modify argument :arg of read only event', array(':arg' => $offset));
		}

		if( ! isset($this->arguments[$offset]))
		{
			throw new Kohana_Exception('Cannot add arguments to an event once it has been created');
		}

		$this->arguments[$offset] = $value;
	}

	/**
	 * Implements ArrayAccess::offsetUnset()
	 *
	 * Should not work, we don't want to allow anyone to delete a argument
	 *
	 * @param string|integer $offset
	 */
	public function offsetUnset($offset)
	{
		throw new Kohana_Exception('You cannot remove arguments from an event');
	}
}