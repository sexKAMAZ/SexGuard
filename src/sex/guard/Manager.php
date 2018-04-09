<?php namespace sex\guard;


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
use sex\guard\provider\JsonProvider;
use sex\guard\provider\SQLiteProvider;

//  sex\guard\command\GuardCommand;
//  sex\guard\command\OldGuardCommand;

//  sex\guard\listener\block\BreakListener;
//  sex\guard\listener\block\PlaceListener;

//  sex\guard\listener\entity\DamageListener;
//  sex\guard\listener\entity\ExplodeListener;
//  sex\guard\listener\entity\TeleportListener;
//  sex\guard\listener\entity\BlockChangeListener;

//  sex\guard\listener\player\ChatListener;
//  sex\guard\listener\player\QuitListener;
//  sex\guard\listener\player\DropItemListener;
//  sex\guard\listener\player\InteractListener;
//  sex\guard\listener\player\BedEnterListener;
//  sex\guard\listener\player\BucketFillListener;
//  sex\guard\listener\player\BucketEmptyListener;

//  sex\guard\listener\server\PacketRecieveListener;


use pocketmine\plugin\PluginBase;
use pocketmine\level\Position;


use Exception;
use InvalidArgumentException;


class Manager extends PluginBase
{
	const VERSION_SIGN = 'INDEV';


	/**
	 * @var Manager
	 */
	static $instance = null;


	/**
	 * @return Manager
	 */
	static function getInstance( ): self
	{
		return self::$instance;
	}


	/**
	 * @var Provider
	 */
	private $provider;


	/**
	 *
	 *  _ __ _   __ _ _ __   __ _  __ _  ___ _ __
	 * | '  ' \ / _' | '_ \ / _' |/ _' |/ _ \ '_/
	 * | || || | (_) | | | | (_) | (_) |  __/ |
	 * |_||_||_|\__,_|_| |_|\__,_|\__, |\___|_|
	 *                            /___/
	 *
	 */
	function onEnable( )
	{
		$this->loadInstance();

		$this->loadProvider();
		$this->loadListener();
		$this->loadCommand();
		$this->loadTask();
	}


	private function loadInstance( )
	{
		self::$instance = $this;
	}


	private function loadProvider( )
	{
		$this->provider = new SQLiteProvider($this->getDataFolder(). 'data/');
	}


	private function loadListener( )
	{
		$list = [
			// new BreakListener($this),
			// new PlaceListener($this),

			// new DamageListener($this),
			// new ExplodeListener($this),
			// new TeleportListener($this),
			// new BlockChangeListener($this),

			// new ChatListener($this),
			// new QuitListener($this),
			// new DropItemListener($this),
			// new InteractListener($this),
			// new BedEnterListener($this),
			// new BucketFillListener($this),
			// new BucketEmptyListener($this),

			// new PacketRecieveListener($this)
		];

		foreach( $list as $listener )
		{
			$this->getServer()->getPluginManager()->registerEvents($listener, $this);
		}
	}
	
	
	private function loadCommand( )
	{
		try
		{
			$list = [
				// new GuardCommand($this)
			];
		}

		catch( Exception $exception )
		{
			$list = [
				// new OldGuardCommand($this)
			];
		}

		foreach( $list as $command )
		{
			$map     = $this->getServer()->getCommandMap();
			$replace = $map->getCommand($command->getName());

			if( isset($replace) )
			{
				$replace->setLabel('');
				$replace->unregister($map);
			}

			$map->register($this->getName(), $command);
		}
	}


	private function loadTask( )
	{
		try
		{
			$list = [
				// [ new PositionCheckTask($this), 30 ]
			];
		}

		catch( Exception $exception )
		{
			$list = [
				// [ new OldPositionCheckTask($this), 30 ]
			];
		}

		foreach( $list as $task )
		{
			$this->getServer()->getScheduler()->scheduleRepeatingTask(...$task);
		}
	}


	/**
	 * @return Provider
	 */
	private function getProvider( ): Provider
	{
		return $this->provider;
	}


	/**
	 *              _
	 *   __ _ _ __ (_)
	 *  / _' | '_ \| |
	 * | (_) | (_) | |
	 *  \__,_| ,__/|_|
	 *       |_|
	 *
	 * NOTE THAT: this function can return Region, Region[], empty list and null.
	 *
	 * CHOOSE WHAT YOU NEED:
	 * 1. if you need to get region by name,
	 *    than $type must be region's name and $by_name = true.
	 *    function returns Region or null.
	 * 2. if you need to get region by owner,
	 *    than $type must be owner's name and $by_name = false.
	 *    function returns Region[] or empty list.
	 * 3. if you need to get region by Position,
	 *    than $type must be Position (or extend Position).
	 *    function returns Region or null.
	 * 4. if you need to get all regions in the area,
	 *    than $type must be Area (or extend Area).
	 *    function returns Region[] or empty list.
	 *
	 * @param  string|Position|Area $type
	 * @param  bool                 $by_name
	 *
	 * @return Region|Region[]|null
	 *
	 * @throws InvalidArgumentException
	 */
	function getRegion( $type, bool $by_name = false )
	{
		if( is_string($type) )
		{
			if( $by_name )
			{
				return $this->getProvider()->getRegion($type);
			}

			return $this->getProvider()->getRegionByOwner($type);
		}

		elseif( $type instanceof Position )
		{
			return $this->getProvider()->getRegionByPosition($type);
		}

		elseif( $type instanceof Area )
		{
			return $this->getProvider()->getRegionByArea($type);
		}

		throw new InvalidArgumentException('Invalid type!');
	}


	/**
	 * NOTE THAT:
	 * 1. regions are saved asynchronously.
	 * 2. if you have a bunch of regions to save,
	 *    it's better to put them all in a function,
	 *    than doing it one at a time.
	 *
	 * @param Region[] $region
	 */
	function setRegion( Region ...$region )
	{
		$this->getProvider()->setRegion(...$region);
	}


	/**
	 * NOTE THAT:
	 * 1. regions are removed asynchronously.
	 * 2. if you have a bunch of regions to remove,
	 *    it's better to put all of their names in a function,
	 *    than doing it one at a time.
	 *
	 * @param string[] $name
	 */
	function removeRegion( string ...$name )
	{
		$this->getProvider()->removeRegion(...$name);
	}
}