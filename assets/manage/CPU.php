<?php
require_once __DIR__."/../interfaces/decideaction.php";
class CPU implements DecideAction{

	public function decideAction(Character $user, Character $target): String
    {
        if(!empty($user->getInventory())){
            $action = 4;#random_int(1, 4);
            $itemChoose = random_int(1, 3);
        }

        else{
            $action = 4;#random_int(1, 3);
        }
        
        
        switch($action){
            case 1:
                $user->Attack($target);
                return "{$user->getName()} Atacou {$target->getName()}";

            case 2:
                $user->useAbillity($target); 
                return "{$user->getName()} usou uma habilidade no player";

            case 3:
                $user->AttackWithWeapon($target);
                return "{$user->getName()} atacou {$target->getName()} com {$user->getDefaultWeapon()->getName()}";

            case 4:
                $inventory = $user->getInventory();
                if(array_key_exists("Poção de Cura", $inventory)){
                    return "{$user->getName()} usou Poção de Cura e curou 30 de hp";
                    $inventory["Poção de Cura"]->useItem($user);
                }

                $itemChoose = array_rand($inventory);
                $inventory[$itemChoose]->useItem($user);
                switch($itemChoose){
                    case "Poção de Cura":
                        return "{$user->getName()} usou {$itemChoose} e curou 30 de hp";

                    case "Encantamento Força":
                        return "{$user->getName()} usou {$itemChoose} e aumentou 20 de dano";

                    case "Escudo Magico":
                        return "{$user->getName()} usou {$itemChoose} e aumentou a defesa em 10";

                }
        }
    }
}