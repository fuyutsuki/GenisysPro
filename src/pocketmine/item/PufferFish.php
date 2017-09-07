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

namespace pocketmine\item;

use pocketmine\entity\Effect;

class PufferFish extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PUFFER_FISH,0, $count, "PufferFish");
	}

	public function getFoodRestore() : int{
		return 1;
	}

	public function getSaturationRestore() : float{
		return 0.2;
    }
    
    public function getAdditionalEffects() : array{
		return  [
			Effect::getEffect(Effect::HUNGER)->setDuration(300)->setAmplifier(2),
			Effect::getEffect(Effect::NAUSEA)->setDuration(300)->setAmplifier(1),
			Effect::getEffect(Effect::POISON)->setDuration(1200)->setAmplifier(3),
        ];
	}

}
