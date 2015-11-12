<?php
    
function dbcon()
{
	$zaczep=@mysql_connect( "127.0.0.1","aplikacje","app")
   	or die('Brak połączenia z mysql'); 
   	@mysql_select_db('aplikacje') 
   	or die('błąd wyboru bazy danych');
}
    
    
function spawdz_uzytkownika($login,$haslo)
{
	$udane=null;
	$nazwa=null;
	$typ=null;
	$wynik=mysql_query("SELECT * FROM `uzytkownik` WHERE `login` = '".$login."' AND `haslo` = '".$haslo."'"); 
	$ilosc=mysql_num_rows($wynik);
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
   		$udane=1;
		$nazwa=$rekord[1];
		$typ=$rekord[4];
	}
	$tablica[0]=$udane;
	$tablica[1]=$nazwa;
	$tablica[2]=$typ;
	return $tablica;
}
?>