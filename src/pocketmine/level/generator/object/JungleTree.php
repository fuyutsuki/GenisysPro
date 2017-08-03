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

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class JungleTree extends Tree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->leafType = Leaves::JUNGLE;
		$this->type = Wood::JUNGLE;
		$this->treeHeight = 8;
	}

	protected function placeTrunk(ChunkManager $level, $x, $y, $z, Random $random, $trunkHeight){
		// The base dirt block
		$level->setBlockIdAt($x, $y - 1, $z, Block::DIRT);

		for($yy = 0; $yy < $trunkHeight; ++$yy){
			$blockId = $level->getBlockIdAt($x, $y + $yy, $z);
			if(isset($this->overridable[$blockId])){
				$level->setBlockIdAt($x, $y + $yy, $z, $this->trunkBlock);
				$level->setBlockDataAt($x, $y + $yy, $z, $this->type);
			}
		}
		if(mt_rand(0, 1) === 1){
			$this->setCocoa($level, $x, $y + $yy-3, $z);
		}
	}

	protected function setCocoa(ChunkManager $level, $x, $y, $z){
		$id = $level->getBlockIdAt($x, $y, $z);
		$data = $level->getBlockDataAt($x, $y, $z);
		$s = (mt_rand(0, 1)*2)-1;
		if(mt_rand(0, 1)){
			$sx = $x + $s;
			$sz = $z;
		}else{
			$sx = $x;
			$sz = $z + $s;
		}
		$face = $this->getFace($sx, $y, $sz, $x, $y, $z);
		if($face !== 0 and $face !== 1){
			$faces = [
				2 => 8,
				3 => 10,
				4 => 11,
				5 => 9,
			];
			$meta = $faces[$face];
			$level->setBlockIdAt($sx, $y, $sz, Block::COCOA_BLOCK);
			$level->setBlockDataAt($sx, $y, $sz, $meta);
			return true;
		}
		return false;	
	}

	protected function getFace($tx, $ty, $tz, $nx, $ny, $nz){
		switch (true) {
			case $ty < $ny:
				return 0;
			break;
			case $ty > $ny:
				return 1;
			break;
			case $tz < $nz:
				return 2;
			break;
			case $tz > $nz:
				return 3;
			break;
			case $tx < $nx:
				return 4;
			break;
			case $tx > $nx:
				return 5;
			break;
		}
		return 0;
	}
}