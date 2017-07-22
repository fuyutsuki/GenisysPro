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
use pocketmine\block\Sapling;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\Tree;
class Aether extends GrassyBiome{

	public function __construct(){
		parent::__construct();
		$this->setElevation(60, 256);

		$array = [
			Block::get(Block::CONCRETE_POWDER, 0),
			Block::get(Block::SNOW_BLOCK, 0),
			Block::get(Block::SNOW_BLOCK, 0),
			Block::get(Block::SNOW_BLOCK, 0),
			Block::get(Block::SNOW_BLOCK, 0),
			Block::get(Block::SNOW_BLOCK, 0),
		];
		for($i = 0; $i < 256; $i++){
			$array[] = Block::get(Block::AIR, 0);
		}
		$this->setGroundCover($array);

		$trees = new Tree([Block::QUARTZ_BLOCK, Block::CONCRETE, Sapling::OAK]);
		$trees->setBaseAmount(1);
		$this->addPopulator($trees);

		$this->temperature = 2;
		$this->rainfall = 0;
	}

	public function getName() : string{
		return "Aether";
	}
}