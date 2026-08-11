<?php

class Battlelog{
    private array $entries;
    public function __construct(){
        echo "Log Criado";
        $this->entries = [];
    }

    public function register(string $message){
        $this->entries[] = [
            'turn' => count($this->entries) + 1,
            'message' => $message,
            'timestamp' => time()
        ];

        return $this;
    }

    public function getEntries(){
        return $this->entries;
    }

    public function getLastEntry(){
        return end($this->entries) ?: null;
    }

    public function render(){
        $html = "<ul class='battle-log'>";
        foreach($this->entries as $entry){
            $html .= "<li>Turno: {$entry['turn']}: {$entry['message']}</li>";
        }

        $html .= "</ul>";
        return $html;
    }

    public function clear(){
        $this->entries = [];
    }
}