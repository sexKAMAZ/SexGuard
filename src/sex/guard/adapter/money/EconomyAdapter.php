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
interface EconomyAdapter
{
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
	 * @return int
	 */
	static function getMoney( string $nick ): int;


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function setMoney( string $nick, int $amount );


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function addMoney( string $nick, int $amount );


	/**
	 * @param string $nick
	 * @param int    $amount
	 */
	static function reduceMoney( string $nick, int $amount );
}