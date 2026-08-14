<?php
require_once __dir__."/../interfaces/decideaction.php";
require_once "item.php";
class Player implements DecideAction{
    protected $playerTurn;
    private $disableAbillity;
    private $disableInventory;

    #[Override]
    public function decideAction(Character $user, Character $target): String
    {
        $action = $_POST['pAction'] ?? null;
        $itemChoosed = $_POST['itemChoosed'] ?? null;
        echo "$itemChoosed";

        switch($action){
            case "attack":
                $user->Attack($target);
                return "{$user->getName()} atacou {$target->getName()}";

            case "weapon":
                $user->getDefaultWeapon()->weaponTick();
                $user->AttackWithWeapon($target);
                return "{$user->getName()} atacou o {$target->getName()}com uma {$user->getDefaultWeapon()->getName()}";

            case "abillity":
                if($user->useAbillity($target)){
                    $this->disableAbillity = false;
                    return "{$user->getName()} usou uma Habilidade na {$target->getName()}";
                    
                }

                else{
                    $this->disableAbillity = true;
                    return "{$user->getName()} está sem energia";
                }

            case "item":
                $inventory = $user->getInventory();
                $inventory[$itemChoosed]->useItem($user);

                switch($itemChoosed){
                    case "Poção de Cura":
                        return "{$user->getName()} usou {$itemChoosed} e curou 30 de hp";

                    case "Encantamento Força":
                        return "{$user->getName()} usou {$itemChoosed} e aumentou o dano do ataque";

                    case "Escudo Mágico":
                        return "{$user->getName()} usou {$itemChoosed} e aumentou a defesa";
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