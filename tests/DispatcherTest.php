<?php

/**
 * Tests Dispatcher class in the Dispatcher module
 *
 * @author Matt Button <matthew@sigswitch.com>
 * @group modules.dispatcher
 * @group testdox
 */
Class DispatcherTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Asserts that instance() returns the global instance
	 *
	 * As a side note, this test actually came in useful when I left off the static
	 * attribute for the variable holding the instance!
	 *
	 * @test
	 * @covers Dispatcher::instance
	 */
	public function testInstanceReturnsGlobalInstanceOfDispatcher()
	{
		$instance1 = Dispatcher::instance();

		$instance2 = Dispatcher::instance();

		$this->assertSame($instance1, $instance2);
	}

	/**
	 * Factory instance should be different from the global instance
	 *
	 * @test
	 * @covers Dispatcher::instance
	 * @covers Dispatcher::factory
	 */
	public function testFactoryReturnsAnIndividualInstance()
	{
		$instance = Dispatcher::instance();

		$factory = Dispatcher::factory();

		$this->assertNotSame($factory, $instance);
	}

	/**
	 * Event should return a valid instance of Dispatcher_Event, and said instance
	 * should also have received the parameters passed to event()
	 *
	 * @test
	 */
	public function testEventReturnsAValidInstanceOfDispatcherEvent()
	{
		$args = array('my' => 'arguments');
		$event = Dispatcher::event($args, TRUE);

		$this->assertType('Dispatcher_Event', $event);
	}

	/**
	 * A new dispatcher instance should not have any listeners
	 *
	 * @test
	 */
	public function testNewDispatcherInstanceDoesNotHaveAnyListeners()
	{
		$instance = new Dispatcher;

		$this->assertSame(array(), $instance->listeners);
		$this->assertAttributeSame(array(), 'listeners', $instance);
	}

	/**
	 * Dispatcher::register_listener() should regisiter a listener in the listeners
	 * memeber variable
	 *
	 * @test
	 */
	public function testRegisiterListenerMethodAddsAListener()
	{
		$instance = new Dispatcher();

		$instance->register_listener('some_event', 'my_function');

		$this->assertSame(array('some_event' => array('my_function')), $instance->listeners);
		$this->assertAttributeSame(array('some_event' => array('my_function')), 'listeners', $instance);
	}

	/**
	 * Callbacks aren't optional and an exception should be thrown if one isn't
	 * provided
	 *
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testRegisterListenerThrowsExceptionForEmptyFunctionCallback()
	{
		$instance = new Dispatcher();

		$instance->register_listener('random', '');
	}

	/**
	 * Same as the previous callback not empty test, except this tests arrays
	 *
	 * @test
	 * @expectedException Kohana_Exception
	 */
	public function testRegisterListenerThrowsExceptionForEmptyClassCallback()
	{
		$instance = new Dispatcher();

		$instance->register_listener('random', array());
	}

	/**
	 * Tests that trigger_event calls a callback with arguments
	 *
	 * @test
	 * @covers Dispatcher::trigger_event
	 */
	public function testTriggerEventCallsCallbackWithArguments()
	{
		$mock = $this->getMock('Mock_Callback', array('call_me'));

		$mock->expects($this->once())
				->method('call_me')
				// Have a look in PHPUnit_Framework_Assert for more
				->with($this->equalTo('random.pre_forge'), $this->isInstanceOf('Dispatcher_Event'));



		$mock2 = $this->getMock('Mock_Callback', array('maybe'));

		$mock2->expects($this->once())
				->method('maybe')
				// Have a look in PHPUnit_Framework_Assert for more
				->with($this->equalTo('random.pre_forge'), $this->isInstanceOf('Dispatcher_Event'));

		$dispatcher = new Dispatcher();

		$dispatcher->register_listener('random.pre_forge', array($mock, 'call_me'));
		$dispatcher->register_listener('random.pre_forge', array($mock2, 'maybe'));

		$dispatcher->trigger_event('random.pre_forge', Dispatcher::event(array('random' => 42)));
	}

	/**
	 * Setting $halt_on_success (2nd param) to true should halt the execution loop
	 *
	 * @test
	 */
	public function testTriggerHaltsEventCallbackLoopWhenHaltOnSuccessIsTrue()
	{
		$mock = $this->getMock('Mock_Callback', array('call_me'));

		$mock->expects($this->once())
				->method('call_me')
				// Have a look in PHPUnit_Framework_Assert for more
				->with($this->equalTo('random.pre_forge'), $this->isInstanceOf('Dispatcher_Event'))
				->will($this->returnValue(TRUE));

		$mock2 = $this->getMock('Mock_Callback', array('maybe'));

		$mock2->expects($this->never())
				->method('maybe');

		$dispatcher = new Dispatcher();

		$dispatcher->register_listener('random.pre_forge', array($mock, 'call_me'));
		$dispatcher->register_listener('random.pre_forge', array($mock2, 'maybe'));

		$dispatcher->trigger_event('random.pre_forge', Dispatcher::event(array('random' => 42)), TRUE);
	}
}