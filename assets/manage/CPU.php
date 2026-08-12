<?php
require_once __DIR__."/../interfaces/decideaction.php";
class CPU implements DecideAction{

	public function decideAction(Character $user, Character $target): String
    {
        $action = random_int(1, 2);
        
        switch($action){
            case 1:
                $user->Attack($target);
                return "{$user->getName()} Atacou {$target->getName()}";
                break;

            case 2:
                $user->useAbillity($target); 
                return "{$user->getName()} usou uma habilidade no player";
                break;

            case 3:
                break;
        }
    }
}