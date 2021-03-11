<?php


namespace tatchan\ReverseCraft;

use pocketmine\block\BlockIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function 作業台を触ったとき(PlayerInteractEvent $event) {
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
        if ($event->getBlock()->getId() !== BlockIds::CRAFTING_TABLE) return;
        if (!$event->getPlayer()->isSneaking()) return;
        $recipes = [];
        $item = $event->getPlayer()->getInventory()->getItemInHand();

        foreach ($this->getServer()->getCraftingManager()->matchRecipeByOutputs([$item]) as $recipe) {
            if (!($recipe instanceof ShapedRecipe or $recipe instanceof ShapelessRecipe)) {
                continue;
            }
            foreach ($recipe->getIngredientList() as $item) {
                if ($item->getName() == "Unknown") {
                    continue 2;
                }
            }
            $recipes[] = $recipe;
        }

        $event->setCancelled();

        if (count($recipes) == 0) {
            $event->getPlayer()->sendMessage("§cレシピが見つからなかった");
            return;
        }

        $event->getPlayer()->sendForm(new selectForm($recipes));
    }
}
