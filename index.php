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
   
   
 

   //echo $innnn['name'];
   
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
   
   if($_SESSION['typ']==0)menu_admin();
   if($_SESSION['typ']==1)menu_prac();
   if($_SESSION['typ']==2)menu_uz();
   
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
   		   				wyswietl_narzedzia($rekord);
		   			}
				}
				else{
					echo "brak wyników";
				}
   		    }
           break;
		//wyśietlenie narzędzia  
		case 'pokaz':
			
			form_start("index.php?action=szukaj","post");
  			$form=form_szukaj_narzedzia();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("szukaj");
		   if(!empty($_GET['narzedzie']))
		   {
		   		$wynik=mysql_query("SELECT * FROM `narzedzie` WHERE `id`=".$_GET['narzedzie']); 
		   		$ilosc=mysql_num_rows($wynik);
		   		if($ilosc>0){
					while ($rekord=mysql_fetch_row($wynik))
   		   			{
   		   				wyswietl_narzedzie($rekord);
		   			}
		   	  	}
   		   }
           break;
		 
       
       default:
           
   		}
   }
   
   if(!empty($_GET['action'])&&$_SESSION['typ']==1)
   {
	switch ($_GET['action']) {
		case 'szukaj':
			form_start("index.php?action=szukaj","post");
  			$form=form_szukaj_narzedzia();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("szukaj");
			break;
		case 'klient':
			form_start("index.php?action=klient","post");
  			$form=form_pracownik_wybierz_klienta();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("zdefiniuj");
			break;
		case 'pokaz_wypozyczenia':
			form_start("index.php?action=pokaz_wypozyczenia","post");
  			$form=form_pracownik_pokaz_wypozyczenia();
  			//form_wyswietl($form);
  			//form_spawdz($form);
  			//form_stop("pokaż");
			break;
		
		default:
			
			break;
	}
	
   }
   
   if(!empty($_GET['action'])&&$_SESSION['typ']==0)
   {
	switch ($_GET['action']) {
		case 'dodaj_uz':
			form_start("index.php?action=dodaj_uz","post");
  			$form=form_admin_doadaj_uzytkownika();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("dodaj");
			break;
		case 'dodaj_polke':
			form_start("index.php?action=dodaj_polke","post");
  			$form=form_admin_doadaj_polke();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("dodaj");
			break;
		case 'dodaj_hale':
			form_start("index.php?action=dodaj_hale","post");
  			$form=form_admin_doadaj_hale();
  			form_wyswietl($form);
  			form_spawdz($form);
  			form_stop("dodaj");
			break;
		
		default:
			
			break;
	}
	
   }
   
   
   }
	else{
   		form_start("index.php","post");
   		$form=logowanie();
   		form_wyswietl($form);
   		form_stop("zaloguj");
	}
   // form_start("index.php?action=szukaj","post");
   // $form=form_admin_doadaj_uzytkownika();
   // form_wyswietl($form);
   // form_stop("dodaj");
          
   
?>