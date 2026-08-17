<?php
require_once "character.php";

class Archer extends Character{
    private $name;

    public function __construct($n)
    {
        parent::__construct(95, 45, 16, 9, 10);
        $this->setName($n);
    }

    public function useAbillity(Character $target){
        $this->canAct();
        $r = 10;
        
        $this->ep -= $r;
        if($this->ep < $r){
            $this->disableAbillity = true;
        }
        $dmg = $this->attack * 1.8;
        $target->RecieveDamage($dmg);
        return $dmg;
    }

    public function LevelUp(){
        $this->maxHp += 5;
        $this->defense += 2;
        $this->attack += 5;
        $this->maxep += 4;
        $this->initiative += 2;
        $this->level += 1;
    }
    /**
     * Get the value of name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self {
        $this->name = "O Arqueiro ".$name;
        return $this;
    }
}