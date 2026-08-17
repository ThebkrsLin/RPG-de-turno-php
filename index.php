<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPG de Turno</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="creation-header">
            <h1>Criar Personagem</h1>
            <p>Escolha seu nome e sua classe para começar a aventura</p>
        </div>

        <div class="card">
            <form action="combat.php" method="post">
                <div class="form-group">
                    <label for="charName">Nome do Personagem (opcional)</label>
                    <input type="text" id="charName" placeholder="Davi Jones" name="charName" />
                </div>

                <div class="form-group">
                    <label>Classe do Personagem</label>
                    <div class="class-options">
                        <div class="class-option">
                            <input type="radio" id="warrior" name="charClass" value="Warrior" checked>
                            <label for="warrior">
                                <span class="icon">🛡️</span>
                                Guerreiro
                            </label>
                        </div>
                        <div class="class-option">
                            <input type="radio" id="mage" name="charClass" value="Mage">
                            <label for="mage">
                                <span class="icon">🔮</span>
                                Mago
                            </label>
                        </div>
                        <div class="class-option">
                            <input type="radio" id="archer" name="charClass" value="Archer">
                            <label for="archer">
                                <span class="icon">🏹</span>
                                Arqueiro
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Criar Personagem</button>
            </form>
        </div>
    </div>
</body>
</html>