<?php namespace sex\guard\util;


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
use Exception;
use InvalidArgumentException;

use SQLite3Result;
use SQLite3Stmt;
use SQLite3;


class SexQLite
{
	/**
	 * @var string
	 */
	private static $file;


	/**
	 *  _____            ___  _    _ _
	 * / ___/  _____  __/ _ \| |  (_) |_____
	 * \___ \ / _ \ \/ / | | | |  | | __/ _ \
	 *  ___) |  __/>  <| |_| | |__| | ||  __/
	 * /____/ \___/_/\_\\__,_\____|_|\__\___/
	 *
	 *
	 * @param  string $file
	 *
	 * @return SQLite3
	 */
	static function connect( string $file = '' ): SQLite3
	{
		if( !empty($file) )
		{
			self::$file = $file;

			return new SQLite3($file);
		}

		if( !isset(self::$file) )
		{
			throw new Exception("SexQLite error: file not found.");
		}

		return new SQLite3(self::$file);
	}


	/**
	 * @param  SQLite3 $link
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	static function close( SQLite3 $link ): bool
	{
		if( $link->close() )
		{
			return true;
		}

		throw new Exception("SexQLite error: trying to close connection.");
	}


	/**
	 * @param  string $sql
	 *
	 * @return SQLite3Result
	 */
	static function query( SQLite3 $link, string $sql ): SQLite3Result
	{
		return $link->query($sql);
	}


	/**
	 * @param  SQLite3 $link
	 * @param  string  $sql
	 *
	 * @return SQLite3Stmt
	 */
	static function prepare( SQLite3 $link, string $sql ): SQLite3Stmt
	{
		return $link->prepare($sql);
	}


	/**
	 * @param  SQLite3Stmt $statement
	 * @param  string      $param
	 * @param  mixed       $value
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	static function bind( SQLite3Stmt $statement, string $param, $value ): bool
	{
		if( $statement->bindValue($param, self::type($value)) )
		{
			return $statement;
		}

		throw new Exception("SexQLite error: trying to bind $value in $param.");
	}


	/**
	 * @param  SQLite3Stmt $statement
	 *
	 * @return SQLite3Result
	 */
	static function execute( SQLite3Stmt $statement ): SQLite3Result
	{
		return $statement->execute();
	}


	/**
	 * @param  SQLite3Result $result
	 *
	 * @return mixed[]
	 */
	static function fetch( SQLite3Result $result ): array
	{
		$array = $result->fetchArray(SQLITE3_ASSOC);

		if( !$array )
		{
			return [];
		}

		return $array;
	}


	/**
	 * @param  SQLite3Result $result
	 *
	 * @return int
	 */
	static function num( SQLite3Result $result ): int
	{
		return $result->numColumns();
	}


	/**
	 * @param  mixed $value
	 *
	 * @return int
	 *
	 * @throws InvalidArgumentException
	 */
	private static function type( $value ): int
	{
		$type = gettype($value);

		switch( $type )
		{
			case 'double':  return SQLITE3_FLOAT;
			case 'integer': return SQLITE3_INTEGER;
			case 'boolean': return SQLITE3_INTEGER;
			case 'NULL':    return SQLITE3_NULL;
			case 'string':  return SQLITE3_TEXT;
		}

		throw new InvalidArgumentException("SexQLite error: Invalid type '$type'.");
	}
}