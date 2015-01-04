<?php

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WashBookCommand extends WashCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wash:book';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Book a wash: wash:book YYYYMMDD [1-4]';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// First, we log in
		$this->login();

		// For some reason, the cookie must touch the Reservation page, 
		// or else we will get an error when we try to book
		$this->visitReservationPage();

		// We should probably do a bit of validation here...
		$date = $this->argument('date');
		$slot = $this->argument('slot');

		// Request to book the specified wash
		$response = $this->book($date, $slot);

		$errorStatus = $this->getErrorStatus($response->getEffectiveUrl());

		// Handle output based on the error status
		$this->line('');
		if($errorStatus === 'Nej') {
			$this->line('<info>Wash successfully booked!</info>');
		}
		if($errorStatus === 'JaNo2') {
			$this->line('<error>Wash is unavalible</error>');
		}
		if($errorStatus === 'JaNo1') {
			$this->line('<error>You have reached max allowed booked washes</error>');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['date', InputArgument::REQUIRED, 'Date in format YYYYMMDD'],
			['slot', InputArgument::REQUIRED, 'Time slot: 1-4'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

	/**
	 * Send a get request to the reservation page
	 */
	private function visitReservationPage()
	{
		$this->client->get('Reservation.asp', [
			'cookies'	=> true	
		]);
	}

	/**
	 * Book a wash
	 * 
	 * @param  string $date
	 * @param  string $slot
	 * 
	 * @return GuzzleHttp\Message\Response
	 */
	private function book($date, $slot)
	{
		return $this->client->post('Bestil.asp', [
			'query' => [
				'Mode'	=> 'Bestil'	
			],
			'body'	=> [
				'Dato'		=> $date,
				'Tur'		=> $slot,
				'Gruppe'	=> '1',
				'Lejlighed'	=> Config::get('laundromatic.flat'),
				'Beboer'	=> '2', // No clue what this does
				'KortNr'	=> '1', // ... or this
				'RetMode'	=> '' // And wtf is this?
			],
			'cookies'	=> true,
		]);
	}

	/**
	 * Get error status from response url (Yes, this is actually possible...)
	 * 
	 * @param  GuzzleHttp\Message\Response $response
	 * 
	 * @return string
	 */
	private function getErrorStatus($url)
	{
		// Do some wierd ass PHP voodoo to parse the URL returned
		$query = parse_url($url, PHP_URL_QUERY);

		$queryParams = [];

		parse_str($query, $queryParams);

		return $queryParams['Fejl'];
	}

}
