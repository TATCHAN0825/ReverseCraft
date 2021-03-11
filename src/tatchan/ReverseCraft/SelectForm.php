<?php


namespace tatchan\ReverseCraft;


use pocketmine\form\Form;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\Player;

class selectForm implements Form
{
    /** @var ShapedRecipe[]|ShapelessRecipe[] 本当は(ShapedRecipe|ShapelessRecipe)[] */
    private $recipes;

    public function __construct(array $recipes) {
        $this->recipes = array_values($recipes);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;

        $player->sendForm(new CountForm($this->recipes[$data], $player));
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