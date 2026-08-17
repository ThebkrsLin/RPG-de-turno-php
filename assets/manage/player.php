<?php
require_once __dir__."/../interfaces/decideaction.php";
require_once "item.php";
class Player implements DecideAction{
    protected $playerTurn;
    private $disableAbillity;
    private $disableInventory;

    #[Override]
    public function decideAction(Character $user, Character $target): array
    {
        $action = $_POST['pAction'] ?? null;
        $itemChoosed = $_POST['itemChoosed'] ?? null;
        echo "$itemChoosed";

        switch($action){
            case "attack":
                $dmg = $user->Attack($target);
                return [
                'message' => "{$user->getName()} atacou {$target->getName()} causando " . round($dmg, 1) . " de dano",
                'damage' => $dmg,
                'item' => null,
            ];


            case "weapon":
                $user->getDefaultWeapon()->weaponTick();
                $dmg = $user->AttackWithWeapon($target);
                return [
                'message' => "{$user->getName()} atacou {$target->getName()} usando {$user->getDefaultWeapon()->getName()}   causando " . round($dmg, 1) . " de dano",
                'damage' => $dmg,
                'item' => null,
            ];


            case "abillity":
                $dmg = $user->useAbillity($target);
                if($dmg){
                    $this->disableAbillity = false;
                    return [
                'message' => "{$user->getName()} usou a habilidade no {$target->getName()} causando " . round($dmg, 1) . " de dano",
                'damage' => $dmg,
                'item' => null,
            ];

                    
                }

                else{
                    $this->disableAbillity = true;
                    return [
                'message' => "{$user->getName()} está sem energia!!",
                'damage' => $dmg,
                'item' => null,
            ];
                }

            case "item":
                $inventory = $user->getInventory();
                $dmg = $inventory[$itemChoosed]->useItem($user);
                switch($itemChoosed){
                    case "Poção de Cura":
                        return [
                        'message' => "{$user->getName()} usou {$itemChoosed} e curou 30 de hp",
                        'damage' => $dmg,
                        'item' => $itemChoosed,
                        ];

                    case "Encantamento Força":
                        return [
                        'message' => "{$user->getName()} usou {$itemChoosed} e aumentou o dano do ataque",
                        'damage' => $dmg,
                        'item' => $itemChoosed,
                        ];

                    case "Escudo Mágico":
                        return [
                        'message' => "{$user->getName()} usou {$itemChoosed} e aumentou a defesa",
                        'damage' => $dmg,
                        'item' => $itemChoosed,
                        ];
                }
        }
    }

    public function getDisableAbillity(){
        return $this->disableAbillity;
    }

    public function getDisableInventory() {
        return $this->disableInventory;
    }
}