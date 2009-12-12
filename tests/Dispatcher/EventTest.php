<?php

/**
 * Tests Dispatcher_Event class in the Dispatcher module
 *
 * @author Matt Button <matthew@sigswitch.com>
 * @group modules.dispatcher
 * @group testdox
 */
Class Dispatcher_EventTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests the default values of a new instance
	 *
	 * @test
	 */
	public function testNewInstanceHasNoArgumentsAndNotEditable()
	{
		$event = new Dispatcher_Event();

		$this->assertAttributeSame(array(), 'arguments', $event);
		$this->assertAttributeSame(FALSE, 'arguments_mutable', $event);
	}

	/**
	 * We should be able to get read only access to variables through the getter
	 *
	 * @test
	 */
	public function testCanAccessVariablesThroughGetter()
	{
		$event = new Dispatcher_Event(array('bob' => 'dylan'));

		$this->assertSame(FALSE, $event->arguments_mutable);
		$this->assertSame(array('bob' => 'dylan'), $event->arguments);
	}

	/**
	 * The countable interface should allow us to count() arguments
	 * 
	 * @test
	 */
	public function testCountActuallyCountsArguments()
	{
		$event = new Dispatcher_Event;

		$this->assertSame(0, count($event));

		$event = new Dispatcher_Event(array('good' => 'kohana', 'bad' => 'codeigniter', 'ugly' => 'cakephp'));

		$this->assertSame(3, count($event));
	}

	/**
	 * You shouldn't be able to edit arguments of a read only event
	 *
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testCantEditArgumentsOfANonEditableEvent()
	{
		$event = new Dispatcher_Event(array('a' => 'cow', 'jumps' => 'over'));

		$event['the'] = 'moon';
	}

	/**
	 * We should be able to edit arguments of an editable event
	 * 
	 * @test
	 */
	public function testCanChangeArgumentsOfAnEditableEvent()
	{
		$event = new Dispatcher_Event(array('a' => 'cow', 'jumps' => 'over'), TRUE);

		$event['a'] = 'person';

		$this->assertTrue(isset($event['a']));
		$this->assertSame('person', $event['a']);
		$this->assertAttributeSame(array('a' => 'person', 'jumps' => 'over'), 'arguments', $event);
	}

	/**
	 * The arguments that are defined at creation are the only ones allowed
	 * 
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testCannotAddArgumentsOnceEventIsCreated()
	{
		$event = new Dispatcher_Event(array(), TRUE);

		$event['foo'] = 'bar';
	}

	/**
	 * We should not be able to remove arguments, even when they're set as mutable
	 *
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testCannotDeleteAnArgument()
	{
		$event = new Dispatcher_Event(array('a' => 'cow', 'jumps' => 'over'), TRUE);

		unset($event['jumps']);
	}
}