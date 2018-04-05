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
use sex\guard\object\Region;

use sex\guard\provider\JsonProvider;

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


use Exception;


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
	 *  __  __                                   
	 * |  \/  | __ _ _ __   __ _  __ _  ___ _ __ 
	 * | |\/| |/ _' | '_ \ / _' |/ _' |/ _ \ '_/
	 * | |  | | (_) | | | | (_) | (_) |  __/ |   
	 * |_|  |_|\__,_|_| |_|\__,_|\__, |\___|_|   
	 *                           /___/
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


	/**
	 * @todo add SQLiteProvider
	 */
	private function loadProvider( )
	{
		$this->provider = new JsonProvider($this->getDataFolder(). 'data/');
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
}