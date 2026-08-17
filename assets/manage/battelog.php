<?php

class Battlelog{
    private array $entries;
    private array $stats;



    public function __construct(){
        echo "Log Criado";
        $this->entries = [];
        $this->stats = [];
    }

    public function register(string $message){
        $this->entries[] = [
            'turn' => count($this->entries) + 1,
            'message' => $message,
            'timestamp' => time()
        ];

        return $this;
    }

    public function render(){
        $html = "<ul class='battle-log'>";
        foreach($this->entries as $entry){
            $html .= "<li>Turno: {$entry['turn']}: {$entry['message']}</li>";
        }

        $html .= "</ul>";
        return $html;
    }

    public function registerDamage(string $characterName, float $damage){
        $this->stats[$characterName]['totalDamage'] = ($this->stats[$characterName]['totalDamage'] ?? 0) + $damage;
    }

    public function registeritemUsed(string $characterName, string $itemName){
        $this->stats[$characterName]['itemsUsed'][] = $itemName;
    }

    // Getters e Setters

    public function getEntries(){
        return $this->entries;
    }

    public function getLastEntry(){
        return end($this->entries) ?: null;
    }

    public function getStats(){
        return $this->stats;
    }

    public function clear(){
        $this->entries = [];
    }
}