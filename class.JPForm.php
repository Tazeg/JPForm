<?php
//-----------------------------------------------------------------------
//  AUTHOR	: Jean-Francois GAZET
//  WEB 	: http://www.jeffprod.com
//  TWITTER	: @JeffProd
//  MAIL	: jeffgazet@gmail.com
//  LICENCE 	: GNU GENERAL PUBLIC LICENSE Version 2, June 1991
//-----------------------------------------------------------------------

class JPForm
	{
	private $_html; 	// the html code
	private $_data; 	// data sent by form (POST or GET)
	private $_errors;	// error messages from validation rules
	private $_items;	// text, textarea, select, and values (name,size,cols,rows...)
	private $_sha1;
	private $_method;
	
	//---------------------------------------------------------------------------------------------------------
	// constructor called with i.e. : $myform=new JPForm('index.php','POST');
	//---------------------------------------------------------------------------------------------------------
	
	public function __construct($action,$method)
		{
		$method=strtoupper($method);
		if(!($method=="POST" || $method=="GET")) {echo 'JPForm() : Method must be POST or GET'; return;}
		$this->_method=$method;
		$this->_sha1=sha1($action.$method);
		$this->_html='<form class="form-horizontal" action="'.$action.'" method="'.$method.'">'.PHP_EOL;
		$this->_html.='<input type="hidden" name="JPFormCheck" value="'.$this->_sha1.'">'.PHP_EOL;
		$this->_errors="";
		}
		
	//---------------------------------------------------------------------------------------------------------
	// Labels, or any free HTML code you want to add within the <FORM> code
	//---------------------------------------------------------------------------------------------------------
	
	public function addFreeText($label)
		{
		$id=count($this->_items);
		$this->_items[$id]['type']='freeText';
		$this->_items[$id]['val']=$label;
		}
		
	// return the HTML code for free text
	private static function getHTMLfreeText($carac)
		{
		return $carac['val'].PHP_EOL;
		}
			
	//---------------------------------------------------------------------------------------------------------
	// input type TEXT
	//---------------------------------------------------------------------------------------------------------
	
	public function addText($name,$val,$size,$maxlength,$class='',$rules='') 
		{
		$this->_items[$name]['type']='text';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['size']=$size;
		$this->_items[$name]['maxlength']=$maxlength;
		$this->_items[$name]['class']=$class;
		$this->_items[$name]['rules']=$rules;		
		}
		 
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLtext($carac)
		{
		$r='';
		$r.='<input type="text" name="'.$carac['name'].'"';
		if(!empty($carac['size'])) {$r.=' size="'.$carac['size'].'"';}
		if(isset($carac['val'])) {$r.=' value="'.$carac['val'].'"';}
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		if(!empty($carac['maxlength'])) {$r.=' maxlength="'.$carac['maxlength'].'"';}
		$r.='>'.PHP_EOL;
		return $r;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// input type CHECKBOXES
	//---------------------------------------------------------------------------------------------------------
	
	public function addCheckbox($name,$val,$isChecked,$class='',$rules='') 
		{
		$this->_items[$name]['type']='checkbox';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['checked']=$isChecked;
		$this->_items[$name]['class']=$class;
		$this->_items[$name]['rules']=$rules;		
		}
		
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLcheckbox($carac)
		{
		$r='';
		$r.='<input type="checkbox" name="'.$carac['name'].'"';
		if(isset($carac['val'])) {$r.=' value="'.$carac['val'].'"';}
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		if($carac['checked']) {$r.=' checked';}
		$r.='>'.PHP_EOL;
		return $r;
		}
		

	//---------------------------------------------------------------------------------------------------------
	// input type HIDDEN
	//---------------------------------------------------------------------------------------------------------
	
	public function addHidden($name,$val,$rules) 
		{
		$this->_items[$name]['type']='hidden';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['rules']=$rules;		
		}
		
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLhidden($carac)
		{
		$r='';
		$r.='<input type="hidden" name="'.$carac['name'].'"';
		if(isset($carac['val'])) {$r.=' value="'.$carac['val'].'"';}
		$r.='>'.PHP_EOL;
		return $r;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// input type PASSWORD
	//---------------------------------------------------------------------------------------------------------
		
	public function addPassword($name,$val,$size,$maxlength,$class='',$rules='')
		{
		$this->_items[$name]['type']='password';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['size']=$size;
		$this->_items[$name]['maxlength']=$maxlength;
		$this->_items[$name]['class']=$class;
		$this->_items[$name]['rules']=$rules;
		}
		
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLpassword($carac)
		{
		$r='';
		$r.='<input type="password" name="'.$carac['name'].'"';
		if(!empty($carac['size'])) {$r.=' size="'.$carac['size'].'"';}
		if(isset($carac['val'])) {$r.=' value="'.$carac['val'].'"';}
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		if(!empty($carac['maxlength'])) {$r.=' maxlength="'.$carac['maxlength'].'"';}
		$r.='>'.PHP_EOL;
		return $r;
		}
				
	//---------------------------------------------------------------------------------------------------------
	// input type RADIO
	//---------------------------------------------------------------------------------------------------------
		
	public function addRadio($name,$rules)
		{
		$this->_items[$name]['type']='radio';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['rules']=$rules;
		$this->_items[$name]['options']=array();
		}
	
	public function addRadioOption($parent,$val,$label,$selected=false)
		{
		while (list($key, $value) = each($this->_items))
			{
			if($key!=$parent) {continue;}
			while (list($key2, $value2) = each($value))
				{
				if($key2!='options') {continue;}
				$this->_items[$key][$key2][]="$val|$label|$selected"; 
				}
			reset($value);
			}	
		reset($this->_items);	
		}
				
	// return the HTML code for a type radio
	private static function getHTMLradio($carac)
		{
		$r='';

		while (list($key, $value) = each($carac))
			{
			if($key!='options') {continue;}
			while (list($key2, $value2) = each($value))
				{
				list($val,$label,$selected)=explode("|",$value2);
				$r.='<input type="radio" name="'.$carac['name'].'" value="'.$val.'"';
				if($selected) {$r.=' checked';}
				$r.='>'.$label.PHP_EOL;
				}
			reset($value);
			}	
		reset($carac);
				
		return $r;
		}
				
	//---------------------------------------------------------------------------------------------------------
	// SELECT
	//---------------------------------------------------------------------------------------------------------
	
	public function addSelect($name,$class='',$rules='')
		{
		$this->_items[$name]['type']='select';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['class']=$class;
		$this->_items[$name]['rules']=$rules;
		$this->_items[$name]['options']=array();
		}
	
	public function addSelectOption($parent,$val,$label,$selected=false)
		{
		while (list($key, $value) = each($this->_items))
			{
			if($key!=$parent) {continue;}
			while (list($key2, $value2) = each($value))
				{
				if($key2!='options') {continue;}
				$this->_items[$key][$key2][]="$val|$label|$selected"; 
				}
			reset($value);
			}	
		reset($this->_items);	
		}
				
	// return the HTML code for a type select
	private static function getHTMLselect($carac)
		{
		$r='<select name="'.$carac['name'].'"';
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		$r.='>'.PHP_EOL;
		
		while (list($key, $value) = each($carac))
			{
			if($key!='options') {continue;}
			while (list($key2, $value2) = each($value))
				{
				list($val,$label,$selected)=explode("|",$value2);
				$r.='<option value="'.$val.'"';
				if($selected) {$r.=' selected';}
				$r.='>'.$label.'</option>'.PHP_EOL;
				}
			reset($value);
			}	
		reset($carac);
			
		$r.='</select>'.PHP_EOL;
				
		return $r;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// TEXTAREA
	//---------------------------------------------------------------------------------------------------------
		 	
	function addTextarea($name,$val,$rows,$cols,$class,$rules)
		{
		$this->_items[$name]['type']='textarea';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['rows']=$rows;
		$this->_items[$name]['class']=$class;
		$this->_items[$name]['cols']=$cols;
		$this->_items[$name]['rules']=$rules;			
		}		
	
	// return the HTML code for a textarea with given caracteristics
	private static function getHTMLtextarea($carac)
		{
		$r='';
		$r.='<textarea name="'.$carac['name'].'"';
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		$r.=' rows="'.$carac['rows'].'" cols="'.$carac['cols'].'">'.$carac['val'].'</textarea>'.PHP_EOL;
		return $r;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// SUBMIT button
	//---------------------------------------------------------------------------------------------------------
				
	function addBtSubmit($val="Submit",$name="Submit",$class='')
		{
		$this->_items[$name]['type']='submit';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;	
		$this->_items[$name]['class']=$class;
		}
	
	// return the HTML code for a submit button
	private static function getHTMLsubmit($carac)
		{
		$r='<input type="submit"';
		if(!empty($carac['class'])) {$r.=' class="'.$carac['class'].'"';}
		$r.=' name="'.$carac['name'].'" value="'.$carac['val'].'">'.PHP_EOL;
		return $r;
		}		
		
	//---------------------------------------------------------------------------------------------------------
	// return bool if the form has been submitted
	//---------------------------------------------------------------------------------------------------------
	
	public function isSubmitted()
		{
		switch($this->_method)
			{
			case 'POST':
			if(isset($_POST['JPFormCheck'])) 
				{
				if($_POST['JPFormCheck']==$this->_sha1) {$this->_data=$_POST; return true;}
				}
			break;

			case 'GET':
			if(isset($_GET['JPFormCheck'])) 
				{
				if($_GET['JPFormCheck']==$this->_sha1) {$this->_data=$_GET; return true;}
				}
			break;
			}

		return false;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// checking rules for each item
	// ---------------------------------------------------------------------------------------------------------
	
	public function isValid()
		{
		$r=true;
		
		while (list($frmItem, $param) = each($this->_items))
			{
			if(!isset($param['rules'])) {continue;} // no rule for submit or free text
		
			$ex=explode('|',$param['rules']);
			while (list(, $valueOne) = each($ex))
				{
				switch($valueOne)
					{
					case 'required':
					if(!isset($this->_data[$frmItem]) || $this->_data[$frmItem]=='') 
						{
						$r=false;
						$this->_errors.='<font color="#FF0000">Please enter '.$frmItem.'.</font><br>';
						}
					break;
					
					case 'email':
					if(!empty($this->_data[$frmItem]) && !filter_var($this->_data[$frmItem], FILTER_VALIDATE_EMAIL))
						{
						$r=false;
						$this->_errors.='<font color="#FF0000">'.$frmItem.' is not a valid email.</font><br>';
						}
					break;

					case 'url':
					if(!empty($this->_data[$frmItem]) && !filter_var($this->_data[$frmItem], FILTER_VALIDATE_URL))
						{
						$r=false;
						$this->_errors.='<font color="#FF0000">'.$frmItem.' is not a URL.</font><br>';
						}
					break;	
					
					case 'numeric':
					$this->_data[$frmItem]=str_replace(',','.',$this->_data[$frmItem]);
					if(!is_numeric($this->_data[$frmItem]))
						{
						$r=false;
						$this->_errors.='<font color="#FF0000">'.$frmItem.' is not numeric.</font><br>';
						}
					break;		

					case 'integer':
					if(!preg_match('/^-?\d+$/',$this->_data[$frmItem]))
						{
						$r=false;
						$this->_errors.='<font color="#FF0000">'.$frmItem.' is not an integer.</font><br>';
						}
					break;					
					}
				} // switch
			} // while
			
		reset($this->_items);
		return $r;
		}

	//---------------------------------------------------------------------------------------------------------
	// manage errors
	//---------------------------------------------------------------------------------------------------------
	
	public function getErrors()
		{
		return $this->_errors;
		}
	
	//---------------------------------------------------------------------------------------------------------
	// set posted values to RADIO and SELECT to fill form with those as default
	//---------------------------------------------------------------------------------------------------------
	
	private function changeSelectedValue($name,$valueSentByForm)
		{
		while (list($key, $value) = each($this->_items[$name]['options']))
			{	
			// delete the default form selected
			if(preg_match('/\|1$/',$value)) 
				{
				$value=substr($value,0,-1);
				$this->_items[$name]['options'][$key]=$value;
				}
			// set as default the posted value by user
			if(preg_match("/^$valueSentByForm\|/",$value))
				{
				$this->_items[$name]['options'][$key]=$value.'1';
				}
			}
		reset($this->_items[$name]['options']);
		}
		
	//---------------------------------------------------------------------------------------------------------
	// display the form
	//---------------------------------------------------------------------------------------------------------
		
	public function render()
		{		
		while (list($frmItem, $param) = each($this->_items))
			{
			if(count($this->_data)>0) // to fill form with posted values
				{
				$this->_items[$frmItem]['checked']=0;
				if(isset($this->_data[$frmItem])) 
					{
					if($param['type']=='radio' || $param['type']=='select') {self::changeSelectedValue($param['name'],$this->_data[$frmItem]);}
					else if($param['type']=='checkbox') {$this->_items[$frmItem]['checked']=1;}
					else {$this->_items[$frmItem]['val']=$this->_data[$frmItem];}
					}
				} 
			$functionname='getHTML'.$param['type'];
			$this->_html.=self::$functionname($this->_items[$frmItem]);
			}
		$this->_html.='</form>';
		reset($this->_items);
		
		return $this->_html;
		}
		
	//---------------------------------------------------------------------------------------------------------
	}
?>
