<?php namespace sex\guard\task\async;


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
use sex\guard\util\SexQLite;


use pocketmine\scheduler\AsyncTask;


class RegionInsertTask extends AsyncTask
{
	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @var string
	 */
	private $region_list;


	/**
	 *  _            _
	 * | |____ _ ___| | __
	 * |  _/ _' / __| |/ /
	 * | || (_) \__ \   <
	 *  \__\__,_|___/_|\_\
	 *
	 *
	 * @param string   $file
	 * @param string   $sql
	 * @param Region[] $list
	 */
	function __construct( string $file, string $sql, Region ...$list )
	{
		foreach( $list as $index => $region )
		{
			$list[$index]                     = $region->toData();
			$list[$index][Region::INDEX_NAME] = $region->getName();
		}

		$this->file        = $file;
		$this->sql         = $sql;
		$this->region_list = serialize($list);
	}


	function onRun( )
	{
		$list = unserialize($this->region_list);
		$link = SexQLite::connect($this->file);

		foreach( $list as $data )
		{
			$statement = SexQLite::prepare($link, $this->sql);

			foreach( $data as $index => $value )
			{
				$statement = SexQLite::bind($statement, ":$index", $value);
			}

			SexQLite::execute($statement);
		}

		SexQLite::close($link);
	}
}