<?php 

declare(strict_types=1);

namespace YTBJero\NoCraft;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\inventory\CraftItemEvent;

use pocketmine\utils\Config;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Main extends PluginBase implements Listener{

	private static $ids = [];

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
		foreach($this->getConfig()->get("nocraft") as $id => $status){
			if($this->getConfig()->get("all") === true) return; 
			if($status === false) return;
			$ids = explode(":", $id);
			self::$ids[$ids[0] . ":" . $ids[1]] = true;
		}
	}

	public function onCraft(CraftItemEvent $event){
		$config = $this->getConfig();
        $player = $event->getPlayer();
        if($config->get("all") === true){
        	$event->cancel();
			$player->sendMessage($config->get("cancel-msg"));
        }
		foreach ($event->getOutputs() as $item){
			if (array_key_exists($item->getId() . ":" . $this->getDamage($item), self::$ids)){
				$event->cancel();
				$player->sendMessage($config->get("cancel-msg"));
			}
		}
    }
	
	public function getDamage(Item $item){
		if($item instanceof Tool){
			return $item->getDamage();
		}
		return 0;
	}
}