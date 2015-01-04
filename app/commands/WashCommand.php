<?php

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class WashCommand extends Command {

	protected $client;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->client = new Client([
			'base_url' => Config::get('laundromatic.base_url')
		]);
	}

	public function login()
	{
		$this->client->post('aLog.asp', [
		    'body'      => [
		            'username'  => Config::get('laundromatic.flat'),
		            'password'  => Config::get('laundromatic.password')
		        ],
		    'cookies'   => true
		]);
	}
}
