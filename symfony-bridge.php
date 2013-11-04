<?php
/*
Plugin Name: Symfony Event Bridge
Description: Allow Symfony applications to hook into the WP admin by bridging the event systems
Version: 1.0
*/

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;

if (is_admin()) {
	add_action('init', function() {
		/** @var $kernel AppKernel */
		$kernel = require_once ABSPATH . 'create-kernel.php';

		$kernel->boot();
		$container = $kernel->getContainer();

		//need to switch to request scope in order to make all services available
		$container->enterScope('request');
		$container->set('request', new Request(), 'request');

		//no event data to pass so use generic event class
		$event = new Event();

		$container->get('event_dispatcher')->dispatch('outlandish_routemaster.wp_init', $event);
	});
}