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

function uzytkownik_nazwa($id)
{
	$nazwa=null;
	$wynik=mysql_query("SELECT * FROM `uzytkownik` WHERE `id` =".$id); 
	$ilosc=mysql_num_rows($wynik);
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
		$nazwa=$rekord[3];
	}
	return $nazwa;
}

function uzytkownik_id($nazwa)
{
	$id=null;
	$wynik=mysql_query("SELECT `id` FROM `uzytkownik` WHERE `nazwa`= '".$_SESSION['nazwa']."'"); 
	$ilosc=mysql_num_rows($wynik);
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
		$id=$rekord[0];
	}
	return $id;
}



function lista_wypozyczen($uz)
{
	 //$query="SELECT `uzytkownik`.`nazwa`, `narzedzie`.`nazwa`, `wypozyczenie`.`od` ";
	 //$query+="FROM `wypozyczenie` JOIN `uzytkownik` ON `wypozyczenie`.`klient`= `uzytkownik`.`id` JOIN `narzedzie` ON `narzedzie`.`id`=`wypozyczenie`.`narzedzie` ";
	 //$query+="WHERE `klient` =".$uz." AND `wypozyczenie`.`do`='0000-00-00 00:00:00.000000'";
	 //$wynik=mysql_query($query); 
	 $tab=array();
	 $wynik=mysql_query("SELECT `wypozyczenie`.`id` ,`uzytkownik`.`nazwa`, `narzedzie`.`nazwa`, `wypozyczenie`.`od` FROM `wypozyczenie` JOIN `uzytkownik` ON `wypozyczenie`.`klient`= `uzytkownik`.`id` JOIN `narzedzie` ON `narzedzie`.`id`=`wypozyczenie`.`narzedzie` WHERE `klient` =".$uz." AND `wypozyczenie`.`do`='0000-00-00 00:00:00.000000'"); 
	 $ilosc=mysql_num_rows($wynik);
	
	 if($ilosc>0){
   		while ($rekord=mysql_fetch_row($wynik))
			{
				$tab[]= array($rekord[0],$rekord[1],$rekord[2],$rekord[3]);
			}
			
		}
	return $tab;
}


function wpisz_wypozyczenie()
{
	$ststus=null;
	$id_prac=uzytkownik_id($_SESSION['nazwa']);
	$wynik=mysql_query("SELECT * FROM `wypozyczenie` WHERE `narzedzie` = ".$_POST['narzedzie']." AND `do` = '0000-00-00 00:00:00'");
	$wypozyczone=mysql_num_rows($wynik);
	if($wypozyczone==0){
		$wynik=mysql_query("INSERT INTO `aplikacje`.`wypozyczenie` (`id`, `pracownik`, `klient`, `narzedzie`, `od`, `do`) VALUES (NULL,'".$id_prac."',".$_SESSION['uz'].",".$_POST['narzedzie'].", CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000');");
	}
	else{
		$ststus=1;
	}
	return $ststus;
}

function zwroc_wypozyczenie()
{
	$ststus=null;
	$wynik=mysql_query("SELECT `id` FROM `wypozyczenie` WHERE `id` = ".$_POST['narzedzie']." AND `do` = '0000-00-00 00:00:00'");
	$wypozyczone=mysql_num_rows($wynik);
	if($wypozyczone!=0){
		$id=mysql_fetch_row($wynik);
		$wynik=mysql_query("UPDATE `aplikacje`.`wypozyczenie` SET `do` = CURRENT_TIME() WHERE `wypozyczenie`.`id` = ".$id[0].";");
	}
	else{
		$ststus=1;
	}
	return $ststus;
}


?>