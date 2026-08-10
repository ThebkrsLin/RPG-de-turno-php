<?php
require_once __dir__."/../interfaces/decideaction.php";
require_once __dir__."/../item.php";
class Player implements DecideAction{
    protected $playerTurn;

    #[Override]
    public function decideAction(Character $user, Character $target)
    {
        $action = $_POST['pAction'] ?? null;

        switch($action){
            case "attack":
                $user->Attack($target);
                break;

            case "abillity":
                $user->useAbillity($target);
                break;

            case "item":
                $item = new Item("Cura", "Heal", 20);
                $item->useItem($user);
                break;
        }
    }
}