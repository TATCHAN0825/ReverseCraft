<?php



namespace tatchan\ReverseCraft;

use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$item = ItemFactory::get(ItemIds::DIAMOND_BLOCK,0,1);
        $this->myConfig = new Config($this->getDataFolder() . "MyConfig.yml", Config::YAML);
        $this->myConfig->set("config",(Server::getInstance()->getCraftingManager()->getShapedRecipes()));
		$this->myConfig->save();
	}

	public function onDisable() : void{
		$this->getLogger()->info("Bye");
	}
}
