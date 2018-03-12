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
class FlagList
{
	const INDEX_FLAGLIST = 'flag_list';


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
	 * @param mixed[]
	 */
	static function fromData( array $data )
	{
		$list = $data[self::INDEX_FLAGLIST];

		if( !isset($list) )
		{
			echo "MemberList::fromData() error: flag list not found.". PHP_EOL;
			return null;
		}

		return new FlagList($list);
	}


	/**
	 * @var bool[]
	 */
	private $list = [];


	/**
	 *  _______             _    _     _
	 * |  ___/ | __ _  __ _| |  (_)___| |__
	 * | |__ | |/ _' |/ _` | |  | / __| __/
	 * |  _/ | | (_) | (_) | |__| \__ \ |_
	 * |_|   |_|\__,_|\__, |____|_|___/\__\
	 *                /___/
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
	 * @return MemberList
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
	function set( string $flag, bool $value ): self
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
	 * @return MemberList
	 */
	function exists( string $flag ): bool
	{
		$flag = strtolower($flag);

		return isset($this->list[$flag]);
	}
}