<?php
class Weapon{
    private $name;
    private $additionalDamage;
    private $weaponDuration;
    private $maxDuration;

    public function __construct($n, $dmg, $duration){
        $this->name = $n;
        $this->additionalDamage = $dmg;
        $this->maxDuration = $duration;
        $this->weaponDuration = $this->maxDuration;
    }

    public function weaponTick(){
        if($this->weaponDuration > 0){
            $this->weaponDuration--;
            return false;
        }

        else{
            return true;
        }
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
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of additionalDamage
     */
    public function getAdditionalDamage() {
        return $this->additionalDamage;
    }

    /**
     * Set the value of additionalDamage
     */
    public function setAdditionalDamage($additionalDamage): self {
        $this->additionalDamage = $additionalDamage;
        return $this;
    }

    /**
     * Get the value of weaponDuration
     */
    public function getWeaponDuration() {
        return $this->weaponDuration;
    }

    /**
     * Set the value of weaponDuration
     */
    public function setWeaponDuration($weaponDuration): self {
        $this->weaponDuration = $weaponDuration;
        return $this;
    }

    /**
     * Get the value of maxDuration
     */
    public function getMaxDuration() {
        return $this->maxDuration;
    }

    /**
     * Set the value of maxDuration
     */
    public function setMaxDuration($maxDuration): self {
        $this->maxDuration = $maxDuration;
        return $this;
    }
}