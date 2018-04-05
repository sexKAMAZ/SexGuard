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
use sex\guard\util\Config;

use sex\guard\object\Region;

use sex\guard\provider\Provider;


use pocketmine\level\Position;


interface Provider
{
	/**
	 *  ____                 _     _
	 * |  _ \ _ _______    _(_) __| | ___ _ __
	 * | (_) | '_/ _ \ \  / | |/ _' |/ _ \ '_/
	 * |  __/| || (_) \ \/ /| | (_) |  __/ |
	 * |_|   |_| \___/ \__/ |_|\__,_|\___|_|
	 *
	 *
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name );


	/**
	 * @param  string $owner
	 *
	 * @return Region|null
	 */
	function getRegionByOwner( string $owner );


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getRegionByPosition( Position $position );


	/**
	 * @param  Region $region
	 *
	 * @return JsonProvider
	 */
	function setRegion( Region $region ): Provider;


	/**
	 * @param  string $name
	 *
	 * @return JsonProvider
	 */
	function removeRegion( string $name ): Provider;
}