<?php

	function przycisk($action,$operation ,$value,$text)
	{
		$str=' <a href="index.php?action='.$action.'&'.$operation.'='.$value.'" class="przycisk"> '.$text.' </a>';
		return $str;
	}
	
    function przycisk_menu($target,$text)
	{
		$str='<a href="'.$target.'"> '.$text.' </a>';
		return $str;
	}

    function wyswietl_narzedzia($response)
	{
		echo '<div class="nerzedzie">'.$response[1].przycisk('pokaz','narzedzie',$response[0],'pokaż').'</div>';  
	}
	
	function wyswietl_narzedzie($response)
	{
		echo '<div class="nerzedzie">'.$response[1].'połoażenie półka'.$response[2].'</div>';  
	}
	
	
	function menu_uz()
	{
		echo '<div class="menu">';	
		echo przycisk_menu('index.php?action=szukaj', 'szukaj narzedzia');
		echo przycisk_menu('index.php?action=wypozyczone', 'twoje wypożyczenia');
		echo przycisk_menu('index.php?action=konto', 'ustawienia konta');
		echo przycisk_menu('index.php?action=wyloguj', 'wyloguj');
		echo '<div>';
	}
	
	function menu_prac()
	{
		echo '<div class="menu">';	
		echo przycisk_menu('index.php?action=klient', 'klient');
		echo przycisk_menu('index.php?action=szukaj', 'szukaj narzedzia');
		echo przycisk_menu('index.php?action=pokaz_wypozyczenia', 'pokaż wypożyczenia');
		echo przycisk_menu('index.php?action=wyloguj', 'wyloguj');
		echo '<div>';
	}
	
	function menu_admin()
	{
		echo '<div class="menu">';	
		echo przycisk_menu('index.php?action=dodaj_uz', 'dodaj uzytkownika');
		echo przycisk_menu('index.php?action=dodaj_polke', 'dodaj półkę');
		echo przycisk_menu('index.php?action=dodaj_hale', 'dodaj halę');
		echo przycisk_menu('index.php?action=wyloguj', 'wyloguj');
		echo '<div>';
	}
	
	function uz_wypozyczone($rekord)
	{
		//pole 2 nazwa narzedzia 
		//pole 3 data wypozyczenia
		echo '<div class="lista1">'.$rekord[2].'</div>';
		echo '<div class="lista2">'.$rekord[3].'</div>'; 
	}
	
?>