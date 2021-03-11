<?php


namespace tatchan\ReverseCraft;


use pocketmine\form\Form;
use pocketmine\inventory\CraftingRecipe;
use pocketmine\item\Item;
use pocketmine\Player;

class selectForm implements Form
{


    /** @var CraftingRecipe[] */
    private $recipes;
    /** @var Item */
    private $item;

    public function __construct(array $recipes, Item $item) {
        $this->recipes = array_values($recipes);
        $this->item = $item;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;

        $player->sendForm(new CountForm($this->recipes[$data], $this->item));
    }

    public function jsonSerialize() {
        $buttons = [];
        foreach ($this->recipes as $recipe) {
            $buttons[] = [
                "text" => implode(",", Utils::allignItems($recipe->getIngredientList())),
            ];
        }
        return [
            "type" => "form",
            "title" => "レシピを選んでね",
            "content" => "",
            "buttons" => $buttons,

        ];
    }
}