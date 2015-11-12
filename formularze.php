<?php
	
	/**
	 * 
	 */
	abstract class in_type 
	{
		const text="text";
		const password="password";
		const hidden="hidden";
	}
	
	
	function form_start($target,$method="post")
	{
		echo "<form action=\"".$target."\" method=\"".$method."\">";
	}
	
	function form_stop($submitname="")
	{
		echo "<input type=\"submit\" value=\"".$submitname."\"/></form>";
	}
	
	// function input_add($type,$name,$label="",$value="")
	// {
		// $val="";
		// $lab="";
		// if($value!="")
		// {
			// $val="value=\"".$value."\"";
		// }	
		// if($label!="")
		// {
			// $lab="<label>".$label."</label>";
		// }
		// echo $label."<input type=\"".$type."\" name=\"".$name."\"".$val."/>";
	// }
	
	function input_add($type,$name,$label="",$value="")
	{
		return $inputs = array('type' =>$type ,
								'name'=>$name,
								'label'=>$label,
								'value'=>$value
							 );
	}
	
	class form 
	{
		private $struktura ;
		
		public function get(){
			return $this->struktura; 
		}
	}
	
	
class form_element
{
	//private $type=''; 
	private $id=null;
	private $name=null;
	private $value=null;
	private $label=null;
	private $dispaly=null;
	private $msg=null;
	
	//public function get_type(){return $this->type;}
	//public function set_type($val){$this->type=$val;}
	
	public function get_id(){return $this->id;}
	public function set_id($val){$this->id=$val;}
	
	public function get_name(){return $this->name;}
	public function set_name($val){$this->name=$val;}
	
	public function get_value(){return $this->value;}
	public function set_value($val){$this->value=$val;}
	
	public function get_label(){return $this->label;}
	public function set_label($val){$this->label=$val;}
	
	public function get_display(){return $this->display;}
	public function set_display($val){$this->display=$val;}
	
	public function get_msg(){return $this->msg;}
	public function set_msg($val){$this->msg=$val;}
	
	public function disp_filed(){echo 'abstract';}
	//abstract public function validate(); 
		
	public function show()
	{
		echo $this->label.' ';
		echo $this->disp_filed();
		$msg=$this->msg;
		if($msg!=null){echo '<span class="msg">'.$msg.'</span>';}//wyświtlanie aleru dla błędnie wypełninego pola
		$display=$this->dispaly;
		if($display=='next'){echo'<br/>'; }//sposób wyświtlania pól obok siebie lub pod sobą 
		else{echo ' ';} 
	}
}

class form_input extends form_element
{
	private $type='text';
	public function set_params($id,$type,$name,$label,$display,$value)
	{
		$this->set_id($id);
		$this->set_name($name);
		$this->set_label($label);
		$this->set_display($display);
		$this->set_value($value);
		$this->type=$type;
	}
	public function disp_filed()
	{
		echo '<input id="'.$this->get_id().'"';
		echo 'type="'.$this->type.'"';
		echo 'name="'.$this->get_name().'"';
		$val=$this->get_value();
		if($val!=null){echo'value="'.$val.'"';}
		echo '/>';
	}
	
	public function valid()
	{
		$index=$this->get_name();
		try{
		if(!empty($_POST[$index]));
		}
		catch(Exception $e){
			
		}
		}
}


function form_wyswietl($tab)
{		
	foreach($tab as $pole) 
	{ 
   		$pole->show();
	}
}

function form_spawdz($tab)
{
	foreach($tab as $pole) 
	{ 
   		$pole->valid();
	}		
}


function logowanie() 
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('login',in_type::text,'login','login',null, null);
	$tab[]=$pole;
	$pole= new form_input;
	$pole->set_params('pass',in_type::password,'pass','hasło',null, null);
	$tab[]=$pole;
	$pole= new form_input;
	return $tab;
}



function form_szukaj_narzedzia() 
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('pole',in_type::text,'szukaj_narzedzia','szukaj narzedzia:','next', null);
	$tab[]=$pole;
	return $tab;
}

function form_admin_doadaj_uzytkownika() 
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('nazwa_uz',in_type::text,'nazwa_uz','nazwa uzytkownika',null, null);
	$tab[]=$pole;
	$pole= new form_input;
	$pole->set_params('pass1',in_type::password,'pass1','hasło',null, null);
	$tab[]=$pole;
	$pole= new form_input;
	$pole->set_params('pass2',in_type::password,'pass2','potwierdz hało',null, null);
	$tab[]=$pole;
	return $tab;
}

function form_admin_doadaj_polke()
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('nazwa_pk',in_type::text,'nazwa_pk','nazwa półki',null, null);
	$tab[]=$pole;
	$pole= new form_input;
	$pole->set_params('hala',in_type::password,'hala','hala',null, null);
	$tab[]=$pole;
	return $tab;	
}

function form_admin_doadaj_hale()
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('nazwa_hala',in_type::text,'nazwa_hala','nazwa hali',null, null);
	$tab[]=$pole;
	return $tab;	
}

function form_pracownik_wybierz_klienta()
{
	$tab=array();
	$pole= new form_input;
	$pole->set_params('klient',in_type::text,'klient','klent',null, null);
	$tab[]=$pole;
	return $tab;	
}

function form_pracownik_pokaz_wypozyczenia()
{
		echo "jeszcze nie gotowe";
}










?>