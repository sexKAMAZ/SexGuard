<?php namespace sex\guard\object\container;


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
use sex\guard\object\Region;


class RegionList
{
	/**
	 * @var Region[]
	 */
	private $list = [];


	/**
	 *                   _        _
	 *   ___  ___  _ __ | |____ _(_)_ __   ___ _ __
	 *  / __\/ _ \| '_ \|  _/ _' | | '_ \ / _ \ '_/
	 * | (__| (_) | | | | || (_) | | | | |  __/ |
	 *  \___/\___/|_| |_|\__\__,_|_|_| |_|\___|_|
	 *
	 *
	 * @param Region[] $list
	 */
	function __construct( Region ...$list )
	{
		foreach( $list as $region )
		{
			$name = $region->getName();

			$this->list[$name] = $region;
		}
	}


	/**
	 * @return Region[]
	 */
	function getAll( ): array
	{
		return $this->list;
	}


	/**
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function get( string $name )
	{
		$name = strtolower($name);

		if( !isset($this->list[$name]) )
		{
			return null;
		}

		return $this->list[$name];
	}


	/**
	 * @param  Region $region
	 *
	 * @return RegionList
	 */
	function set( Region $region ): RegionList
	{
		$name = $region->getName();

		if( isset($this->list[$name]) )
		{
			$this->list[$name] = $region;
		}

		return $this;
	}


	/**
	 * @param  string $name
	 *
	 * @return RegionList
	 */
	function remove( string $name ): RegionList
	{
		$name = strtolower($name);

		if( isset($this->list[$name]) )
		{
			unset($this->list[$name]);
		}

		return $this;
	}


	/**
	 * @param  string $name
	 *
	 * @return bool
	 */
	function exists( string $name ): bool
	{
		return isset($this->list[$name]);
	}
}