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

function dodaj_hale()
{
	$wynik=mysql_query( "SELECT * FROM `pomieszczenie` WHERE `nazwa`='".$_POST['nazwa_hala']."' "); 
	$ilosc=mysql_num_rows($wynik);
	if($ilosc>0)
	{
		echo "to pomieszczenie juz istnieje";	
	}
	else {
		$wynik=mysql_query("INSERT INTO `aplikacje`.`pomieszczenie` (`id`, `nazwa`) VALUES (NULL, '".$_POST['nazwa_hala']."')");
		echo 'dodano pomiesczenie '.$_POST['nazwa_hala'];
	}
	
}

function dodaj_polke()
{
	$wynik=mysql_query("SELECT `id` FROM `pomieszczenie` WHERE `nazwa`='".$_POST['hala']."' "); 
	$ilosc=mysql_num_rows($wynik);
	
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
		$id=$rekord[0];
		mysql_query("INSERT INTO `aplikacje`.`polka` (`id`, `nazwa`, `hala`) VALUES (NULL, '".$_POST['nazwa_pk']."', '".$id."')");
		echo "dodano półke ".$_POST['nazwa_pk']." do hali ".$_POST['hala']; 
	}
	else {
		echo "zła nazwa hali";
	}
}

function dodaj_uzytkownika()
{
	$wynik=mysql_query("SELECT * FROM `uzytkownik` WHERE `login`='".$_POST['nazwa_uz']."'"); 
	$ilosc=mysql_num_rows($wynik); 
	if($ilosc>0)
	{
		echo "uzytownik o podanym loginie istnieje";
	}
	else
	{
		$typ=null;
		if($_POST['typ']=='typ1')
			$typ=1;
		elseif($_POST['typ']=='typ2')
			$typ=2;
		if($_POST['pass1']==$_POST['pass2'])
		{
			if($typ!=null)
			{
				mysql_query("INSERT INTO `aplikacje`.`uzytkownik` (`id`, `login`, `haslo`, `nazwa`, `uprawnienia`, `kontakt`) VALUES (NULL, '".$_POST['nazwa_uz']."', '".$_POST['pass1']."', '".$_POST['dane']."', '".$typ."', '".$_POST['kontakt']."')");
			}
		}
		else 
		{
			echo "podane hasła nie są identyczne"; 
		}
	}
	
}

function dodaj_narzendzie()
{
	$wynik=mysql_query(	"SELECT `polka`.`id` FROM `polka` JOIN `pomieszczenie` ON `polka`.`hala`=`pomieszczenie`.`id` WHERE `polka`.`nazwa`='".$_POST['polka']."' AND `pomieszczenie`.`nazwa`='".$_POST['hala']."'"); 
	$ilosc=mysql_num_rows($wynik); 
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
		$id=$rekord[0];
		$wynik=mysql_query("INSERT INTO `aplikacje`.`narzedzie` (`id`, `nazwa`, `polka`, `zuzyte`) VALUES (NULL, '".$_POST['nazwa']."', '".$id."', '0')");
		echo  "dodano ".$_POST['nazwa']." hala: ".$_POST['hala']." półka: ".$_POST['polka'];
	}
	else{
		echo 'nieprawidłowe dane';  
	}
}


function zmien_haslo()
{
	$id=uzytkownik_id($_SESSION['nazwa']);
	$wynik=mysql_query(	"SELECT `uzytkownik`.`haslo` FROM `uzytkownik` WHERE `id` = 3" ); 
	$ilosc=mysql_num_rows($wynik);
	$perm=0; 
	if($ilosc>0)
	{
		$rekord=mysql_fetch_row($wynik);
		if($rekord[0]==$_POST['passold'])
		$perm=1;	
	}
	if($perm==1)
	{
		if($_POST['pass1']==$_POST['pass2']){			
			$wynik=mysql_query("UPDATE `aplikacje`.`uzytkownik` SET `haslo` = '".$_POST['pass1']."' WHERE `uzytkownik`.`id` = ".$id.";");
			echo 'zmieniono hasło';
			}
		else
			echo 'podane hasla nie są identyczne';

	}
	else{
		echo "nieprwidłowe stare haslo"; 
	} 
		
}

?>