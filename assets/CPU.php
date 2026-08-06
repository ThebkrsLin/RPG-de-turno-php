<?php
require_once "interface/decideaction.php";
class CPU implements DecideAction{

	public function decideAction(Character $user, Character $target, int $action)
    {
        switch($action){
            case 1:
                $user->Attack($target);
                break;

            case 2:
                $user->useAbillity($target);
                break;
    }

    public function GrindCPU(Character $target){
        $choose = random_int(-1, 1);
        echo "$choose";
        for($i = 0; $i < $target->getLevel()+$choose; $i++){
            $target->LevelUp();
        }
    }
}