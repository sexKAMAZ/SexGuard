<?php namespace sex\guard\object\container;


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


class FlagList
{
	/**
	 * @var bool[]
	 */
	static $default_flag_list = [
		'interact' => true,
		'teleport' => true,
		'explode'  => false,
		'change'   => false,
		'bucket'   => false,
		'damage'   => true,
		'chest'    => false,
		'place'    => false,
		'break'    => false,
		'sleep'    => false,
		'decay'    => true,
		'drop'     => true,
		'chat'     => true,
		'pvp'      => false,
		'mob'      => true
	];


	/**
	 * @param  mixed[]
	 *
	 * @return FlagList|null
	 */
	static function fromData( array $data )
	{
		if( !isset($data[Region::INDEX_FLAG_LIST]) )
		{
			// return null; // user can damage region data.

			$list = '';
		}

		$list = $list ?? explode(':', $data[Region::INDEX_FLAG_LIST]);

		foreach( $list as $index => $flag )
		{
			$flag = explode('=', $flag);

			unset($list[$index]);

			if( count($flag) != 2 )
			{
				continue;
			}

			$list[$flag[0]] = ($flag[1] == 'true');
		}

		return new FlagList($list);
	}


	/**
	 * @var bool[]
	 */
	private $list = [];


	/**
	 *                  _        _
	 *   _______  _ __ | |____ _(_)_ __   ___ _ __
	 *  / __/ _ \| '_ \|  _/ _' | | '_ \ / _ \ '_/
	 * | (_| (_) | | | | || (_) | | | | |  __/ |
	 *  \___\___/|_| |_|\__\__,_|_|_| |_|\___|_|
	 *
	 *
	 * @param bool[] $list
	 */
	function __construct( array $list )
	{
		foreach( $list as $flag => $value )
		{
			if( !isset(self::$default_flag_list[$flag]) )
			{
				unset($list[$flag]);
			}

			if( !is_bool($value) )
			{
				$list[$flag] = self::$default_flag_list[$flag];
			}
		}

		foreach( self::$default_flag_list as $flag => $value )
		{
			if( !isset($list[$flag]) )
			{
				$list[$flag] = $value;
			}
		}

		$this->list = $list;
	}


	/**
	 * @return bool[]
	 */
	function getAll( ): array
	{
		return $this->list;
	}


	/**
	 * @param  string $flag
	 *
	 * @return bool
	 */
	function get( string $flag ): bool
	{
		$flag = strtolower($flag);

		if( !isset($this->list[$flag]) )
		{
			return self::$default_flag_list[$flag];
		}

		return $this->list[$flag];
	}


	/**
	 * @param  string $flag
	 * @param  bool   $value
	 *
	 * @return FlagList
	 */
	function set( string $flag, bool $value ): FlagList
	{
		$flag = strtolower($flag);

		if( isset($this->list[$flag]) )
		{
			$this->list[$flag] = $value;
		}

		return $this;
	}


	/**
	 * @param  string $flag
	 *
	 * @return bool
	 */
	function exists( string $flag ): bool
	{
		$flag = strtolower($flag);

		return isset($this->list[$flag]);
	}


	/**
	 * @return string
	 */
	function toString( ): string
	{
		$list = [];

		foreach( $this->list as $flag => $value )
		{
			$list[] = $flag. '='. ($value ? 'true' : 'false');
		}

		return implode(':', $list);
	}
}