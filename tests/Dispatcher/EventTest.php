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
	public function testNewInstanceHasNoArguments()
	{
		$event = new Dispatcher_Event();

		$this->assertAttributeSame(array(), 'arguments', $event);
	}

	/**
	 * We should be able to get read only access to variables through the getter
	 *
	 * @test
	 */
	public function testCanAccessVariablesThroughGetter()
	{
		$event = new Dispatcher_Event(array('bob' => 'dylan'));

		$this->assertSame(array('bob' => 'dylan'), $event->arguments());
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
	 * We should be able to edit arguments of an editable event
	 * 
	 * @test
	 */
	public function testCanChangeArgumentsOfAnEvent()
	{
		$event = new Dispatcher_Event(array('a' => 'cow', 'jumps' => 'over'), TRUE);

		$event['a'] = 'person';

		$this->assertTrue(isset($event['a']));
		$this->assertSame('person', $event['a']);
		$this->assertAttributeSame(array('a' => 'person', 'jumps' => 'over'), 'arguments', $event);
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

	/**
	 * A new event should not be flagged as changed
	 *
	 * @test
	 */
	public function testNewEventIsUnchanged()
	{
		$event = new Dispatcher_Event(array('foo' => 'bar'));

		$this->assertFalse($event->changed());
	}

	/**
	 * A new event without any arguments should not be classed as changed
	 *
	 * @test
	 */
	public function testEventWithoutArgumentsIsUnchanged()
	{
		$event = new Dispatcher_Event();

		$this->assertFalse($event->changed());
	}

	/**
	 * Once an argument has been edited, an event should be marked as changed
	 */
	public function testModifyingAnArgumentMarksEventChanged()
	{
		$event = new Dispatcher_Event(array('pie' => 'sky'));

		$event['pie'] = 'lie';

		$this->assertTrue($event->changed());
	}

	/**
	 * We shouldn't be able to access protected attributes (i.e. those starting with an underscore)
	 *
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testCannotAccessProtectedAttributes()
	{
		$event = new Dispatcher_Event;

		$event->_original_arguments;
	}
}