<?php

require_once __DIR__."/../interfaces/decideaction.php";
class CPU implements DecideAction{

	public function decideAction(Character $user, Character $target): array
    {
        if(!empty($user->getInventory())){
            $action = random_int(1, 4);
        }

        else{
            $action = random_int(1, 3);
        }
        
        
        switch($action){
            case 1:
                $dmg = $user->Attack($target);
                return [
                        'message' => "{$user->getName()} Atacou {$target->getName()} causando ". round($dmg, 1)." de dano",
                        'damage' => $dmg,
                        'item' => null,
                        ];

            case 2:
                $dmg = $user->useAbillity($target); 
                return [
                        'message' => "{$user->getName()} usou uma habilidade no {$target->getName()} causando ".round($dmg, 1)." de Dano",
                        'damage' => $dmg,
                        'item' => null,
                        ];

            case 3:
                $dmg = $user->AttackWithWeapon($target);
                return [
                        'message' => "{$user->getName()} atacou {$target->getName()} usando {$user->getDefaultWeapon()->getName()} causando ".round($dmg, 1)." de dano",
                        'damage' => $dmg,
                        'item' => null,
                        ];

            case 4:

                $inventory = $user->getInventory();

                #fazer a cpu ter a preferência de se curar quando estiver, com pouca vida
                if(array_key_exists("Poção de Cura", $inventory) && $user->getHp() < $user->getMaxHp()){
                    $inventory["Poção de Cura"]->useItem($user);
                    return [
                        'message' => "{$user->getName()} usou Poção de Cura e curou 30 de hp",
                        'damage' => 0,
                        'item' => $inventory["Poção de Cura"]->getName(),
                        ];
                }

                $itemChoose = array_rand($inventory);
                echo "Usou {$itemChoose}";

                switch($itemChoose){
                    case "Poção de Cura":
                        if($user->getHp() < $user->getMaxHp()){
                            $inventory[$itemChoose]->useItem($user);
                            return [
                            'message' => "{$user->getName()} usou {$itemChoose} e curou 30 de hp",
                            'damage' => 0,
                            'item' => $itemChoose,
                            ];
                        }
                        
                        else{
                            $dmg = $user->Attack($target);
                            return [
                                'message' => "{$user->getName()} Atacou {$target->getName()} causando ". round($dmg, 1)." de dano",
                                'damage' => $dmg,
                                'item' => null,
                            ];

                        }

                    case "Encantamento Força":
                        $inventory[$itemChoose]->useItem($user);
                        return [
                        'message' => "{$user->getName()} usou {$itemChoose} e aumentou 20 de dano nos seus ataques",
                        'damage' => 0,
                        'item' => $itemChoose,
                        ];

                    case "Escudo Mágico":
                        $inventory[$itemChoose]->useItem($user);
                        return [
                        'message' => "{$user->getName()} usou {$itemChoose} e aumentou a defesa em 10",
                        'damage' => 0,
                        'item' => $itemChoose,
                        ];

                }
        }
    }
}