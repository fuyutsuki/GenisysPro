<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\level\generator\populator;

use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class Setter extends Populator{
	/** @var ChunkManager */
	private $level;
	private $randomAmount;
	private $baseAmount;

	public $block_id = 0;
	public $block_data = 0;	

	public function __construct($id, $data = 0){
		$this->block_id = $id;
		$this->block_data = $data;
	}

	public function setRandomAmount($amount){
		$this->randomAmount = $amount;
	}

	public function setBaseAmount($amount){
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$this->level = $level;
		$amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);

			if($y !== -1 and $this->canStay($x, $y, $z)){
				if(is_array($this->block_id)){
					foreach($this->block_id as $key => $array) {
						list($xx, $yy, $zz, $id, $data) = $array;
						$this->level->setBlockIdAt($x+$xx, $y+$yy, $z+$zz, $id);
						$this->level->setBlockDataAt($x+$xx, $y+$yy, $z+$zz, $data);
					}
				}else{
					$this->level->setBlockIdAt($x, $y, $z, $this->block_id);
					$this->level->setBlockDataAt($x, $y, $z, $this->block_data);
				}
			}
		}
	}

	private function canStay($x, $y, $z){
		$b = $this->level->getBlockIdAt($x, $y, $z);
		return ($b === Block::AIR or $b === Block::SNOW_LAYER) and (Block::get($this->level->getBlockIdAt($x, $y - 1, $z))->isSolid() || $this->level->getBlockIdAt($x, $y - 1, $z) === 237);
	}

	private function getHighestWorkableBlock($x, $z){
		for($y = 127; $y >= 0; --$y){
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if((!is_array($this->block_data) or isset($this->block_data[$b])) and ($b !== Block::AIR and $b !== Block::WATER and $b !== Block::STILL_WATER and $b !== Block::LEAVES and $b !== Block::LEAVES2 and $b !== Block::SNOW_LAYER)){
				break;
			}
		}

		return $y === 0 ? -1 : ++$y;
	}
}