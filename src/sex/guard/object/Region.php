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
use sex\guard\object\Area;
use sex\guard\object\container\FlagList;
use sex\guard\object\container\MemberList;


use pocketmine\math\Vector3;
use pocketmine\level\Level;


use InvalidArgumentException;


class Region extends Area
{
	const INDEX_NAME        = 'name';
	const INDEX_OWNER       = 'owner';
	const INDEX_MEMBER_LIST = 'member_list';
	const INDEX_FLAG_LIST   = 'flag_list';
	const INDEX_LEVEL       = 'level';
	const INDEX_MIN_X       = 'min_x';
	const INDEX_MAX_X       = 'max_x';
	const INDEX_MIN_Y       = 'min_y';
	const INDEX_MAX_Y       = 'max_y';
	const INDEX_MIN_Z       = 'min_z';
	const INDEX_MAX_Z       = 'max_z';


	/**
	 * @param  string  $name
	 * @param  mixed[] $data
	 *
	 * @return Region|null
	 */
	static function make( string $name, array $data )
	{
		if( empty($name) )
		{
			return null;
		}

		if( !isset($data[self::INDEX_OWNER]) or empty($data[self::INDEX_OWNER]) )
		{
			return null;
		}

		$owner  = $data[self::INDEX_OWNER];
		$member = MemberList::fromData($data);

		if( !isset($member) )
		{
			return null;
		}

		$flag = FlagList::fromData($data);

		if( !isset($flag) )
		{
			return null;
		}

		$area = parent::fromData($data);

		if( !isset($area) )
		{
			return null;
		}

		return self::fromObject($name, $area, $owner, $member, $flag);
	}


	/**
	 * @param  string     $name
	 * @param  Area       $area
	 * @param  string     $owner
	 * @param  MemberList $member
	 * @param  FlagList   $flag
	 *
	 * @return Region|null
	 */
	static function fromObject(
		string $name, Area $area, string $owner, MemberList $member, FlagList $flag
	) {
		return new Region(
			$name,  $area->getLevel(), $area->getMinVector(), $area->getMaxVector(),
			$owner, $member, $flag
		);
	}


	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $owner;

	/**
	 * @var MemberList
	 */
	private $member_list;

	/**
	 * @var FlagList
	 */
	private $flag_list;


	/**
	 *                _
	 *  _ _____  __ _(_) ___  _ __
	 * | '_/ _ \/ _' | |/ _ \| '_ \
	 * | ||  __/ (_) | | (_) | | | |
	 * |_| \___\\__, |_|\___/|_| |_|
	 *          /___/
	 *
	 * @param string     $name
	 * @param Level      $level
	 * @param Vector3    $min
	 * @param Vector3    $max
	 * @param string     $owner
	 * @param MemberList $member
	 * @param FlagList   $flag
	 */
	function __construct(
		string $name,  Level $level, Vector3 $min, Vector3 $max,
		string $owner, MemberList $member, FlagList $flag
	) {
		parent::__construct($level, $min, $max);

		$this->setName($name);
		$this->setOwner($owner);

		$this->member_list = $member;
		$this->flag_list   = $flag;
	}


	/**
	 * @return string
	 */
	function getName( ): string
	{
		return $this->name;
	}


	/**
	 * @param  string $name
	 *
	 * @return Region
	 */
	function setName( string $name ): Region
	{
		if( empty($name) )
		{
			throw new InvalidArgumentException('Region\'s name cannot be empty!');
		}

		$this->name = strtolower($name);

		return $this;
	}


	/**
	 * @return string
	 */
	function getOwner( ): string
	{
		return $this->owner;
	}


	/**
	 * @param  string $nick
	 *
	 * @return Region
	 */
	function setOwner( string $nick ): Region
	{
		if( empty($nick) )
		{
			throw new InvalidArgumentException('Owner\'s name cannot be empty!');
		}

		$this->owner = strtolower($nick);

		return $this;
	}


	/**
	 * @return MemberList
	 */
	function getMemberList( ): MemberList
	{
		return $this->member_list;
	}


	/**
	 * @return FlagList
	 */
	function getFlagList( ): FlagList
	{
		return $this->flag_list;
	}


	/**
	 * @return mixed[]
	 */
	function toData( ): array
	{
		$data = [
			self::INDEX_OWNER       => $this->getOwner(),
			self::INDEX_MEMBER_LIST => $this->getMemberList()->toString(),
			self::INDEX_FLAG_LIST   => $this->getFlagList()->toString()
		];

		foreach( parent::toData() as $index => $value )
		{
			$data[$index] = $value;
		}

		return $data;
	}
}