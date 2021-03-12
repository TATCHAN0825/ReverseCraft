<?php


namespace tatchan\ReverseCraft;


use LogicException;
use pocketmine\form\Form;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\Player;

class CountForm implements Form
{
    /**@var ShapedRecipe|ShapelessRecipe */
    private $recipe;
    /** @var Player */
    private $player;

    /**
     * @param ShapedRecipe|ShapelessRecipe $recipe
     */
    public function __construct($recipe, Player $player) {
        $this->recipe = $recipe;
        $this->player = $player;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        $count = $data[0];
        $inv = $player->getInventory();

        //変換に必要なアイテムの前処理
        $results = [];
        foreach ($this->recipe->getResults() as $result) {
            $result->setCount($result->getCount() * $count);
            $results[] = $result;
        }

        //変換に必要なアイテムを所持しているか確認
        foreach ($results as $result) {
            if (!$inv->contains($result)) {
                $player->sendMessage("§cアイテムが足りないよ");
                return;
            }
        }

        //変換元アイテムを削除する
        $inv->removeItem(...$results);

        //アイテムの前処理
        $items = [];
        foreach ($this->recipe->getIngredientList() as $item) {
            if ($item->getDamage() == -1 or $item->getDamage() == 32767) {
                $item->setDamage(0);
            }
            $item->setCount($item->getCount() * $count);
            $items[] = $item;
        }

        //変換後のアイテムを付与し、インベントリに入り切らなかったアイテムはドロップ
        foreach ($inv->addItem(...$items) as $overflow) {
            $player->getLevel()->dropItem($player, $overflow);
        }

        $player->sendMessage("変換したよ！");
    }

    public function jsonSerialize() {

        $max = $this->calculateMax();
        return [
            "type" => "custom_form",
            "title" => "個数を決めてね",
            "content" => [
                [
                    "type" => "slider",
                    "text" => "変換する数を決めてね",
                    "min" => 1,
                    "max" => $max,

                ]
            ]
        ];
    }

    /**
     * 変換できる最大数を計算する
     */
    private function calculateMax(): int {
        for ($max = 0; true; $max++) {
            $results = [];
            foreach ($this->recipe->getResults() as $result) {
                $results[] = $result->setCount($result->getCount() * $max);
            }
            foreach ($results as $result) {
                if (!$this->player->getInventory()->contains($result)) {
                    return max(0, $max - 1);
                }
            }
        }
        throw new LogicException("何らかの理由でforループを抜けました");
    }
}