<?php namespace sex\guard\provider;


/**
 *  _    _       _                          _  ____
 * | |  | |_ __ (_)_    _____ _ ______ __ _| |/ ___\_ _______      __
 * | |  | | '_ \| | \  / / _ \ '_/ __// _' | | /   | '_/ _ \ \    / /
 * | |__| | | | | |\ \/ /  __/ | \__ \ (_) | | \___| ||  __/\ \/\/ /
 *  \____/|_| |_|_| \__/ \___|_| /___/\__,_|_|\____/_| \___/ \_/\_/
 *
 * @author sex_KAMAZ
 * @link   https://vk.com/infernopage
 *         https://t.me/sex_kamaz
 *
 */
use sex\guard\util\Config;

use sex\guard\object\Region;

use sex\guard\provider\Provider;


class JsonProvider implements Provider
{
	/**
	 * @var Config
	 */
	private $region_data;


	/**
	 *      _                 ____                 _     _
	 *     | |____ ___  _ __ |  _ \ _ _______    _(_) __| | ___ _ __
	 *  _  | / __// _ \| '_ \| (_) | '_/ _ \ \  / / |/ _' |/ _ \ '_/
	 * | |_| \__ \ (_) | | | |  __/| || (_) \ \/ /| | (_) |  __/ |
	 *  \___//___/\___/|_| |_|_|   |_| \___/ \__/ |_|\__,_|\___|_|
	 *
	 *
	 * @param string $location
	 */
	function __construct( string $location )
	{
		if( !is_dir($location) )
		{
			mkdir($location);
		}

		$this->region_data = new Config($location. 'region_data.json');

		$this->region_data->reload();
	}


	/**
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name )
	{
		$name = strtolower($name);
		$data = $this->region_data->get($name);

		if( !$data )
		{
			return null;
		}

		return Region::make($name, $data);
	}


	/**
	 * @param  Region $region
	 *
	 * @return JsonProvider
	 */
	function setRegion( Region $region ): Provider
	{
		$name = $region->getName();
		$data = $region->toData();

		$this->region_data->set($name, $data);
		$this->region_data->save(true);

		return $this;
	}


	/**
	 * @param  string $name
	 *
	 * @return JsonProvider
	 */
	function removeRegion( string $name ): Provider
	{
		$name = strtolower($name);

		$this->region_data->remove($name);
		$this->region_data->save(true);

		return $this;
	}
}