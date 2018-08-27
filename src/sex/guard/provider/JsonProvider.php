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
use sex\guard\provider\Provider;

use sex\guard\object\Area;
use sex\guard\object\Region;
use sex\guard\object\container\SortedRegionList;


use pocketmine\level\Position;
use pocketmine\utils\Config;


class JsonProvider implements Provider
{
	const FILENAME = 'region_data.json';


	/**
	 * @var Config
	 */
	private $region_data;

	/**
	 * @var SortedRegionList
	 */
	private $region_list;


	/**
	 *                       _     _
	 *  _ __  _ _______    _(_) __| | ___ _ __
	 * | '_ \| '_/ _ \ \  / | |/ _' |/ _ \ '_/
	 * | (_) | || (_) \ \/ /| | (_) |  __/ |
	 * | ,__/|_| \___/ \__/ |_|\__,_|\___|_|
	 * |_|
	 *
	 * @param string $location
	 */
	function __construct( string $location )
	{
		if( !is_dir($location) )
		{
			mkdir($location, 0777, true);
		}

		$this->region_data = new Config($location. self::FILENAME);

		$this->region_data->reload();

		$list = [];

		foreach( $this->region_data->getAll() as $name => $data )
		{
			$region = Region::make($name, $data);

			if( !isset($region) )
			{
				continue;
			}

			$list[] = $region;
		}

		$this->region_list = new SortedRegionList(...$list);
	}


	/**
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name )
	{
		$region = $this->getRegionList()->get($name);

		if( isset($region) )
		{
			return $region;
		}

		$name = strtolower($name);
		$data = $this->getRegionData()->get($name);

		if( !$data )
		{
			return null;
		}

		$region = Region::make($name, $data);

		if( !isset($region) )
		{
			return null;
		}

		$this->setRegion($region);
		return $region;
	}


	/**
	 * @param  string $nick
	 *
	 * @return Region[]
	 */
	function getRegionByPlayer( string $nick ): array
	{
		return $this->getRegionList()->getByPlayer($nick);
	}


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getRegionByPosition( Position $position )
	{
		return $this->getRegionList()->getByPosition($position);
	}


	/**
	 * @param  Area $area
	 *
	 * @return Region[]
	 */
	function getRegionByArea( Area $area ): array
	{
		return $this->getRegionList()->getByArea($area);
	}


	/**
	 * @param  Region[] $list
	 *
	 * @return Provider
	 */
	function setRegion( Region ...$list ): Provider
	{
		foreach( $list as $region )
		{
			$this->getRegionList()->set($region);
			$this->getRegionData()->set($region->getName(), $region->toData());
		}

		$this->getRegionData()->save(true);
		return $this;
	}


	/**
	 * @param  string[] $list
	 *
	 * @return Provider
	 */
	function removeRegion( string ...$list ): Provider
	{
		foreach( $list as $name )
		{
			$name = strtolower($name);

			$this->getRegionList()->remove($name);
			$this->getRegionData()->remove($name);
		}

		$this->getRegionData()->save(true);
		return $this;
	}


	/**
	 * @return Config
	 */
	private function getRegionData( ): Config
	{
		return $this->region_data;
	}


	/**
	 * @return SortedRegionList
	 */
	private function getRegionList( ): SortedRegionList
	{
		return $this->region_list;
	}
}