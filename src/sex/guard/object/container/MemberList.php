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
class MemberList
{
	const INDEX_MEMBERLIST = 'member_list';


	/**
	 * @param mixed[]
	 */
	static function fromData( array $data )
	{
		$list = $data[self::INDEX_MEMBERLIST];

		if( !isset($list) )
		{
			echo "MemberList::fromData() error: member list not found.". PHP_EOL;
			return null;
		}

		return new MemberList(...$list);
	}


	/**
	 * @var string[]
	 */
	private $list = [];


	/**
	 *  __  __              _              _    _     _
	 * |  \/  | ___ _ __ _ | |__   ___ _ _| |  (_)___| |__
	 * | |\/| |/ _ \ '  ' \| '_ \ / _ \ '_| |  | / __|  _/
	 * | |  | |  __/ || || | (_) |  __/ | | |__| \__ \ |_
	 * |_|  |_|\___|_||_||_|_.__/ \___|_| |____|_|___/\__\
	 *
	 *
	 * @param string[] $list
	 */
	function __construct( string ...$list )
	{
		$this->list = array_map('strtolower', $list);
	}


	/**
	 * @return string[]
	 */
	function getAll( ): array
	{
		return $this->list;
	}


	/**
	 * @param  string $nick
	 *
	 * @return MemberList
	 */
	function add( string $nick ): self
	{
		$this->list[] = strtolower($nick);

		return $this;
	}


	/**
	 * @param  string $nick
	 *
	 * @return MemberList
	 */
	function remove( string $nick ): self
	{
		$this->list[] = strtolower($nick);

		return $this;
	}


	/**
	 * @param  string $nick
	 *
	 * @return bool
	 */
	function exists( string $nick ): bool
	{
		$nick = strtolower($nick);

		return isset($this->list[$nick]);
	}
}