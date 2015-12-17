<?php
	
	
	function zaloguj()
	{
		
		$status=spawdz_uzytkownika($_POST['login'],$_POST['pass']);
		if($status[0]!=null)
		{
			$_SESSION['nazwa']=$status[1];
			$_SESSION['typ']=$status[2];
		}
	}
	
	function wyloguj()
	{
	 	$_SESSION = array();
	 	session_destroy();
	}
	
	function typ_uzytkownika()
	{
		return $_SESSION['typ'];	
	}
	
	
	
	
?>