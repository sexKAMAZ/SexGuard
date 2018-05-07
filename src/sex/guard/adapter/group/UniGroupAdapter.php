<?php namespace sex\guard\adapter\group;


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
use sex\guard\adapter\group\GroupAdapter;


use pocketmine\Player;
use pocketmine\Server;


class UniGroupAdapter implements GroupAdapter
{
	const PLUGIN_NAME = 'UniversalGroup';


	/**
	 * @param  Player $player
	 *
	 * @return string
	 */
	static function getGroup( Player $player ): string
	{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return '';
		}

		return $plugin->getGroup($plugin->getLevel($player->getName()));
	}
}