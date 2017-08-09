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

namespace pocketmine\block;


use pocketmine\item\Tool;

class ShulkerBox extends Solid {
	const WHITE = 0;
	const ORANGE = 1;
	const MAGENTA = 2;
	const LIGHT_BLUE = 3;
	const YELLOW = 4;
	const LIME = 5;
	const PINK = 6;
	const GRAY = 7;
	const LIGHT_GRAY = 8;
	const CYAN = 9;
	const PURPLE = 10;
	const BLUE = 11;
	const BROWN = 12;
	const GREEN = 13;
	const RED = 14;
	const BLACK = 15;

	protected $id = self::SHULKER_BOX;

	/**
	 * ShulkerBox constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 6.0;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getResistance(){
		return 30;
	}
	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			0 => "White Shulker Box",
			1 => "Orange Shulker Box",
			2 => "Magenta Shulker Box",
			3 => "Light Blue Shulker Box",
			4 => "Yellow Shulker Box",
			5 => "Lime Shulker Box",
			6 => "Pink Shulker Box",
			7 => "Gray Shulker Box",
			8 => "Light Gray Shulker Box",
			9 => "Cyan Shulker Box",
			10 => "Purple Shulker Box",
			11 => "Blue Shulker Box",
			12 => "Brown Shulker Box",
			13 => "Green Shulker Box",
			14 => "Red Shulker Box",
			15 => "Black Shulker Box",
		];
		return $names[$this->meta & 0x0f];
	}


}