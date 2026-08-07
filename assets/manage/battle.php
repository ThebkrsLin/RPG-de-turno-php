<?php
require_once "battelog.php";
class Battle{
    private array $fighters;
    private $log;
    private array $order;
    private int $turnCount;
    private int $maxTurn = 50;


    public function __construct(){
        $this->log = new Battelog();
        $this->setTurnCount(0);
    }

    public function addFighters(Character $player, Character $cpu){
        $this->fighters = ['player' => $player, 'cpu' => $cpu];
        $this->setOrder();
    }

    public function start(){
        #apenas lógica teste
        for($i = 0; $i < $this->maxTurn; $i++){
            $first = ($i % 2 == 0) ? $this->order[0] : $this->order[1];
            $second = ($i % 2 != 0) ? $this->order[1] : $this->order[0];
            if($i % 2 != 0){
                #$this->order[0] = "o primeiro pode agir";
            }

            else{
                #this->order[1] = "O segundo pode agir";
            }
        }
        echo "<br>";
    }




    /**
     * Get the value of fighters
     *
     * @return array
     */
    public function getFighters(): array {
        return $this->fighters;
    }

    /**
     * Get the value of log
     *
     * @return Battlelog
     */
    public function getLog(): Battlelog {
        return $this->log;
    }

    /**
     * Set the value of log
     *
     * @param Battlelog $log
     *
     * @return self
     */
    public function setLog(Battlelog $log): self {
        $this->log = $log;
        return $this;
    }

    /**
     * Get the value of order
     *
     * @return array
     */
    public function getOrder(): array {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @param array $order
     *
     * @return self
     */
    public function setOrder(){
        if($this->fighters['player']->getInitiative() >= $this->fighters['cpu']->getInitiative()){
            $this->order = [$this->fighters['player'], $this->fighters['cpu']];
        }

        else{
            $this->order = [$this->fighters['cpu'], $this->fighters['player']];
        }
    }

    /**
     * Get the value of turnCount
     *
     * @return int
     */
    public function getTurnCount(): int {
        return $this->turnCount;
    }

    /**
     * Set the value of turnCount
     *
     * @param int $turnCount
     *
     * @return self
     */
    public function setTurnCount(int $turnCount): self {
        $this->turnCount = $turnCount;
        return $this;
    }
}