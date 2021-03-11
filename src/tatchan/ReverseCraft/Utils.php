<?php


namespace tatchan\ReverseCraft;


use pocketmine\inventory\BaseInventory;
use pocketmine\item\Item;
use RuntimeException;

final class Utils
{
    /**
     * アイテムを整列する
     *
     * @param Item[] $items
     *
     * @return Item[]
     */
    public static function allignItems(array $items): array {
        //偽インベントリを作成
        $inv = new class extends BaseInventory{
            public function getName(): string
            {
                return "";
            }

            public function getDefaultSize(): int
            {
                return 36;
            }
        };

        //アイテムを追加
        foreach ($items as $item) {
            if (count($inv->addItem($item)) > 0) {
                throw new RuntimeException("アイテムが溢れました");
            }
        }

        //いい感じになってる
        return $inv->getContents();
    }

    private function __construct() {
    }
}
