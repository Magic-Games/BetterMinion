<?php

declare(strict_types=1);

namespace Mcbeany\BetterMinion\commands;

use Mcbeany\BetterMinion\BetterMinion;
use Mcbeany\BetterMinion\minions\MinionType;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class MinionCommand extends PluginCommand
{

    public function __construct(Plugin $owner)
    {
        parent::__construct("minion", $owner);
        $this->description = "Give you a minion spawner";
        $this->setPermission("minion.commands");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) return false;
        if (isset($args[0])) {
            switch ($args[0]) {
                case "give":
                    array_shift($args);
                    if (count($args) === 3) {
                        $player = $sender->getServer()->getPlayer($args[0]);
                        if ($player !== null) {
                            $type = intval($args[1]);
                            if ($type >= 0 && $type <= 4) {
                                $target = Item::fromString($args[2]);
                                if ($target->getId() < 255) {
                                    $item = Item::fromString((string) BetterMinion::getInstance()->getConfig()->get("minion-item"));
                                    $item->setNamedTagEntry(new CompoundTag("MinionInformation", [
                                        (new MinionType($type, $target->getId(), $target->getDamage()))->nbtSerialize()
                                    ]));
                                    $player->getInventory()->addItem($item);
                                } else {
                                    $sender->sendMessage("Item not found!");
                                }
                            } else {
                                $sender->sendMessage("Type not found!");
                            }
                        } else {
                            $sender->sendMessage("Player not found!");
                        }
                    }
                    break;
                case "remove":
                    break;
                default:
                    return false;
            }
        }
        return true;
    }

}