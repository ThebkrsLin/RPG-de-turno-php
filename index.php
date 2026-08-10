<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPG de Turno</title>
</head>
<body>
    <div>
        <?php $test = random_int(1, 2);?>
        <form action="stats.php" method="post">
         Nome do Personagem (Opcional):<br>   
        <input type="text" placeholder="Davi Jones" name="charName"/><br>
        Classe do Personagem:<br>
        <select name="charClass">
            <option value="Warrior" selected>Guerreiro</option>
            <option value="Mage">Mago</option>
            <option value="Archer">Arqueiro</option>
        </select><br>
        <input type="submit" value="Criar Personagem"/><br>
        <!--
        <?php if($test == 1): ?>
            <p>Foi sorteado o número <?php echo $test;?></p>
        <?php else: ?>
            <p>Foi escolhido o número 2</p>
        <?php endif; ?>
        -->
        </form>
    </div>
</body>
</html>