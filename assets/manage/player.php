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

        switch($action){
            case "attack":
                $user->Attack($target);
                return "{$user->getName()} atacou {$target->getName()}";

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
                switch($itemChoosed){
                    case "Poção de Cura":
                        $user->Heal(20);
                        $user->removeItem("Poção de Cura");
                        return "{$user->getName()} usou poção de cura e curou 20 hp";
                }
                break;
        }
    }

    public function getDisableAbillity(){
        return $this->disableAbillity;
    }

    public function getDisableInventory() {
        return $this->disableInventory;
    }
}