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
        require_once "./assets/item.php";


        $charName = $_POST["charName"];
        $charClass = $_POST["charClass"];
        $item = new Item("Poção de Cura", "Heal", 16);
        $player = null;
        $CPU = null;
        switch($charClass){
            case "Warrior":
                $player = new Warrior(120, 30, 18, 15, 9, $charName);
                $CPU = new Warrior(120, 30, 18, 15, 9, $charName."(CPU)");
                break;
            
            case "Mage":
                $player = new Mage(80, 60, 10, 6, 16, $charName);
                $CPU = new Mage(120, 30, 18, 15, 9, $charName."(CPU)");
                break;
            
            case "Archer":
                $player = new Archer(95, 45, 16, 9, 20, $charName);
                $CPU = new Archer(120, 30, 18, 15, 9, $charName."(CPU)");
                break;
        }
        $CPU->useAbillity($player);
        echo "<pre>";
        print_r($player);
        echo "<br>";
        print_r($CPU);
        echo "</pre>"
        ?><br>
        <a href="javascript:history.go(-1)">Voltar</a>
    </div>
</body>
</html>