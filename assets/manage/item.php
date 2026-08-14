<?php
require_once __dir__."/../interfaces/usable.php";

class Item implements Usable{
    private $name;
    private $buffType;
    private $value;


    public function __construct($n, $bty, $v){
        $this->setName($n);
        $this->setBuffType($bty);
        $this->setValue($v);
    }
    
    public function useItem(Character $target){
        switch($this->buffType){
            case "Heal":
                $target->Heal($this->value);
                break;

            case "DamageBuff":
                $target->buffAttack($this->value, 15);
                break;
                
            case "DefenseBuff":
                $target->buffDefense($this->value, 10);
                break;
        }
        $target->removeItem($this->getName());
    }


    // Access Functions 

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
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of buffType
     */
    public function getBuffType() {
        return $this->buffType;
    }

    /**
     * Set the value of buffType
     */
    public function setBuffType($buffType): self {
        $this->buffType = $buffType;
        return $this;
    }

    /**
     * Get the value of value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set the value of value
     */
    public function setValue($value): self {
        $this->value = $value;
        return $this;
    }
}