<?php
//-----------------------------------------------------------------------
//	AUTOR	: Jean-Francois GAZET
//	WEB 	: http://www.jeffprod.com
//  TWITTER	: @JeffProd
//  MAIL	: jeffgazet@gmail.com
//  LICENCE : GNU GENERAL PUBLIC LICENSE Version 2, June 1991
//-----------------------------------------------------------------------

class JPForm
	{
	private $_html; 	// the html code
	private $_data; 	// data sent by form (POST or GET)
	private $_errors;	// error messages from validation rules
	private $_items;	// text, textarea, select, and values (name,size,cols,rows...)
	
	//---------------------------------------------------------------------------------------------------------
	// constructor called with i.e. : $myform=new JPForm('index.php','POST');
	//---------------------------------------------------------------------------------------------------------
	
	public function __construct($action,$method)
		{
		$this->_html='<form action="'.$action.'" method="'.$method.'" />'.PHP_EOL;;
		$this->_errors="";
		}
		
	//---------------------------------------------------------------------------------------------------------
	// Labels, or any free HTML code you want to add within the HTML code
	//---------------------------------------------------------------------------------------------------------
	
	public function addFreeText($label)
		{
		$this->_items[$label]['type']='freeText';
		$this->_items[$label]['val']=$label;
		}
		
	// return the HTML code for a this free text
	private static function getHTMLfreeText($carac)
		{
		return $carac['val'].PHP_EOL;
		}
			
	//---------------------------------------------------------------------------------------------------------
	// input type text
	//---------------------------------------------------------------------------------------------------------
	
	public function addText($name,$val="",$size=30,$rules) 
		{
		$this->_items[$name]['type']='text';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['size']=$size;
		$this->_items[$name]['rules']=$rules;		
		}
		
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLtext($carac)
		{
		return '<input type="text" name="'.$carac['name'].'" value="'.$carac['val'].'" size="'.$carac['size'].'" /><br />'.PHP_EOL;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// select
	//---------------------------------------------------------------------------------------------------------
	
	public function addSelect($name,$rules)
		{
		$this->_items[$name]['type']='select';
		$this->_items[$name]['name']=$name;
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
		$r='<select name="'.$carac['name'].'">'.PHP_EOL;
		
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
	// textarea
	//---------------------------------------------------------------------------------------------------------
		 	
	function addTextarea($name,$val,$rows,$cols,$rules)
		{
		$this->_items[$name]['type']='textarea';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['rows']=$rows;
		$this->_items[$name]['cols']=$cols;
		$this->_items[$name]['rules']=$rules;			
		}		
	
	// return the HTML code for a textarea with given caracteristics
	private static function getHTMLtextarea($carac)
		{
		return '<textarea name="'.$carac['name'].'" rows="'.$carac['rows'].'" cols="'.$carac['cols'].'">'.$carac['val'].'</textarea><br />'.PHP_EOL;
		}
		
	//---------------------------------------------------------------------------------------------------------
	// submit button
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
		$r.=' name="'.$carac['name'].'" value="'.$carac['val'].'" /><br />'.PHP_EOL;
		return $r;
		}		
		
	//---------------------------------------------------------------------------------------------------------
	// return bool if the form has been submitted
	//---------------------------------------------------------------------------------------------------------
	
	public function isSubmitted()
		{
		if(count($_POST)>0) {$this->_data=$_POST; return true;}
		if(count($_GET)>0) {$this->_data=$_GET; return true;}
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
					if(!isset($this->_data[$frmItem]) || empty($this->_data[$frmItem])) 
						{
						$r=false;
						$this->_errors.="Please enter $frmItem.<br>";
						}
					break;
					
					case 'email':
					if(!empty($this->_data[$frmItem]) && !filter_var($this->_data[$frmItem], FILTER_VALIDATE_EMAIL))
						{
						$r=false;
						$this->_errors.="$frmItem is not a valid email.<br>";
						}
					break;

					case 'url':
					if(!empty($this->_data[$frmItem]) && !filter_var($this->_data[$frmItem], FILTER_VALIDATE_URL))
						{
						$r=false;
						$this->_errors.="$frmItem is not a URL.<br>";
						}
					break;	
					
					case 'numeric':
					$this->_data[$frmItem]=str_replace(',','.',$this->_data[$frmItem]); // yes, in France we use a coma ;-)
					if(!is_numeric($this->_data[$frmItem]))
						{
						$r=false;
						$this->_errors.="$frmItem is not numeric.<br>";
						}
					break;		

					case 'integer':
					if(!preg_match('/^\d+$/',$this->_data[$frmItem]))
						{
						$r=false;
						$this->_errors.="$frmItem is not an integer.<br>";
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
		return $this->_errors.'<br>';
		}
	
	//---------------------------------------------------------------------------------------------------------
	// display the form
	//---------------------------------------------------------------------------------------------------------
		
	public function render()
		{		
		while (list($frmItem, $param) = each($this->_items))
			{
			if(isset($this->_data[$frmItem])) {$param['val']=$this->_data[$frmItem];} // to fill textarea and text with values submitted
			$functionname='getHTML'.$param['type'];
			$this->_html.=self::$functionname($param);
			}
		$this->_html.='</form>';
		reset($this->_items);
		
		return $this->_html;
		}
		
	//---------------------------------------------------------------------------------------------------------
	}
?>
