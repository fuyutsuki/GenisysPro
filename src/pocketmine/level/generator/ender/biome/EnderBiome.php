<?php

namespace pocketmine\level\generator\ender;

use pocketmine\level\generator\biome\Biome;

class EnderBiome extends Biome
{

    public function getName(): string
    {
        return "Ender";
    }

    public function getColor()
    {
        return 0;
    }

    public function __construct()
    {

    }
}