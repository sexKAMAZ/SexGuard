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


use pocketmine\Server;


class PurePermsAdapter implements GroupAdapter
{
	const PLUGIN_NAME = 'PurePerms';


	/**
	 *            _             _
	 *   __ _  __| | __ _ _ __ | |_____ _ __
	 *  / _' |/ _' |/ _' | '_ \|  _/ _ \ '_/
	 * | (_) | (_) | (_) | (_) | ||  __/ |
	 *  \__,_|\__,_|\__,_| ,__/ \__\___|_|
	 *                   |_|
	 *
	 * @param  string $nick
	 *
	 * @return string
	 */
	static function getGroup( string $nick ): string
	{
		$server = Server::getInstance();
		$plugin = $server->getPluginManager()->getPlugin(self::PLUGIN_NAME);

		if( !isset($plugin) or $plugin->isDisabled() )
		{
			return '';
		}

		$group = $plugin->getUserDataMgr()->getGroup($server->getOfflinePlayer($nick));

		if( !isset($group) )
		{
			return '';
		}

		return $group->getName();
	}
}