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
        require_once "./assets/manage/CPU.php";
        require_once "./assets/item.php";
        require_once "./assets/manage/battle.php";


        $charName = $_POST["charName"];
        $charClass = $_POST["charClass"];
        $charClass2 = random_int(1, 3);
        $item = new Item("Poção de Cura", "Heal", 16);
        $item2 = new Item("Random Crit", "attackBuff", 24);
        $player = null;
        $enemy = null;
        $CPU = new CPU();
        $battle = new Battle();

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

        switch($charClass2){
            case 1:
                $enemy = new Warrior("(CPU)");
                break;
                
            case 2:
                $enemy = new Mage("(CPU)");
                break;
            
            case 3:
                $enemy = new Archer("(CPU)");
        }
        $battle->addFighters($player, $enemy);
        $battle->start();

        $player->addItem($item);
        $player->addItem($item2);
        $CPU->decideAction($enemy, $player);
        #$item->useItem($player);

        if($player->getLevel() > $enemy->getLevel()){
            $CPU->GrindCPU($enemy, $player);
        }

        echo "<pre>";
        print_r($player);
        echo "<br>";
        print_r($enemy);
        echo "<br>";
        print_r($battle);
        echo "</pre><br>";
        ?><br>
        <a href="javascript:history.go(-1)">Voltar</a>
    </div>
</body>
</html>