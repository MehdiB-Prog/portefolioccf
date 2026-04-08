<?php
require 'helpers.php';

function init_table() {
	$db = connect_to_db();
	$sql = file_get_contents("pokemons.sql");

	try {
		$db->exec($sql);
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
		die();
	}

	return;
}

function connect_to_db() {
	try {
		$db = new PDO("mysql:host=localhost;dbname=pokemon", "root", "root");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
		die();
	}
	
	return $db;
}

function getPokemons($db) {
    $query = $db->query("SELECT * FROM pokemons ORDER BY name ASC");
    $pokemons = $query->fetchAll();	

    return $pokemons;
}

function getPokemonByName($name, $db) {
	$query = $db->prepare("SELECT * FROM pokemons WHERE name = :name");
	$query->execute(["name" => $name]);

	$pokemon = $query->fetch();	

	return $pokemon;
}

function addPokemon($name, $db) {
	$pokemon = getPokemonByName($name, $db);

	if ($pokemon) {
		return;
	}
	

	try {
		list($name, $types, $img_url) = retrieve_pokemon_data($name);
	
		$query = $db->prepare("INSERT INTO pokemons (name, type1, type2, img_url) VALUES (:name, :type1, :type2, :img_url)");

		$query->execute([
			"name" => $name,
			"type1" => $types[0],
			"type2" => $types[1] ?? NULL,
			"img_url" => $img_url
		]);
	} catch (\Throwable $th) {
		return "Pokemon not found";
	}
}

function deletePokemon($name, $db) {
	$query = $db->prepare("DELETE FROM pokemons WHERE name = :name");
	$query->execute(["name" => $name]);
}

function retrieve_pokemon_data($name) {
	$url = "https://tyradex.app/api/v1/pokemon/" . $name;
	$options = [
		"http" => [
			"header" => "Content-Type: application/json\r\n" .
				"cache-control: no-cache\r\n",
			"method" => "GET"
		],
		"ssl" => [
			"verify_peer" => false,
			"verify_peer_name" => false
		]
	];

	$context = stream_context_create($options);
	$response = file_get_contents($url, false, $context);

	$data = json_decode($response, true);

	if (!isset($data["pokedex_id"])) {
		throw new Exception("Pokemon not found");
	}

	$name = $data["name"]["fr"];
	$img_url = $data["sprites"]["regular"];

	$types = [];
	foreach ($data["types"] as $type) {
		array_push($types, $type["name"]);		
	}
	
	return [$name, $types, $img_url];
};


function display_pokemon_row($pokemon) {
    $img_url = $pokemon["img_url"];
    $name = $pokemon["name"];
    $type1 = $pokemon["type1"];
    $type2 = $pokemon["type2"];

    echo "<tr>";
    echo "<td><img src='$img_url' alt='" . $name . "'></td>";
	echo "<td>" . $name . "</td>";
	echo "<td>";

	echo "<span class='type " . strtolower(english_type_name($type1)) . "'>" . $type1 . "</span>";
	
	if ($type2) {
		echo "<span class='type " . strtolower(english_type_name($type2)) . "'>" . $type2 . "</span>";
	}
	echo "</td>";
	echo "<td>
		<form action='index.php' method='post'>
			<input type='hidden' name='delete' value='" . $name . "'>
			<button type='submit'>Delete</button>
		</form>";
	echo "</tr>";
}
?>
