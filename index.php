<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES - Tarea 9</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Generador de Pokemon</h1>

    <div class="controles">
        <form method="get">
            <input type="hidden" name="action" value="random">
            <button type="submit">Pokemon Aleatorio</button>
        </form>
        <form method="get">
            <input type="hidden" name="action" value="anterior">
            <button type="submit">Anterior</button>
        </form>
        <form method="get">
            <input type="hidden" name="action" value="siguiente">
            <button type="submit">Siguiente</button>
        </form>
        <form method="get">
            <input type="hidden" name="action" value="buscar">
            <input type="text" name="numeroPokemon" placeholder="Número de Pokemon">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div id="detallesPokemon">
    <?php
    session_start();

    if(isset($_GET['action'])) {
        switch($_GET['action']) {
            case 'random':
                $randomId = rand(1, 1025);
                $_SESSION['currentPokemonId'] = $randomId;
                getPokemonInfo($randomId);
                break;

            case 'anterior':
                if(isset($_SESSION['currentPokemonId'])) {
                    $previousId = max(1, $_SESSION['currentPokemonId'] - 1);
                    $_SESSION['currentPokemonId'] = $previousId;
                    getPokemonInfo($previousId);
                } else {
                    echo "<script>alert('Primero selecciona un Pokémon');</script>";
                }
                break;

            case 'siguiente':
                if(isset($_SESSION['currentPokemonId'])) {
                    $nextId = min(1025, $_SESSION['currentPokemonId'] + 1);
                    $_SESSION['currentPokemonId'] = $nextId;
                    getPokemonInfo($nextId);
                } else {
                    echo "<script>alert('Primero selecciona un Pokémon');</script>";
                }
                break;

            case 'buscar':
                if(isset($_GET['numeroPokemon'])) {
                    $numeroPokemon = $_GET['numeroPokemon'];
                    $_SESSION['currentPokemonId'] = $numeroPokemon;
                    getPokemonInfo($numeroPokemon);
                }
                break;
        }
    }
    ?>
    </div>

    <?php
    /**
     * Se genera la información de un Pokemon y la muestra en pantalla.
     *
     * @param int $pokemonId El ID del Pokémon.
     * @return void
     */
    function getPokemonInfo($pokemonId) {
        if(empty($pokemonId)) {
            return;
        }

        $url = "https://pokeapi.co/api/v2/pokemon/" . $pokemonId;
        $datosPokemon = json_decode(file_get_contents($url));

        echo "<h2>" . ucfirst($datosPokemon->name) . "</h2>";
        echo "<img src='" . $datosPokemon->sprites->front_default . "' alt='" . $datosPokemon->name . "'>";
        echo "<p>Número: " . $datosPokemon->id . "</p>";
        echo "<p>Tipo: ";
        foreach ($datosPokemon->types as $type) {
            echo $type->type->name . ", ";
        }
        echo "</p>";
        echo "<p>Aparece en los juegos: ";
        foreach ($datosPokemon->game_indices as $game) {
            echo $game->version->name . ", ";
        }
        echo "</p>";
    }
    ?>
</body>
</html>
