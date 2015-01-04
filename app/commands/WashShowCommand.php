<?php

use GuzzleHttp\Client;
use Symfony\Component\Console\Helper\Table;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class WashShowCommand extends WashCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wash:show';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Show the schedule for specified date';

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
		// Set styling for error messages
		$formatter = $this->output->getFormatter();
		$formatter->setStyle('error', new OutputFormatterStyle('red'));
		
		// Log in
		$this->login();

		$date = $this->argument('date');

		$dateTime = DateTime::createFromFormat('Ymd', $date);
		$date = $dateTime->format('d/m-Y');

		$this->printDate($dateTime);

		$times = $this->getWashingTimes($date);

		$this->printTimes($times);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('date', InputArgument::REQUIRED, 'Date in format YYYYMMDD'),
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

	/**
	 * Format input to textual table
	 *
	 * @param  array   $headers
	 * @param  array   $rows
	 * @param  string  $style
	 * @return void
	 */
	public function table(array $headers, array $rows, $style = 'default')
	{
		$table = new Table($this->output);

		$style = $table->getStyle();

		$style->setCellHeaderFormat('<comment>%s</comment>');

		$table->setStyle($style);

		$table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
	}

	/**
	 * Get the status of the time
	 * 
	 * @param  string $time
	 * @return string
	 */
	protected function getTimeStatus($time)
	{
		if(is_numeric($time)) {
			return "<error>TAKEN ($time)</error>";
		}

		if($time === "-") {
			return "<error>EXPIRED</error>";
		}

		if(strpos($time, 'Bestil') !== false) {
			return "<info>AVAILABLE</info>";
		}

		return '<info>BOOKED</info>';
	}

	/**
	 * Print the given date
	 * 
	 * @param  \DateTime $date
	 */
	protected function printDate(\DateTime $date)
	{
		$formattedDate = $date->format('l, F jS, Y');

		$this->line('');
		$this->line('  <comment>'.strtoupper($formattedDate).'</comment>');
	}

	/**
	 * Gets washing times from a DOM tree
	 * 
	 * @param  string $DOM
	 * 
	 * @return array
	 */
	private function getTimesFromHTML($DOM)
	{
		$dom = new \DOMDocument;

		libxml_use_internal_errors(true);

		$dom->loadHTML((string) $DOM);

		$tables = $dom->getElementsByTagName('table');

		$table = $tables->item($tables->length - 1);

		$rows = $table->getElementsByTagName('tr');

		$times = [];

		foreach ($rows as $index => $row) {
		    if($index > 1) {
		        $tds = iterator_to_array($row->getElementsByTagName('td'));

		        $times[] = str_replace("&nbsp;", "", htmlentities($tds[1]->textContent));
		    }
		}

		return $times;
	}

	/**
	 * Print the time table
	 * 
	 * @param  array  $times
	 */
	private function printTimes(array $times)
	{
		$headers = ['TIME', 'STATUS', 'BOOKING COMMAND'];

		$rows = [
			['07:00 - 10:00', $this->getTimeStatus($times[0]), '-'],
			['10:00 - 13:00', $this->getTimeStatus($times[1]), '-'],
			['13:00 - 16:00', $this->getTimeStatus($times[2]), '-'],
			['16:00 - 19:00', $this->getTimeStatus($times[3]), '-'],
		];

		$this->table($headers, $rows);
	}

	/**
	 * Get the washing times for the specified date
	 * 
	 * @param  string $date
	 * 
	 * @return array
	 */
	private function getWashingTimes($date)
	{
		$response = $this->client->get("Reservation.asp", [
			'query'		=> ['Dato' => $date],
   			'cookies'   => true
		]);

		return $this->getTimesFromHTML($response->getBody());
	}

}
