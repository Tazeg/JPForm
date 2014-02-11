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
	private $_html; // the form html code
	private $_data; // data sent by form (POST or GET)
	private $_errors; // error messages from validation rules
	private $_items; // text, textarea and values (label,name,size,cols,rows...)
	
	// called with i.e. $myform=new JPForm('index.php','POST');
	public function __construct($action,$method)
		{
		$this->_html='<form action="'.$action.'" method="'.$method.'" />'.PHP_EOL;;
		$this->_errors="";
		}
		
	// add an input type text
	public function addText($label,$name,$val="",$size=30,$rules) 
		{
		$this->_items[$name]['type']='text';
		$this->_items[$name]['label']=$label;
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['size']=$size;
		$this->_items[$name]['rules']=$rules;
		}
		
	// return the HTML code for a input type text with given caracteristics
	private static function getHTMLtext($carac)
		{
		return $carac['label'].'<br />'.PHP_EOL.'<input type="text" name="'.$carac['name'].'" value="'.$carac['val'].'" size="'.$carac['size'].'" /><br />'.PHP_EOL;
		}
		
	// add a textarea	
	function addTextarea($label,$name,$val,$rows,$cols,$rules)
		{
		$this->_items[$name]['type']='textarea';
		$this->_items[$name]['label']=$label;
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;
		$this->_items[$name]['rows']=$rows;
		$this->_items[$name]['cols']=$cols;
		$this->_items[$name]['rules']=$rules;			
		}		
	
	// return the HTML code for a textarea with given caracteristics
	private static function getHTMLtextarea($carac)
		{
		return $carac['label'].'<br /><textarea name="'.$carac['name'].'" rows="'.$carac['rows'].'" cols="'.$carac['cols'].'">'.$carac['val'].'</textarea><br />'.PHP_EOL;
		}
		
	// add a submit button
	function addBtSubmit($val="Submit",$name="Submit")
		{
		$this->_items[$name]['type']='submit';
		$this->_items[$name]['name']=$name;
		$this->_items[$name]['val']=$val;		
		}
	
	// return the HTML code for a submit button
	private static function getHTMLsubmit($carac)
		{
		return '<input type="submit" name="'.$carac['name'].'" value="'.$carac['val'].'" /><br />'.PHP_EOL;
		}		
		
	// return bool if the form has been submitted
	public function isSubmitted()
		{
		if(count($_POST)>0) {$this->_data=$_POST; return true;}
		if(count($_GET)>0) {$this->_data=$_GET; return true;}
		return false;
		}
		
	// checking rules for each item
	public function isValid()
		{
		$r=true;
		
		reset($this->_items);
		while (list($frmItem, $param) = each($this->_items))
			{
			if(!isset($param['rules'])) {continue;} // no rule for submit or anything else
		
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
			
		return $r;
		}
		
	public function getErrors()
		{
		return $this->_errors.'<br>';
		}

	// display the form
	public function render()
		{
		reset($this->_items);
		while (list($frmItem, $param) = each($this->_items))
			{
			if(isset($this->_data[$frmItem])) {$param['val']=$this->_data[$frmItem];} // to fill form with values submitted
			$functionname='getHTML'.$param['type'];
			$this->_html.=self::$functionname($param);
			}
		$this->_html.='</form>';
		
		return $this->_html;
		}
	}
?>