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
use sex\guard\object\Region;
use sex\guard\object\Selector;


use pocketmine\level\Position;
use pocketmine\level\Level;

use pocketmine\math\Vector3;
use pocketmine\Server;


class Area
{
	const SIDE_NORTH_EAST = 0;
	const SIDE_SOUTH_EAST = 1;
	const SIDE_SOUTH_WEST = 2;
	const SIDE_NORTH_WEST = 3;


	/**
	 * @param  Selector $selector
	 *
	 * @return Area|null
	 */
	static function fromSelector( Selector $selector )
	{
		$first = $selector->getFirstPosition();

		if( !isset($first) )
		{
			return null;
		}

		$second = $selector->getFirstPosition();

		if( !isset($second) )
		{
			return null;
		}

		return self::fromPosition($first, $second);
	}


	/**
	 * @param  Position $pos_1
	 * @param  Position $pos_2
	 *
	 * @return Area|null
	 */
	static function fromPosition( Position $pos_1, Position $pos_2 )
	{
		if( $pos_1->getLevel()->getName() != $pos_2->getLevel()->getName() )
		{
			return null;
		}

		$x = [ min($pos_1->getX(), $pos_2->getX()), max($pos_1->getX(), $pos_2->getX()) ];
		$y = [ min($pos_1->getY(), $pos_2->getY()), max($pos_1->getY(), $pos_2->getY()) ];
		$z = [ min($pos_1->getZ(), $pos_2->getZ()), max($pos_1->getZ(), $pos_2->getZ()) ];

		$min = new Vector3($x[0], $y[0], $z[0]);
		$max = new Vector3($x[1], $y[1], $z[1]);

		return new Area($pos_1->getLevel(), $min, $max);
	}


	/**
	 * @param  mixed[] $data
	 *
	 * @return Area|null
	 */
	static function fromData( array $data )
	{
		if( !isset($data[Region::INDEX_LEVEL]) )
		{
			return null;
		}

		$level = Server::getInstance()->getLevelByName($data[Region::INDEX_LEVEL]);

		if( !isset($level) )
		{
			return null;
		}

		$min = [];

		foreach( [Region::INDEX_MIN_X, Region::INDEX_MIN_Y, Region::INDEX_MIN_Z] as $key => $coord )
		{
			if( !isset($data[$coord]) )
			{
				return null;
			}

			$min[$key] = $data[$coord];
		}

		$max = [];

		foreach( [Region::INDEX_MAX_X, Region::INDEX_MAX_Y, Region::INDEX_MAX_Z] as $key => $coord )
		{
			if( !isset($data[$coord]) )
			{
				return null;
			}

			$max[$key] = $data[$coord];
		}

		return new Area($level, new Vector3(...$min), new Vector3(...$max));
	}


	/**
	 * @param  Vector3 $vector
	 *
	 * @return int
	 */
	static function getLevelSideByVector( Vector3 $vector ): int
	{
		switch( true )
		{
			case $vector->getX() > 0 and 0 > $vector->getZ(): return self::SIDE_NORTH_EAST;
			case $vector->getX() > 0 and 0 < $vector->getZ(): return self::SIDE_SOUTH_EAST;
			case $vector->getX() < 0 and 0 < $vector->getZ(): return self::SIDE_SOUTH_WEST;
			case $vector->getX() < 0 and 0 > $vector->getZ(): return self::SIDE_NORTH_WEST;
		}

		return self::SIDE_NORTH_EAST;
	}


	/**
	 * @var Level
	 */
	private $level;

	/**
	 * @var Vector3
	 */
	private $min_vector;

	/**
	 * @var Vector3
	 */
	private $max_vector;


	/**
	 *
	 *   __ _ _ _____  __ _
	 *  / _' | '_/ _ \/ _' |
	 * | (_) | ||  __/ (_) |
	 *  \__,_|_| \___\\__,_|
	 *
	 *
	 * @param Level   $level
	 * @param Vector3 $min
	 * @param Vector3 $max
	 */
	function __construct( Level $level, Vector3 $min, Vector3 $max )
	{
		$this->level      = $level;
		$this->min_vector = $min;
		$this->max_vector = $max;
	}


	/**
	 * @return Level
	 */
	function getLevel( ): Level
	{
		return $this->level;
	}


	/**
	 * @return Vector3
	 */
	function getMinVector( ): Vector3
	{
		return $this->min_vector;
	}


	/**
	 * @return Vector3
	 */
	function getMaxVector( ): Vector3
	{
		return $this->max_vector;
	}


	/**
	 * @return Vector3
	 */
	function getCenterVector( ): Vector3
	{
		$min = $this->getMinVector();
		$max = $this->getMaxVector();

		$x = round(($min->getX() + $max->getX()) / 2);
		$y = round(($min->getX() + $max->getX()) / 2);
		$z = round(($min->getZ() + $max->getZ()) / 2);

		return new Vector3($x, $y, $z);
	}


	/**
	 * @return int
	 */
	function getLevelSide( ): int
	{
		$vector = $this->getCenterVector();

		return self::getLevelSideByVector($vector);
	}


	/**
	 * @param  bool $ignore_y
	 *
	 * @return int
	 */
	function getSize( bool $ignore_y = false ): int
	{
		$min = $this->getMinVector();
		$max = $this->getMaxVector();

		$x = [ $min->getX(), $max->getX() ];
		$y = [ $min->getY(), $max->getY() ];
		$z = [ $min->getZ(), $max->getZ() ];

		if( $ignore_y )
		{
			$y = [ 0, 1 ];
		}

		return ($x[1] - $x[0]) * ($y[1] - $y[0]) * ($z[1] - $z[0]);
	}


	/**
	 * @return Position
	 */
	function getRandomPosition( ): Position
	{
		$level = $this->getLevel();
		$min   = $this->getMinVector();
		$max   = $this->getMaxVector();

		while( true )
		{
			$x = mt_rand($min->getX(), $max->getX());
			$z = mt_rand($min->getZ(), $max->getZ());

			if( !$level->isChunkLoaded($x, $z) )
			{
				$level->loadChunk($x, $z);
			}

			$position = $level->getSafeSpawn(new Vector3($x, $max->getY(), $z));

			if( !$position )
			{
				continue; // chunk not loaded.
			}

			return $position;
		}
	}


	/**
	 * @param  Area $area
	 *
	 * @return bool
	 */
	function intersectsWith( Area $area ): bool
	{
		if(
			$this->getMinVector()->getX() <= $area->getMaxVector()->getX() and
			$this->getMaxVector()->getX() >= $area->getMinVector()->getX() and
			$this->getMinVector()->getY() <= $area->getMaxVector()->getY() and
			$this->getMaxVector()->getY() >= $area->getMinVector()->getY() and
			$this->getMinVector()->getZ() <= $area->getMaxVector()->getZ() and
			$this->getMaxVector()->getZ() >= $area->getMinVector()->getZ()
		) {
			return true;
		}

		return false;
	}


	/**
	 * @param  Vector3 $vector
	 *
	 * @return bool
	 */
	function isVectorInside( Vector3 $vector ): bool
	{
		if(
			$this->getMinVector()->getX() <= $vector->getX() and
			$this->getMaxVector()->getX() >= $vector->getX() and
			$this->getMinVector()->getY() <= $vector->getY() and
			$this->getMaxVector()->getY() >= $vector->getY() and
			$this->getMinVector()->getZ() <= $vector->getZ() and
			$this->getMaxVector()->getZ() >= $vector->getZ()
		) {
			return true;
		}

		return false;
	}


	/**
	 * @return mixed[]
	 */
	function toData( ): array
	{
		return [
			Region::INDEX_LEVEL => $this->getLevel()->getName(),
			Region::INDEX_MIN_X => $this->getMinVector()->getX(),
			Region::INDEX_MAX_X => $this->getMaxVector()->getX(),
			Region::INDEX_MIN_Y => $this->getMinVector()->getY(),
			Region::INDEX_MAX_Y => $this->getMaxVector()->getY(),
			Region::INDEX_MIN_Z => $this->getMinVector()->getZ(),
			Region::INDEX_MAX_Z => $this->getMaxVector()->getZ()
		];
	}
}