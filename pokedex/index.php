<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="pokedex">
        <h1>Pokédex</h1>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require 'pokemons.php';
                    
                    $db = connect_to_db();

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST["delete"])) {
                                    deletePokemon($_POST["delete"], $db); 
                        } else {
                                $err_msg = addPokemon($_POST["name"], $db);
                        }
                    }


                    $pokemons = getPokemons($db);

                    foreach ($pokemons as $pokemon) {
                        display_pokemon_row($pokemon);
                    }

                    if (count($pokemons) == 0) {
                        echo "<tr><td colspan='3'>No pokemons found</td></tr>";
                    }
                ?>  
            </tbody>
        </table>
        <form class="add-pokemon" action="index.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">

            <button type="submit">Add Pokemon</button>
            </form>
            <?php
                // Si on a une erreur, on l'affiche
                if (isset($err_msg)) {
                    echo "<p class='error'>$err_msg</p>";
                }
            ?>
    </div>  
</body>
</html>
