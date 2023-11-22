<?php

namespace App\Providers;

use App\Nova\EventCall;
use App\Nova\EventOther;
use App\Nova\EventMeeting;
use Wdelfuego\NovaCalendar\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Resource as NovaResource;
use Wdelfuego\NovaCalendar\DataProvider\AbstractCalendarDataProvider;

class CalendarDataProvider extends AbstractCalendarDataProvider {
	//
	// Add the Nova resources that should be displayed on the calendar to this method
	//
	// Must return an array with string keys and string or array values;
	// - each key is a Nova resource class name (eg: 'App/Nova/User::class')
	// - each value is either:
	//
	//   1. a string containing the attribute name of a DateTime casted attribute
	//      of the underlying Eloquent model that will be used as the event's
	//      starting date and time (eg.: 'created_at')
	//
	//      OR
	//
	//   2. an array containing two strings; the first is the name of the attribute
	//      that will be used as the event's starting date and time (eg.: 'starts_at'),
	//      the second will be used as the event's ending date and time (eg.: 'ends_at').
	//
	//      OR
	//
	//   3. an instance of a custom Event generator, which is generally only required
	//      if you want to create more than 1 calendar event for individual Nova resource instances
	//
	//
	// See https://github.com/wdelfuego/nova-calendar to find out
	// how to customize the way the events are displayed
	//
	public function novaResources(): array {
		return [

			// Events without an ending timestamp will always be shown as single-day events:
			EventCall::class => ['start', 'end'],
			EventMeeting::class => ['start', 'end'],
			EventOther::class => ['start', 'end'],

			// Events with an ending timestamp can be multi-day events:
			// SomeResource::class => ['starts_at', 'ends_at'],

			// Custom event generators allow you to take complete control of how
			// events are added to the calendar for your Nova resources
			// Take a look at the documentation if you want to implement custom event generators.
			// SomeResource::class => new MyCustomEventGenerator(),
		];
	}

	// Use this method to show events on the calendar that don't
	// come from a Nova resource. Just return an array of dynamically
	// generated events.
	// protected function nonNovaEvents(): array {
	// 	return [
	// 		(new Event("Today until tomorrow", now(), now()->addDays(1)))
	// 			->displayTime()
	// 			->addBadges('📞')
	// 			->withNotes('these are the event notes'),
	// 	];
	// }

	protected function customizeEvent(Event $event): Event {

		// $event->addBadges($event->resource()->calendar()['badge']);
		$event->addStyle($event->resource()->calendar()['color'])
			->addBadge($event->resource()->calendar()['badge'])
			->displayTime();

			// if($event->model())
			// {
			//     $event->end($event->model()->datetime_end);
			//     $event->name($event->model()->name);
			//     $event->notes($event->model()->notes);
			// }
		return $event;	
	}

	
	public function eventStyles(): array {
		return [
			'primary' => [	
				'color' => '#FFF',
				'background-color' => '#007bff',
			],
			'secondary' => [	
				'color' => '#FFF',
				'background-color' => '#6c757d',
			],
			'success' => [
				'color' => '#FFF',
				'background-color' => '#28a745',
			],
			'danger' => [
				'color' => '#FFF',
				'background-color' => '#dc3545',
			],
			'warning' => [
				'color' => '#FFF',
				'background-color' => '#ffc107',
			],
			'info' => [
				'color' => '#FFF',
				'background-color' => '#17a2b8',
			],						
		];
	}

	// - Exclude resources from Calendar
	protected function excludeResource(NovaResource $resource) : bool
	{
		// - Exclue events not related to the user's CDR
		if (!Auth::user()->is_cdr) {
			return false;
		} else {
			return ( Auth::user()->cdr_id != $resource->cdr_id );
		}
	}	
}


