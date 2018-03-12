<?php namespace sex\guard\object;


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
use pocketmine\level\Position;


class Selector
{
	/**
	 * @var string
	 */
	private $nick;

	/**
	 * @var Position
	 */
	private $first_position = null;

	/**
	 * @var Position
	 */
	private $second_position = null;


	/**
	 *  _____      _           _
	 * / ___/  ___| | ___  ___| |_____  _ __
	 * \___ \ / _ \ |/ _ \/ __| __/ _ \| '_/
	 *  ___) |  __/ |  __/ (__| || (_) | |
	 * /____/ \___|_|\___\\___/\__\___/|_|
	 *
	 *
	 * @param string $nick
	 */
	function __construct( string $nick )
	{
		$this->nick = strtolower($nick);
	}


	/**
	 * @return Position|null
	 */
	function getFirstPosition( )
	{
		return $this->first_position;
	}


	/**
	 * @param  Position $position
	 *
	 * @return Selector
	 */
	function setFirstPosition( Position $position ): self
	{
		$this->first_position = $position;

		return $this;
	}


	/**
	 * @return Position|null
	 */
	function getSecondPosition( )
	{
		return $this->second_position;
	}


	/**
	 * @param  Position $position
	 *
	 * @return Selector
	 */
	function setSecondPosition( Position $position ): self
	{
		$this->second_position = $position;

		return $this;
	}


	/**
	 * @return Selector
	 */
	function clear( ): self
	{
		unset($this->first_position, $this->second_position);

		return $this;
	}
}