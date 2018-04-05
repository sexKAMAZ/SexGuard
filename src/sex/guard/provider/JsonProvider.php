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
use sex\guard\object\Area;
use sex\guard\object\Region;

use sex\guard\provider\Provider;


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
	 * @var Region[][]
	 */
	private $region_list = [];


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

		$this->region_data = new Config($location. self::FILENAME);

		$this->region_data->reload();

		foreach( $this->region_data->getAll() as $name => $data )
		{
			$region = Region::make($name, $data);

			if( !isset($region) )
			{
				continue;
			}

			$level = $region->getLevel()->getName();
			$side  = $region->getLevelSide();

			$this->region_list[$level][$side] = $region;
		}
	}


	/**
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name )
	{
		$name = strtolower($name);

		foreach( $this->region_list as $level => $list_by_level )
		{
			foreach( $list_by_level as $side => $list_by_side )
			{
				foreach( $list_by_side as $region )
				{
					if( $region->getName() != $name )
					{
						continue;
					}

					return $region;
				}
			}
		}

		$data = $this->region_data->get($name);

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
	 * @param  string $owner
	 *
	 * @return Region[]
	 */
	function getRegionByOwner( string $owner ): array
	{
		$owner  = strtolower($owner);
		$result = [];

		foreach( $this->region_list as $level => $list_by_level )
		{
			foreach( $list_by_level as $side => $list_by_side )
			{
				foreach( $list_by_side as $region )
				{
					if( $region->getOwner() != $name )
					{
						continue;
					}

					$result[] = $region;
				}
			}
		}

		return $result;
	}


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getRegionByPosition( Position $position )
	{
		$level = $position->getLevel()->getName();
		$side  = Region::getLevelSideByVector($position);
		$list  = $this->region_list[$level][$side];

		if( !isset($list) )
		{
			return null;
		}

		for( end($list), $i = key($list), reset($list); $i >= 0; $i-- )
		{
			if( !isset($list[$i]) )
			{
				continue;
			}

			$region = $list[$i];

			if( !$region->isVectorInside($position) )
			{
				continue;
			}

			return $region;
		}

		return null;
	}


	/**
	 * @param  Area $area
	 *
	 * @return Region[]
	 */
	function getRegionByArea( Area $area ): array
	{
		$level = $area->getLevel()->getName();
		$side  = $area->getLevelSide();
		$list  = $this->region_list[$level][$side];

		if( !isset($list) )
		{
			return null;
		}

		$result = [];

		for( end($list), $i = key($list), reset($list); $i >= 0; $i-- )
		{
			if( !isset($list[$i]) )
			{
				continue;
			}

			$region = $list[$i];

			if( !$area->intersectsWith($region) )
			{
				continue;
			}

			$result[] = $region;
		}

		return $result;
	}


	/**
	 * @param  Region[] $list
	 *
	 * @return JsonProvider
	 */
	function setRegion( Region ...$list ): JsonProvider
	{
		foreach( $list as $region )
		{
			$name = $region->getName();

			foreach( $this->region_list as $level => $list_by_level )
			{
				foreach( $list_by_level as $side => $list_by_side )
				{
					foreach( $list_by_side as $index => $old_region )
					{
						if( $old_region->getName() != $name )
						{
							continue;
						}

						unset($this->region_list[$level][$side][$index]);
					}
				}
			}

			$level = $region->getLevel()->getName();
			$side  = $region->getLevelSide();

			$this->region_list[$level][$side] = $region;

			$this->region_data->set($name, $region->toData());
		}

		$this->region_data->save(true);
		return $this;
	}


	/**
	 * @param  string[] $list
	 *
	 * @return JsonProvider
	 */
	function removeRegion( string ...$list ): JsonProvider
	{
		foreach( $list as $name )
		{
			$name = strtolower($name);

			foreach( $this->region_list as $level => $list_by_level )
			{
				foreach( $list_by_level as $side => $list_by_side )
				{
					foreach( $list_by_side as $index => $region )
					{
						if( $region->getName() != $name )
						{
							continue;
						}

						unset($this->region_list[$level][$side][$index]);

						/* still need this?
						$this->region_list[$level][$side] = array_values($this->region_list[$level][$side]);
						*/
					}
				}
			}

			$this->region_data->remove($name);
		}

		$this->region_data->save(true);
		return $this;
	}
}