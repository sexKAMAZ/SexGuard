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
use sex\guard\object\Area;
use sex\guard\object\Region;
use sex\guard\object\container\RegionList;


use pocketmine\level\Position;


class SortedRegionList extends RegionList
{
	/**
	 * @var string[][]
	 */
	private $player_name_list = [];

	/**
	 * @var string[][][]
	 */
	private $position_name_list = [];


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
		parent::__construct(...$list);

		foreach( $list as $region )
		{
			$name  = $region->getName();
			$owner = $region->getOwner();

			$this->player_name_list[$owner][] = $name;

			foreach( $region->getMemberList() as $member )
			{
				$this->player_name_list[$member][] = $name;
			}

			$level = $region->getLevel()->getName();
			$side  = $region->getLevelSide();

			$this->position_name_list[$level][$side][] = $name;
		}
	}


	/**
	 * @param  string $nick
	 *
	 * @return Region[]
	 */
	function getByPlayer( string $nick ): array
	{
		$nick = strtolower($nick);

		if( !isset($this->player_name_list[$nick]) )
		{
			return [];
		}

		$list = $this->player_name_list[$nick];

		foreach( $list as $index => $name )
		{
			$region = $this->get($name);

			if( !isset($region) )
			{
				unset($this->player_name_list[$nick][$index]);
				continue;
			}

			$list[$index] = $region;
		}

		return $list;
	}


	/**
	 * @param  string $level
	 * @param  int    $side
	 *
	 * @return Region[]
	 */
	function getByLevelSide( string $level, int $side ): array
	{
		if( !isset($this->position_name_list[$level]) )
		{
			return null;
		}

		if( !isset($this->position_name_list[$level][$side]) )
		{
			return null;
		}

		$list = $this->position_name_list[$level][$side];

		foreach( $list as $index => $name )
		{
			$region = $this->get($name);

			if( !isset($region) )
			{
				unset($this->position_name_list[$level][$side][$index]);
				continue;
			}

			$list[$index] = $region;
		}

		return $list;
	}


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getByPosition( Position $position )
	{
		$level = $position->getLevel()->getName();
		$side  = Region::getLevelSideByVector($position);
		$list  = $this->getByLevelSide($level, $side);

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
	function getByArea( Area $area ): array
	{
		$level = $area->getLevel()->getName();
		$side  = $area->getLevelSide();
		$list  = $this->getByLevelSide($level, $side);

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
	 * @param  Region $region
	 *
	 * @return RegionList
	 */
	function set( Region $region ): RegionList
	{
		parent::set($region);

		$name  = $region->getName();
		$owner = $region->getOwner();

		if( isset($this->player_name_list[$owner]) )
		{
			if( !in_array($name, $this->player_name_list[$owner]) )
			{
				$this->player_name_list[$owner][] = $name;
			}
		}

		foreach( $region->getMemberList() as $member )
		{
			if( isset($this->player_name_list[$owner]) )
			{
				if( !in_array($name, $this->player_name_list[$member]) )
				{
					$this->player_name_list[$member][] = $name;
				}
			}
		}

		$level = $region->getLevel()->getName();
		$side  = $region->getLevelSide();

		if( isset($this->position_name_list[$level][$side]) )
		{
			if( !in_array($name, $this->position_name_list[$level][$side]) )
			{
				$this->position_name_list[$level][$side][] = $name;
			}
		}

		return $this;
	}
}