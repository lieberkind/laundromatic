<?php

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CancelWashCommand extends WashCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wash:cancel';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cancel a wash';

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
		$this->login();

		// Should do some validation...
		$date = $this->argument('date');
		$slot = $this->argument('slot');

		$this->cancel($date, $slot);

		$this->line('');
		$this->line('<comment>Wash was cancelled</comment>');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('date', InputArgument::REQUIRED, 'The date'),
			array('slot', InputArgument::REQUIRED, 'The slot'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

	protected function cancel($date, $slot)
	{
		$this->client->get('Bestil.asp', [
			'query'	=> [
				'Mode'		=> 'Slet',
				'Dato'		=> $date,
				'Tur'		=> $slot,
				'Gruppe'	=> '1'
			],
			'cookies'	=> true
		]);
	}

}
