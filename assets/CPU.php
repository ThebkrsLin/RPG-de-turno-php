<?php
require_once "characters/archer.php";
require_once "characters/mage.php";
require_once "characters/warrior.php";
class CPU{
    private $cpuChar;
    public function createEnemy(){
        $randomClass = ["Warrior", "Mage", "Archer"];
        $choosen = array_rand($randomClass);
        
        switch($choosen){
            case 0:
                $this->cpuChar = new Warrior("(CPU)");
                break;

            case 1:
                $this->cpuChar = new Mage("(CPU)");
                break;

            case 2:
                $this->cpuChar = new Archer("(CPU)");
                break;
        }
    }

    public function GrindCPU(Character $target){
        $choose = random_int(-1, 1);
        echo "$choose";
        for($i = 0; $i < $target->getLevel()+$choose; $i++){
            $this->cpuChar->LevelUp();
        }
    }

    /**
     * Get the value of cpuChar
     */
    public function getCpuChar() {
        return $this->cpuChar;
    }
}