<?php
require_once __DIR__."/../interfaces/decideaction.php";
class CPU implements DecideAction{

	public function decideAction(Character $user, Character $target)
    {
        $action = 1;#random_int(1, 2);
        
        switch($action){
            case 1:
                $user->Attack($target);
                printf("<p>%s Atacou %s</p>", $user->getName(), $target->getName());
                break;

            case 2:
                if($user->getEnergyPoints() <= 10){
                    $user->Attack($target);
                }
                else{
                    echo "<p>A CPU usou uma habilidade no player</p>";
                    $user->useAbillity($target);
                }
                break;
        }
    }

    public function GrindCPU(Character $user, Character $target){
        $choose = random_int(-1, 1);
        echo "$choose";
        for($i = 0; $i < $target->getLevel()+$choose; $i++){
            $user->LevelUp();
        }
    }
}