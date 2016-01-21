<?php
	
   include_once('formularze.php');
   include_once('html.php');
   include_once('sesja.php');
   include_once('baza.php');
   session_start();
   dbcon();
   // mysql_query("SELECT * FROM 'narzedzia'");
   // $wynik=mysql_query("SELECT * FROM `narzedzie`");
   // while ($rekord=mysql_fetch_row($wynik))
   // {
   		// echo $rekord[0].$rekord[1].$rekord[2].$rekord[0]."<br/>";
   // }
   
   szablon_top();
   
 

   //echo $innnn['name'];
   /* spawszanie waznosci sesji*/
    try{
	if(!empty($_POST['login'])&&!empty($_POST['pass'])){
		zaloguj();
	}
	}
	catch(Exception $e){
			
	}
	if(!empty($_GET['action'])){
   		if($_GET['action']=='wyloguj')
		{
		  wyloguj();
		}
   }
	
	
	
   if(isset($_SESSION['nazwa']) && isset($_SESSION['typ']))
   {
   echo '<nav>';
   /*wyswietlanie menu w zalesnosci od typu uzytkownika*/
   if($_SESSION['typ']==0)menu_admin();
   if($_SESSION['typ']==1)menu_prac();
   if($_SESSION['typ']==2)menu_uz();
   echo '</nav> <div id="tresc">';
   /*****************************************************************/
   /********************** kod dla uzytkownika **********************/
   /*****************************************************************/
   /*****************************************************************/
   
   
   if(!empty($_GET['action'])&&$_SESSION['typ']==2)
   {
   switch ($_GET['action']) {
   		//wyszukiwanie narzedzia 
       case 'szukaj':
		     form_start("index.php?action=szukaj","post");
  			 $form=form_szukaj_narzedzia();
  			 form_wyswietl($form);
  			 form_spawdz($form);
  			 form_stop("szukaj");
   		   
   		   if(!empty($_POST['szukaj_narzedzia']))
		   {
		   		echo "wyniki dla zapytania :\"";
		   		echo $_POST['szukaj_narzedzia']."\"<br/>";
   		   		//$wynik=mysql_query("SELECT * FROM `narzedzie` WHERE `nazwa` LIKE '".$_POST['szukaj_narzedzia']."'");
   		   		$wynik=mysql_query("SELECT * FROM `narzedzie` WHERE `nazwa` LIKE '%".$_POST['szukaj_narzedzia']."%'"); 
				$ilosc=mysql_num_rows($wynik);
				if($ilosc>0){
   		   			while ($rekord=mysql_fetch_row($wynik))
   		   			{
   		   				form_start("index.php?action=pokaz","post");
  						$form=dform_wyswietl_narzedzia($rekord);
  						form_wyswietl($form);
  						form_stop("pokaż");
		   			}
				}
				else{
					echo "brak wyników";
				}
   		    }
           break;
		//wyśietlenie narzędzia  
	    case 'wypozyczone':
			$uz=uzytkownik_id($_SESSION['nazwa']);
			$lista=lista_wypozyczen($uz);
			foreach ($lista as $rekord) {
				uz_wypozyczone($rekord);
			}
			
		break;
		case 'pokaz':
			
			form_start("index.php?action=szukaj","post");
  			$form=form_szukaj_narzedzia();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("szukaj");
		   if(!empty($_POST['narzedzie']))
		   {
		   		$wynik=mysql_query("SELECT * FROM `narzedzie` WHERE `id`=".$_POST['narzedzie']); 
		   		$ilosc=mysql_num_rows($wynik);
		   		if($ilosc>0){
					while ($rekord=mysql_fetch_row($wynik))
   		   			{
   		   				wyswietl_narzedzie($rekord);
		   			}
		   	  	}
   		   }
           break;
		   case 'haslo':
			form_start("index.php?action=haslo","post");
  			$form=form_zmien_haslo();
  			form_wyswietl($form);
  			$veryfy=form_spawdz($form);
  			form_stop("zmień");
			$rozmiar=sizeof($veryfy);
			if($rozmiar==0)
			{
				zmien_haslo();
			}
			
			break;
		 
       
       default:
           
   		}
   }


   /*****************************************************************/
   /*********************** kod dla pracownika **********************/
   /*****************************************************************/
   /*****************************************************************/
   
   if(!empty($_GET['action'])&&$_SESSION['typ']==1)
   {
	switch ($_GET['action']) {
			case 'szukaj':
			 form_start("index.php?action=szukaj","post");
  			 $form=form_szukaj_narzedzia();
  			 form_wyswietl($form);
  			 form_spawdz($form);
  			 form_stop("szukaj");
   		   
   		   if(!empty($_POST['szukaj_narzedzia']))
		   {
		   		echo "wyniki dla zapytania :\"";
		   		echo $_POST['szukaj_narzedzia']."\"<br/>";
   		   		//$wynik=mysql_query("SELECT * FROM `narzedzie` WHERE `nazwa` LIKE '".$_POST['szukaj_narzedzia']."'");
   		   		$wynik=mysql_query("SELECT `narzedzie`.`id`,`narzedzie`.`nazwa`, MIN(`wypozyczenie`.`do`) FROM `narzedzie` LEFT JOIN `wypozyczenie` ON `wypozyczenie`.`narzedzie`=`narzedzie`.`id` WHERE `nazwa` LIKE '%".$_POST['szukaj_narzedzia']."%' GROUP BY `narzedzie`.`id` "); 
				$ilosc=mysql_num_rows($wynik);
				if($ilosc>0){
   		   			while ($rekord=mysql_fetch_row($wynik))
   		   			{
   		   				$status=1;
						//spawdzanie czy wybarano uzytkownika i narzedzie jest dostepne 
						//0000-00-00 00:00:00 narzęzie poza magazynem 
   		   				if($rekord[2]=='0000-00-00 00:00:00') $status=0;
   		   				if(isset($_SESSION['uz']) && $status) 		   				
   		   					form_start("index.php?action=wypozycz","post");
						else{
							form_start("index.php?action=pokaz","post");
						}
  						$form=dform_wyswietl_narzedzia($rekord);
  						form_wyswietl($form);
						if(isset($_SESSION['uz'])&&$status) 		   				
   		   					form_stop("wypozycz");
						else{
							form_stop("pokaż");
						}
  						
		   			}
				}
				else{
					echo "brak wyników";
				}
   		    }
			break;
		case 'klient':
			form_start("index.php?action=klient","post");
  			$form=form_pracownik_wybierz_klienta();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("szukaj");
			if(isset($_SESSION['uz']))
			{
				$klient=uzytkownik_nazwa($_SESSION['uz']);
				echo 'klient :'.$klient.'<br/>';
				echo 'lista wypożyczeń <br/>';
				$lista=lista_wypozyczen($_SESSION['uz']);
				foreach ($lista as $rekord) {
					form_start("index.php?action=zwroc","post");
  					$form =	dform_pracownik_wypozyczenia_uz($rekord);
  					form_wyswietl($form);
  					form_spawdz($form);
  					form_stop("zwróć");
				}
			}
  			if(!empty($_POST['klient']))
		   		{
		   			echo "wyniki dla zapytania :\"";
		   			echo $_POST['klient']."\"<br/>";
   		   		    $wynik=mysql_query("SELECT * FROM `uzytkownik` WHERE `nazwa` LIKE '%".$_POST['klient']."%' AND `uprawnienia` = 2"); 
				    $ilosc=mysql_num_rows($wynik);
				    if($ilosc>0){
   		   			while ($rekord=mysql_fetch_row($wynik))
   		   			{
   		   				form_start("index.php?action=definiuj_klijenta","post");
  						$form=	dform_wyswietl_uzytkownika($rekord);
  						form_wyswietl($form);
  						form_spawdz($form);
  						form_stop("ustaw");
		   			}
				}
				else{
					echo "brak wyników";
				}
   		    }
  			
			break;
		case 'definiuj_klijenta':
			if(!empty($_POST['uzytkownik']))
			{
				 $wynik=mysql_query("SELECT * FROM `uzytkownik` WHERE `id` = ".$_POST['uzytkownik']." AND `uprawnienia` = 2"); 
				 $ilosc=mysql_num_rows($wynik);
				 $nazwa="";
				 $id="";
				    if($ilosc>0){
   		   				while ($rekord=mysql_fetch_row($wynik))
						{
							$nazwa=$rekord[3];
							$id=$rekord[0];
						}
						echo "ustawiono ".$nazwa;
						$_SESSION['uz']=$id;	
					}
			}
		break;
		case 'wypozycz':
			if(!empty($_POST['narzedzie']))
			{
				if(isset($_SESSION['uz']))
				{
					$status=wpisz_wypozyczenie();
					if($status==1)
					{
					    echo "nie dodano";	
					}
					else
					{
						echo "dodano do listy";	
						form_start("index.php?action=szukaj","post");
  			 			$form=form_szukaj_narzedzia();
  			 			form_wyswietl($form);
  						form_spawdz($form);
  			 			form_stop("szukaj");		
					}
				}
			}
		break;
		case 'zwroc':
			if(!empty($_POST['narzedzie']))
			{
				
				if(isset($_SESSION['uz']))
				{
					$status=zwroc_wypozyczenie();
					if($status==1)
					{
						echo "nie zwrócono";
					}
					else
					{
						echo "zwrócono narzędzie";
						form_start("index.php?action=szukaj","post");
  			 			$form=form_szukaj_narzedzia();
  			 			form_wyswietl($form);
  						form_spawdz($form);
  			 			form_stop("szukaj");		
					}
				}
			}
			break;
		case 'dodaj':
		form_start("index.php?action=dodaj","post");
  		$form=form_pracownik_dodaj_narzedzie();
  		form_wyswietl($form);
  		form_spawdz($form);
  		form_stop("dodaj");		
		if(!empty($_POST['nazwa']) && !empty($_POST['hala']) && !empty($_POST['polka']))
		{
			dodaj_narzendzie();
		}
		break;
			
			
		default:
			
			break;
	}

   }

   /*****************************************************************/
   /********************** kod dla admistratora *********************/
   /*****************************************************************/
   /*****************************************************************/
   
   if(!empty($_GET['action'])&&$_SESSION['typ']==0)
   {
	switch ($_GET['action']) {
		case 'dodaj_uz':
			form_start("index.php?action=dodaj_uz","post");
  			$form=form_admin_doadaj_uzytkownika();
  			form_wyswietl($form);
  			$veryfy=form_spawdz($form);
  			form_stop("dodaj");
			$rozmiar=sizeof($veryfy);
			if($rozmiar==0)
			{
				dodaj_uzytkownika();
			}
			break;
		case 'dodaj_polke':
			form_start("index.php?action=dodaj_polke","post");
  			$form=form_admin_doadaj_polke();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("dodaj");
			if(!empty($_POST['hala'])&&!empty($_POST['nazwa_pk']))
			{
			dodaj_polke();
			}
			break;
		case 'dodaj_hale':
			form_start("index.php?action=dodaj_hale","post");
  			$form=form_admin_doadaj_hale();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("dodaj");
			if(!empty($_POST['nazwa_hala']))
			{
				dodaj_hale();
			}
			
			break;
		
		default:
			
			break;
	}
	
   }
   
   
   }

	//gdy użytkownik nie zalogowany
	else{
		echo'<nav>&nbsp; </nav> <div id="tresc">'; 
   		form_start("index.php","post");
   		$form=logowanie();
   		form_wyswietl($form);
   		form_stop("zaloguj");
	}
   // form_start("index.php?action=szukaj","post");
   // $form=form_admin_doadaj_uzytkownika();
   // form_wyswietl($form);
   // form_stop("dodaj");
   szablon_bottom();
   
?>