<?php
require_once __dir__."/../interfaces/decideaction.php";
require_once "item.php";
class Player implements DecideAction{
    protected $playerTurn;

    #[Override]
    public function decideAction(Character $user, Character $target)
    {
        $action = $_POST['pAction'] ?? null;
        switch($action){
            case "attack":
                $user->Attack($target);
                echo "Atacou a CPU!";
                break;

            case "abillity":
                $user->useAbillity($target);
                echo "Usou uma Habilidade na CPU";
                break;

            case "item":
                $item = new Item("Cura", "Heal", 20);
                $item->useItem($user);
                break;
        }
    }

}