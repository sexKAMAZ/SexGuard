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

use sex\guard\util\SexQLite;
use sex\guard\provider\Provider;

use sex\guard\task\async\RegionDeleteTask;
use sex\guard\task\async\RegionInsertTask;


use pocketmine\level\Position;
use pocketmine\Server;


use SQLite3;


class SQLiteProvider implements Provider
{
	const FILENAME    = 'region_data.db';
	const INDEX_TABLE = 'region';


	/**
	 * @param  string $sql
	 *
	 * @return string
	 */
	static function buildQuery( string $sql ): string
	{
		$sql = str_replace('INDEX_TABLE',         self::INDEX_TABLE,       $sql);
		$sql = str_replace('INDEX_NAME',        Region::INDEX_NAME,        $sql);
		$sql = str_replace('INDEX_OWNER',       Region::INDEX_OWNER,       $sql);
		$sql = str_replace('INDEX_MEMBER_LIST', Region::INDEX_MEMBER_LIST, $sql);
		$sql = str_replace('INDEX_FLAG_LIST',   Region::INDEX_FLAG_LIST,   $sql);
		$sql = str_replace('INDEX_LEVEL',       Region::INDEX_LEVEL,       $sql);
		$sql = str_replace('INDEX_MIN_X',       Region::INDEX_MIN_X,       $sql);
		$sql = str_replace('INDEX_MAX_X',       Region::INDEX_MAX_X,       $sql);
		$sql = str_replace('INDEX_MIN_Y',       Region::INDEX_MIN_Y,       $sql);
		$sql = str_replace('INDEX_MAX_Y',       Region::INDEX_MAX_Y,       $sql);
		$sql = str_replace('INDEX_MIN_Z',       Region::INDEX_MIN_Z,       $sql);
		$sql = str_replace('INDEX_MAX_Z',       Region::INDEX_MAX_Z,       $sql);

		return $sql;
	}


	/**
	 * @var string
	 */
	private $region_select_by_name = "
		SELECT * FROM `INDEX_TABLE` WHERE
		(
			`INDEX_NAME` = :INDEX_NAME
		)
	";

	/**
	 * @var string
	 */
	private $region_select_by_player = "
		SELECT * FROM `INDEX_TABLE` WHERE
		(
			`INDEX_OWNER` = :INDEX_OWNER OR

			`INDEX_MEMBER_LIST` LIKE '%:INDEX_MEMBER_LIST%'
		)
	";

	/**
	 * @var string
	 */
	private $region_select_by_position = "
		SELECT * FROM `INDEX_TABLE` WHERE
		(
			`INDEX_LEVEL`  = :INDEX_LEVEL AND
			`INDEX_MIN_X` <= :x           AND
			`INDEX_MAX_X` >= :x           AND
			`INDEX_MIN_Y` <= :y           AND
			`INDEX_MAX_Y` >= :y           AND
			`INDEX_MIN_Z` <= :z           AND
			`INDEX_MAX_Z` >= :z
		)
	";

	/**
	 * @var string
	 */
	private $region_select_by_area = "
		SELECT * FROM `INDEX_TABLE` WHERE
		(
			`INDEX_LEVEL`  = :INDEX_LEVEL AND
			`INDEX_MIN_X` <= :INDEX_MAX_X AND
			`INDEX_MAX_X` >= :INDEX_MIN_X AND
			`INDEX_MIN_Y` <= :INDEX_MAX_Y AND
			`INDEX_MAX_Y` >= :INDEX_MIN_Y AND
			`INDEX_MIN_Z` <= :INDEX_MAX_Z AND
			`INDEX_MAX_Z` >= :INDEX_MIN_Z
		)
	";

	/**
	 * @var string
	 */
	private $region_insert = "
		INSERT OR REPLACE INTO `INDEX_TABLE` VALUES
		(
			:INDEX_NAME,
			:INDEX_OWNER,
			:INDEX_MEMBER_LIST,
			:INDEX_FLAG_LIST,
			:INDEX_LEVEL,
			:INDEX_MIN_X,
			:INDEX_MAX_X,
			:INDEX_MIN_Y,
			:INDEX_MAX_Y,
			:INDEX_MIN_Z,
			:INDEX_MAX_Z
		)
	";

	/**
	 * @var string
	 */
	private $region_delete = "
		DELETE FROM `INDEX_TABLE` WHERE
		(
			`INDEX_NAME` = :INDEX_NAME
		)
	";

	/**
	 * @var string
	 */
	private $region_create = "
		CREATE TABLE IF NOT EXISTS `INDEX_TABLE`
		(
			`INDEX_NAME`        TEXT    NOT NULL,
			`INDEX_OWNER`       TEXT    NOT NULL,
			`INDEX_MEMBER_LIST` TEXT    NOT NULL,
			`INDEX_FLAG_LIST`   TEXT    NOT NULL,
			`INDEX_LEVEL`       TEXT    NOT NULL,
			`INDEX_MIN_X`       INTEGER NOT NULL,
			`INDEX_MAX_X`       INTEGER NOT NULL,
			`INDEX_MIN_Y`       INTEGER NOT NULL,
			`INDEX_MAX_Y`       INTEGER NOT NULL,
			`INDEX_MIN_Z`       INTEGER NOT NULL,
			`INDEX_MAX_Z`       INTEGER NOT NULL,

			UNIQUE(`INDEX_NAME`)
		)
	";

	/**
	 * @var SQLite3
	 */
	private $region_data;

	/**
	 * @var string
	 */
	private $region_file;


	/**
	 *                       _     _
	 *  _ __  _ _______    _(_) __| | ___ _ __
	 * | '_ \| '_/ _ \ \  / | |/ _' |/ _ \ '_/
	 * | (_) | || (_) \ \/ /| | (_) |  __/ |
	 * | ,__/|_| \___/ \__/ |_|\__,_|\___|_|
	 * |_|
	 *
	 * @param string $location
	 */
	function __construct( string $location )
	{
		if( !is_dir($location) )
		{
			@mkdir($location);
		}

		$this->region_file = $location. self::FILENAME;
		$this->region_data = SexQLite::connect($this->region_file);

		$this->region_select_by_name     = self::buildQuery($this->region_select_by_name);
		$this->region_select_by_player   = self::buildQuery($this->region_select_by_player);
		$this->region_select_by_position = self::buildQuery($this->region_select_by_position);
		$this->region_select_by_area     = self::buildQuery($this->region_select_by_area);
		$this->region_insert             = self::buildQuery($this->region_insert);
		$this->region_delete             = self::buildQuery($this->region_delete);
		$this->region_create             = self::buildQuery($this->region_create);

		SexQLite::query($this->region_data, $this->region_create);
	}


	/**
	 * @param  string $name
	 *
	 * @return Region|null
	 */
	function getRegion( string $name )
	{
		$name = strtolower($name);

		$statement = SexQLite::prepare($this->region_data, $this->region_select_by_name);
		$statement = SexQLite::bind($statement, ':'. Region::INDEX_NAME, $name);

		$data = SexQLite::execute($statement);
		$data = SexQLite::fetch($data);

		return Region::make($name, $data);
	}


	/**
	 * @param  string $nick
	 *
	 * @return Region[]
	 */
	function getRegionByPlayer( string $nick ): array
	{
		$nick = strtolower($nick);

		$statement = SexQLite::prepare($this->region_data, $this->region_select_by_player);
		$statement = SexQLite::bind($statement, ':'. Region::INDEX_OWNER, $nick);
		$statement = SexQLite::bind($statement, ':'. Region::INDEX_MEMBER_LIST, $nick);

		$result = SexQLite::execute($statement);
		$list   = [];

		while( count($data = SexQLite::fetch($result)) > 0 )
		{
			$region = Region::make($data[Region::INDEX_NAME], $data);

			if( !isset($region) )
			{
				continue;
			}

			if( $nick != $region->getOwner() )
			{
				continue;
			}

			if( !$region->getMemberList()->exists($nick) )
			{
				continue;
			}

			$list[] = $region;
		}

		return $list;
	}


	/**
	 * @param  Position $position
	 *
	 * @return Region|null
	 */
	function getRegionByPosition( Position $position )
	{
		$level = $position->getLevel()->getName();

		$statement = SexQLite::prepare($this->region_data, $this->region_select_by_position);
		$statement = SexQLite::bind($statement, ':'.  Region::INDEX_LEVEL, $level);
		$statement = SexQLite::bind($statement, ':x', $position->getX());
		$statement = SexQLite::bind($statement, ':y', $position->getY());
		$statement = SexQLite::bind($statement, ':z', $position->getZ());

		$result = SexQLite::execute($statement);
		$list   = [];

		while( count($data = SexQLite::fetch($result)) > 0 )
		{
			$list[] = $data;
		}

		$data = end($list); reset($list);

		return Region::make($data[Region::INDEX_NAME], $data);
	}


	/**
	 * @param  Area $area
	 *
	 * @return Region[]
	 */
	function getRegionByArea( Area $area ): array
	{
		$data      = $area->toData();
		$statement = SexQLite::prepare($this->region_data, $this->region_select_by_area);

		foreach( $data as $index => $value )
		{
			$statement = SexQLite::bind($statement, ":$index", $value);
		}

		$result = SexQLite::execute($statement);
		$list   = [];

		while( count($data = SexQLite::fetch($result)) > 0 )
		{
			$region = Region::make($data[Region::INDEX_NAME], $data);

			if( !isset($region) )
			{
				continue;
			}

			$list[] = $region;
		}

		return $list;
	}


	/**
	 * @param  Region[] $list
	 *
	 * @return Provider
	 */
	function setRegion( Region ...$list ): Provider
	{
		$task = new RegionInsertTask($this->region_file, $this->region_insert, ...$list);

		Server::getInstance()->getScheduler()->scheduleAsyncTask($task);
		return $this;
	}


	/**
	 * @param  string[] $list
	 *
	 * @return Provider
	 */
	function removeRegion( string ...$list ): Provider
	{
		$task = new RegionDeleteTask($this->region_file, $this->region_delete, ...$list);

		Server::getInstance()->getScheduler()->scheduleAsyncTask($task);
		return $this;
	}
}