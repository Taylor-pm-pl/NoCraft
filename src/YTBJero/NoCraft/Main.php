<?php 

declare(strict_types=1);

namespace YTBJero\NoCraft;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\inventory\CraftItemEvent;

use pocketmine\utils\Config;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
	}

	public function onCraft(CraftItemEvent $event){
		$config = $this->getConfig();
        $player = $event->getPlayer();
        if($config->get("all") === true){
        	$event->cancel();
			$player->sendMessage($config->get("cancel-msg"));
        }
		foreach ($event->getOutputs() as $item){
			foreach($this->getConfig()->get("nocraft") as $name){
				if($item->equals(StringToItemParser::getInstance()->parse($name), true)){
					$event->cancel();
					$player->sendMessage($config->get("cancel-msg"));
				}
			}
		}
    }
}
