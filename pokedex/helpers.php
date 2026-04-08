<?php

function english_type_name($french_type_name) {
	switch (trim($french_type_name)) {
		case 'Feu':
			return 'Fire';
		case 'Eau':
			return 'Water';
		case 'Plante':
			return 'Grass';
		case 'Insecte':
			return 'Bug';
		case 'Normal':
			return 'Normal';
		case 'Poison':
			return 'Poison';
		case 'Fée':
			return 'Fairy';
		case 'Vol':
			return 'Flying';
		case 'Combat':
			return 'Fighting';
		case 'Sol':
			return 'Ground';
		case 'Roche':
			return 'Rock';
		case 'Spectre':
			return 'Ghost';
		case 'Acier':
			return 'Steel';
		case 'Psy':
			return 'Psychic';
		case 'Électrik':
			return 'Electric';
		case 'Glace':
			return 'Ice';
		case 'Dragon':
			return 'Dragon';
		case 'Ténèbres':
			return 'Dark';
		case 'Fer':
			return 'Steel';
		default:
			return $french_type_name;
			break;
	}
}
