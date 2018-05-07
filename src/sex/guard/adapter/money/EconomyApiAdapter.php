<?php namespace sex\guard\adapter\money;


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
use sex\guard\adapter\money\EconomyAdapter;


use pocketmine\Server;


class EconomyApiAdapter implements EconomyAdapter
{
	const PLUGIN_NAME = 'EconomyAPI';


	/**
	 * @param  string $nick
	 *
	 * @return int
	 */
	static function getMoney( string $nick ): int
	{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return 0;
		}

		$amount = $plugin->myMoney($nick);

		if( $amount === false )
		{
			$amount = 0;
		}

		return $amount;
	}


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function setMoney( string $nick, int $amount )
	{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return;
		}

		if( $amount < 0 )
		{
			$amount = 0;
		}

		$plugin->setMoney($nick, $amount, false, self::PLUGIN_NAME);
	}


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function addMoney( string $nick, int $amount )
	{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return;
		}

		if( $amount < 0 )
		{
			// self::reduceMoney($nick, abs($amount));

			return;
		}

		$plugin->addMoney($nick, $amount, false, self::PLUGIN_NAME);
	}


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function reduceMoney( string $nick, int $amount )
	{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return;
		}

		if( $amount < 0 )
		{
			// self::addMoney($nick, abs($amount));

			return;
		}

		$plugin->reduceMoney($nick, $amount, false, self::PLUGIN_NAME);
	}
}