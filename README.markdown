# Dispatcher

Inspired by the Symfony [Event Dispatcher Component](http://components.symfony-project.org/event-dispatcher/), this module
allows you to create event driven applications.

It differs from the K2 Event class in a number of ways:

* It's not a singleton class, which makes it a lot easier to test the system and create multiple dispatchers
* Event data is encapsulated, allowing you can trigger events within events

## Unit Testing

At time of writing this module has 95.65% code coverage, ensuring the reliability of the API through development.

## Examples

	Dispatcher::instance()
		->register_listener('post.pre_publish', array('Misc_Class', 'callback'));

	// Later on...
	Dispatcher::instance()
		// Calls Misc_Class::callback_method();
		->trigger_event('post.pre_publish', Dispatcher::event(array())