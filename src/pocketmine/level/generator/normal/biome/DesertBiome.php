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

namespace pocketmine\level\generator\normal\biome;

use pocketmine\block\Block;
use pocketmine\level\generator\populator\Setter;

class DesertBiome extends SandyBiome{

	public function __construct(){
		parent::__construct();
		$this->setElevation(45, 100);

		$setter1 = new Setter([
			[ 0,  0, 0, Block::DEAD_BUSH, 0],
			[ 0, -1, 0, Block::SAND, 0],
			[ 0, -2, 0, Block::SANDSTONE, 0],
			[ 0, -3, 0, Block::ORANGE_GLAZED_TERRACOTTA, 0],
			[-1, -3, 0, Block::SANDSTONE, 0],
			[ 1, -3, 0, Block::SANDSTONE, 0],
			[ 0, -3,-1, Block::SANDSTONE, 0],
			[ 0, -3, 1, Block::SANDSTONE, 0],
			[ 0, -4, 0, Block::SANDSTONE, 0],
		], [Block::SAND => true]);
		$setter1->setBaseAmount(1);
		$this->addPopulator($setter1);

		$setter2 = new Setter([
			[ 0,  0, 0, Block::DEAD_BUSH, 0],
			[ 0, -1, 0, Block::SAND, 0],
			[ 0, -2, 0, Block::SANDSTONE, 0],
			[ 0, -3, 0, Block::BROWN_GLAZED_TERRACOTTA, 0],
			[-1, -3, 0, Block::SANDSTONE, 0],
			[ 1, -3, 0, Block::SANDSTONE, 0],
			[ 0, -3,-1, Block::SANDSTONE, 0],
			[ 0, -3, 1, Block::SANDSTONE, 0],
			[ 0, -4, 0, Block::SANDSTONE, 0],
		], [Block::SAND => true]);
		$setter2->setBaseAmount(1);
		$this->addPopulator($setter2);

		$this->temperature = 2;
		$this->rainfall = 0;
	}

	public function getName() : string{
		return "Desert";
	}
}