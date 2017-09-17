<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\event\player\PlayerFishEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\FishingRod;
use pocketmine\level\Level;
use pocketmine\level\sound\SplashSound;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\WaterParticle;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\math\Vector3;


class FishingHook extends Projectile{
	const NETWORK_ID = 77;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.1;
	protected $drag = 0.025;

	public $data = 0;
	public $attractTimer = 100;
	public $coughtTimer = 0;
	public $damageRod = false;
	public $canFishing = false;

	public static $power = 0;
	protected $damage = 0;

	public static $fishes = [ItemItem::RAW_FISH, ItemItem::RAW_SALMON, ItemItem::CLOWN_FISH, ItemItem::PUFFER_FISH];

	public static function getFishes(){
		return self::$fishes;
	}

	public static function setFishes(array $fishes){
		self::$fishes = $fishes;
	}

	public static function setPower(int $power){
		self::$power = $power;
	}

	public function initEntity(){
		parent::initEntity();

		if(isset($this->namedtag->Data)){
			$this->data = $this->namedtag["Data"];
		}
	}

	public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($level, $nbt, $shootingEntity);
		$this->attractTimer = mt_rand(50, 220); // reset timer
		if($level->getWeather()->getWeather() >= 1){
			$this->attractTimer = intval($this->attractTimer/2);
		}
		$this->damage = self::$power;
		if($shootingEntity !== null){
			$this->setDataProperty(self::DATA_OWNER_EID, self::DATA_TYPE_LONG, $shootingEntity->getId());
		}
	}

	public function setData($id){
		$this->data = $id;
	}

	public function getData(){
		return $this->data;
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}
		//$this->gravity = 0.1;
		$this->timings->startTiming();
		$hasUpdate = parent::onUpdate($currentTick);
		if($this->isInsideOfWater()){
			$this->motionX *= 8/9;
			$this->motionY += 0.15;
			//$this->gravity = -0.01;
			$this->motionZ *= 8/9;
			$this->motionChanged = true;
			$this->teleport($this->getPosition()->add($this->getMotion()));
			$hasUpdate = true;
			$this->canFishing = true;
		}
		if($this->canFishing){
			if($this->attractTimer === 0 && mt_rand(0, 100) <= 30){ // chance, that a fish bites
				$this->coughtTimer = mt_rand(10, 35); // random delay to catch fish
				$this->attractTimer = mt_rand(80, 240); // reset timer
				if($this->level->getWeather()->getWeather() >= 1){
					$this->attractTimer = intval($this->attractTimer/2);
				}
				$this->attractFish();
					//if($this->shootingEntity instanceof Player) $this->shootingEntity->sendTip("A fish bites!");
			}elseif($this->attractTimer > 0){
				$this->attractTimer--;
				var_dump($this->attractTimer);
			}
			if($this->coughtTimer > 0){
				$this->coughtTimer--;
				$this->fishBites();
			}
		}
		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function fishBites(){
		if($this->shootingEntity instanceof Player){
			$particle = new BubbleParticle($this);
			$sound = new SplashSound($this);
			$level = $this->shootingEntity->getLevel();
			$level->addParticle($particle);
			$level->addSound($sound);
			$pk = new EntityEventPacket();
			$pk->eid = $this->shootingEntity->getId();//$this or $this->shootingEntity
			$pk->event = EntityEventPacket::FISH_HOOK_HOOK;
			$this->server->broadcastPacket($this->shootingEntity->hasSpawned, $pk);
		}
	}

	public function attractFish(){
		if($this->shootingEntity instanceof Player){
			$pk = new EntityEventPacket();
			$pk->eid = $this->shootingEntity->getId();//$this or $this->shootingEntity
			$pk->event = EntityEventPacket::FISH_HOOK_BUBBLE;
			$this->server->broadcastPacket($this->shootingEntity->hasSpawned, $pk);
		}
	}

	public function reelLine(){
		$this->damageRod = false;

		if($this->shootingEntity instanceof Player && $this->coughtTimer > 0){
			$fishes = self::getFishes();
			$fish = array_rand($fishes, 1);
			$item = ItemItem::get($fishes[$fish]);
			$this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new PlayerFishEvent($this->shootingEntity, $item, $this));
			if(!$ev->isCancelled()){
				//$this->shootingEntity->getInventory()->addItem($item);
				$rod = clone $this->shootingEntity->getInventory()->getItemInHand();
				if($rod->getDamage() >= $rod->getMaxDurability()){
					$rod = ItemItem::get(0);
				}else{
					$rod->setDamage($rod->getDamage()+6);
				}
				$this->shootingEntity->getInventory()->setItemInHand($rod);
				$po1 = $this->shootingEntity->getPosition();
				$po2 = $this->getPosition();
				$v = new Vector3($po1->x - $po2->x, (3+$po1->y - $po2->y), $po1->z - $po2->z);
				$v = $v->normalize()->multiply($po1->distance($po2)*0.125);
				$this->shootingEntity->getLevel()->dropItem($po2, $item, $v);
				$particle = new WaterParticle($this);
				$this->shootingEntity->getLevel()->addParticle($particle);
				if($this->server->expEnabled) $this->shootingEntity->addXp(mt_rand(1, 6));
				$this->damageRod = true;
			}
		}

		if($this->shootingEntity instanceof Player){
			$this->shootingEntity->unlinkHookFromPlayer();
		}

		if(!$this->closed){
			$this->kill();
			$this->close();
		}

		return $this->damageRod;
	}

	public function kill(){
		parent::kill();
		if($this->shootingEntity instanceof Player){
			$this->shootingEntity->fishingHook->reelLine();
			$this->shootingEntity->linkHookToPlayer(null);
		}
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = FishingHook::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
