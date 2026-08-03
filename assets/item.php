<?php
require_once "./interfaces/usable.php";

class Item implements Usable{
    private $name;
    private $buff_type;
    private $value;

    public function useItem(Character $target){
        switch($this->buff_type){
            case "Heal":
                $target->Heal($this->value);
                break;

            case "DamageBuff":
                $target->buffAttack($this->value);
                break;
            
            case "DefenseBuff":
                $target->buffDefense($this->value);
                break;
        }
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
     * Get the value of buff_type
     */
    public function getBuffType() {
        return $this->buff_type;
    }

    /**
     * Set the value of buff_type
     */
    public function setBuffType($buff_type): self {
        $this->buff_type = $buff_type;
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