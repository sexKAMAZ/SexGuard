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


class Region extends Area
{
	const INDEX_REGION_AREA  = 'position';
	const INDEX_REGION_OWNER = 'owner';


	/**
	 * @param  string  $name
	 * @param  mixed[] $data
	 *
	 * @return Region|null
	 */
	static function make( string $name, array $data )
	{
		$area = $data[self::INDEX_REGION_AREA];

		if( !isset($area) )
		{
			echo "Region::fromData() error: area not found.". PHP_EOL;
			return null;
		}

		$area = parent::fromData($area);

		if( !isset($area) )
		{
			return null;
		}

		$owner = $data[self::INDEX_REGION_OWNER];

		if( !isset($owner) )
		{
			echo "Region::fromData() error: owner not found.". PHP_EOL;
			return null;
		}

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
	 *  ____            _             
	 * |  _ \ ___  __ _(_) ___  _ __  
	 * | (_) / _ \/ _' | |/ _ \| '_ \ 
	 * |  _ <  __/ (_) | | (_) | | | |
	 * |_| \_\___\\__, |_|\___/|_| |_|
	 *            /___/
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

		$this->name        = strtolower($name);
		$this->owner       = strtolower($owner);
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
	function setName( string $name ): self
	{
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
	function setOwner( string $nick ): self
	{
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
		return [
			'position'    => parent::toData(),
			'owner'       => $this->getOwner(),
			'member_list' => $this->getMemberList()->getAll(),
			'flag_list'   => $this->getFlagList()->getAll()
		];
	}
}