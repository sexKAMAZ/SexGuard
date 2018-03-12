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
use pocketmine\level\Level;

use pocketmine\math\Vector3;
use pocketmine\Server;


class Area
{
	const INDEX_AREA_LEVEL     = 'level';
	const INDEX_AREA_MINVECTOR = 'min_vector';
	const INDEX_AREA_MAXVECTOR = 'max_vector';


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
		$level = Server::getInstance()->getLevelByName($data[self::INDEX_AREA_LEVEL] ?? '');

		if( !isset($level) )
		{
			echo "Area::fromData() error: level not found.". PHP_EOL;
			return null;
		}

		$min = $data[self::INDEX_AREA_MINVECTOR];

		if( !isset($min) )
		{
			echo "Area::fromData() error: min position not found.". PHP_EOL;
			return null;
		}

		$max = $data[self::INDEX_AREA_MAXVECTOR];

		if( !isset($max) )
		{
			echo "Area::fromData() error: max position not found.". PHP_EOL;
			return null;
		}

		for( $i = 0; $i <= 2; $i++ )
		{
			if( !isset($min[$i]) )
			{
				echo "Area::fromData() error: min[$i] position not found.". PHP_EOL;
				return null;
			}

			if( !isset($max[$i]) )
			{
				echo "Area::fromData() error: max[$i] position not found.". PHP_EOL;
				return null;
			}
		}

		return new Area($level, new Vector3(...$min), new Vector3(...$max));
	}


	/**
	 * @var Level
	 */
	private $level;

	/**
	 * @var Vector3
	 */
	private $min;

	/**
	 * @var Vector3
	 */
	private $max;


	/**
	 *     _                   
       *    / \   _ _____  __ _ 
       *   / _ \ | '_/ _ \/ _' |
       *  / ___ \| ||  __/ (_) |
       * /_/   \_|_| \___\\__,_|
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
	 * @return mixed[]
	 */
	function toData( ): array
	{
		return [
			self::INDEX_AREA_LEVEL     => $this->getLevel()->getName(),
			self::INDEX_AREA_MINVECTOR => [
				$this->getMinVector()->getFloorX(),
				$this->getMinVector()->getFloorY(),
				$this->getMinVector()->getFloorZ()
			],
			self::INDEX_AREA_MAXVECTOR => [
				$this->getMaxVector()->getFloorX(),
				$this->getMaxVector()->getFloorY(),
				$this->getMaxVector()->getFloorZ()
			]
		];
	}
}