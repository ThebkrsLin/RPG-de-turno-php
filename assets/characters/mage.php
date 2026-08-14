<?php
require_once "character.php";

class Mage extends Character{
    private $name;
    
    public function __construct($n)
    {
        parent::__construct(80, 40, 10, 6, 16);
        $this->setName($n);
    }

    public function useAbillity(Character $target){
        $this->canAct();
        $r = 30;
        if($this->ep < $r){
           echo $this->name." está sem energia, não poderá usar a habilidade";
           return false;
        }
        
        else{   
            $this->ep -= $r;
            $target->RecieveDamage($this->attack * 3.8);
            return true;
        }
    }

    public function LevelUP(){
        $this->maxHp += 6;
        $this->defense += 1;
        $this->attack += 4; 
        $this->maxep += 8;
        $this->initiative += 1;
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
        $this->name = "O Mago ".$name;
        return $this;
    }
}