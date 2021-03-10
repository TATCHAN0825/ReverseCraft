<?php


namespace tatchan\ReverseCraft;


use pocketmine\form\Form;
use pocketmine\inventory\CraftingRecipe;
use pocketmine\item\Item;
use pocketmine\Player;

class CountForm implements Form
{
    /**@var CraftingRecipe */
    private $recipe;
    /** @var Item */
    private $item;

    public function __construct(CraftingRecipe $recipe, Item $item) {
        $this->recipe = $recipe;
        $this->item = $item;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        $count = $data[0];
        $inv = $player->getInventory();
        foreach ($this->recipe->getIngredientList() as $item) {
            for ($i = 0; $i < $count; $i++) {
                if ($inv->canAddItem($item)) {
                    if ($item->getDamage() == -1 or $item->getDamage() == 32767) {
                        $item->setDamage(0);
                    }
                    $inv->addItem($item);
                } else {
                    $player->getLevel()->dropItem($player, $item);
                }

            }

        }
        $removeitem = $inv->getItemInHand();
        $removeitem->setCount($count);
        $inv->removeItem($removeitem);
    }

    public function jsonSerialize() {

        return [
            "type" => "custom_form",
            "title" => "個数を決めてね",
            "content" => [
                [
                    "type" => "slider",
                    "text" => "変換する数を決めてね",
                    "min" => 1,
                    "max" => $this->item->getCount(),

                ]
            ]
        ];
    }
}