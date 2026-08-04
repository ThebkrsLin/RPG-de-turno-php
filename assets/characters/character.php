<?php
require_once __dir__."/../interfaces/hittable.php";
abstract class Character implements Hittable{
    protected $hp;
    protected $maxHp;
    protected $maxEnergyPoints;
    protected $energyPoints;
    protected $attack;
    protected $defense;
    protected $initiative;
    protected array $inventory;
    protected $level;

    public function __construct($mHp, $maxEP, $att, $def, $ini){
        $this->setMaxHp($mHp);
        $this->setHp($mHp);
        $this->setMaxEnergyPoints($maxEP);
        $this->setEnergyPoints($maxEP);
        $this->setAttack($att);
        $this->setDefense($def);
        $this->setInitiative($ini);
        $this->setLevel(1);
    }

    public abstract function LevelUp();

    public function isAlive(){
        return $this->hp > 0;
    }

    public abstract function useAbillity(Character $target);

    protected function canAct(){
        if(!$this->isAlive()){
            throw new Exception("O Personagem Foi derrotado, não poderá agir!1");
        }
    }

    public function Heal($v){
        $this->hp += $v;
    }

    public function buffAttack($v){
        $this->attack += $v;
    }

    public function buffDefense($v){
        $this->defense += $v;
    }

    public function LoseEnergy($energy){
        $this->energyPoints -= $energy;
    }

    public function RecieveDamage(float $dmg){
        $attackerDmg = max(0, $dmg - $this->defense);
        $this->hp = max(0, $this->hp - $attackerDmg);
    }

    public function Attack(Character $target){
        $this->canAct();
        $target->RecieveDamage($this->attack);
    }

    // Access Functions
    public function getHp(){
        return $this->hp;
    }

    public function setHp(int $hp){
        $this->hp = $hp;
        
    }

    public function getMaxHp(){
        return $this->maxHp;
    }

    public function setMaxHp(int $maxHp){
        $this->maxHp = $maxHp;
        
    }

    public function getMaxEnergyPoints(){
        return $this->maxEnergyPoints;
    }

    public function setMaxEnergyPoints(int $maxEnergyPoints){
        $this->maxEnergyPoints = $maxEnergyPoints;
        
    }

    
    public function getEnergyPoints(){
        return $this->energyPoints;
    }

    public function setEnergyPoints($energyPoints){
        $this->energyPoints = $energyPoints;
        
    }

    public function getAttack(){
        return $this->attack;
    }

    public function setAttack($attack){
        $this->attack = $attack;
        
    }

    public function getDefense(){
        return $this->defense;
    }

    public function setDefense($defense){
        $this->defense = $defense;
        
    }

    public function getInventory(){
        return $this->inventory;
    }

    public function setInventory($inventory){
        $this->inventory[-1] = $inventory;
        
    }

    public function getLevel(){
        return $this->level;
    }

    public function getInitiative() {
        return $this->initiative;
    }

    public function setInitiative($initiative){
        $this->initiative = $initiative;
    }

    /**
     * Set the value of level
     */
    public function setLevel($level): self {
        $this->level = $level;
        return $this;
    }
}