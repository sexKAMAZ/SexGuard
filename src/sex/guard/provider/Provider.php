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


interface Provider
{
	/**
	 *                       _     _
	 *  _ __  _ _______    _(_) __| | ___ _ __
	 * | '_ \| '_/ _ \ \  / | |/ _' |/ _ \ '_/
	 * | (_) | || (_) \ \/ /| | (_) |  __/ |
	 * | ,__/|_| \___/ \__/ |_|\__,_|\___|_|
	 * |_|
	 *
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name );


	/**
	 * @param  string $nick
	 *
	 * @return Region[]
	 */
	function getRegionByPlayer( string $nick ): array;


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getRegionByPosition( Position $position );


	/**
	 * @param  Area $area
	 *
	 * @return Region[]
	 */
	function getRegionByArea( Area $area ): array;


	/**
	 * @param  Region[] $region
	 *
	 * @return Provider
	 */
	function setRegion( Region ...$region ): Provider;


	/**
	 * @param  string[] $name
	 *
	 * @return Provider
	 */
	function removeRegion( string ...$name ): Provider;
}