<?php namespace sex\guard\util;


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
use sex\guard\Manager;


use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\AsyncTask;
use pocketmine\scheduler\Task;

use pocketmine\Server;


class Multiversion
{
	/**
	 * @var bool
	 */
	private static $old_scheduler;


	/**
	 * @return bool
	 */
	static function isOldScheduler( ): bool
	{
		if( !isset(self::$old_scheduler) )
		{
			$old = false;

			try
			{
				Manager::getInstance()->getScheduler();
			}

			catch( Throwable $exception )
			{
				$old = true;
			}

			self::$old_scheduler = $old;
		}

		return self::$old_scheduler;
	}


	/**
	 * @param  Task $task
	 * @param  int  $period
	 *
	 * @return TaskHandler|null
	 */
	static function scheduleRepeatingTask( Task $task, int $period )
	{
		if( Manager::isOldScheduler() )
		{
			return Server::getInstance()->getScheduler()->scheduleRepeatingTask($task, $period);
		}

		return Manager::getInstance()->getScheduler()->scheduleRepeatingTask($task, $period);
	}


	/**
	 * @param  Task $task
	 * @param  int  $period
	 *
	 * @return TaskHandler|null
	 */
	static function scheduleDelayedTask( Task $task, int $period )
	{
		if( Manager::isOldScheduler() )
		{
			return Server::getInstance()->getScheduler()->scheduleDelayedTask($task, $period);
		}

		return Manager::getInstance()->getScheduler()->scheduleDelayedTask($task, $period);
	}


	/**
	 * @param AsyncTask $task
	 */
	static function scheduleAsyncTask( AsyncTask $task )
	{
		if( Manager::isOldScheduler() )
		{
			return Server::getInstance()->getScheduler()->scheduleAsyncTask($task);
		}

		return Server::getInstance()->getAsyncPool()->submitTask($task);
	}
}