# Dispatcher

Inspired by the [symfony dispatcher](http://components.symfony-project.org/event-dispatcher/), this module
allows you to create event driven applications.

This differs from the K2 Event class in a number of ways:

* It's not a singleton class, which makes it a lot easier to test the system
* Event data is encapsulated, allowing you can trigger events within events


## Examples

	Dispatcher::instance()
		->register_listener('post.pre_publish', array('Misc_Class', 'callback_method'));

	// Later on...
	Dispatcher::instance()
		// Calls Misc_Class::callback_method();
		->trigger_event('post.pre_publish', Dispatcher::event(array())