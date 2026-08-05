<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php
        require_once "./assets/characters/mage.php";
        require_once "./assets/characters/warrior.php";
        require_once "./assets/characters/archer.php";
        require_once "./assets/CPU.php";
        require_once "./assets/item.php";


        $charName = $_POST["charName"];
        $charClass = $_POST["charClass"];
        $item = new Item("Poção de Cura", "Heal", 16);
        $item2 = new Item("Random Crit", "attackBuff", 24);
        $player = null;
        $CPU = new CPU();
        $CPU->createEnemy();
        switch($charClass){
            case "Warrior":
                $player = new Warrior($charName);
                break;
            
            case "Mage":
                $player = new Mage($charName);
                break;
            
            case "Archer":
                $player = new Archer($charName);
                break;
        }

        $player->addItem($item);
        $player->addItem($item2); 
        #$item->useItem($player);
        $player->useAbillity($CPU->getcpuChar());
        $CPU->GrindCPU($player);
        echo "<pre>";
        print_r($player);
        echo "<br>";
        print_r($CPU->getCpuChar());
        echo "</pre><br>";
        ?><br>
        <a href="javascript:history.go(-1)">Voltar</a>
    </div>
</body>
</html>