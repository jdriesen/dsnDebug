<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  * The DSNDebug Library  
  * @package LIBRARIES \ DSNDEBUG
  * @category Labrary
  *
  * @copyright 2015-2017 3SN.IT
  * @author Johnny DRIESEN <info.@3sn.be>
  * @version 1.0.0
  * @since 1.0.0 Initial Model File
  *   
  * @todo clean up code
  */


class DSNDebug {
    
	private	$fp;
	private $on_screen	= TRUE;
	private $fp_isopen	= FALSE;
	
	private static $forceLoggingInProduction = FALSE;
	
	private static $debugFolder		= './debug/';
	private static $debugFileExt	= '.dbg.txt';
	
	// If you want to prefix the log info with a DateTime stamp, set to TRUE ...
	private $dateIncluded = FALSE;
	private $dta_format = 'Y-m-d H:i:s';
	
	
	public function __construct($onScreen = TRUE, $toFile = FALSE, $fName = ''){
		// Extra test ... NEVER allow Debug Screen writes in Production !!
		
		$this->on_screen = (self::inDevMode() && $onScreen);
		if ($toFile) { $this->fp = self::open($fName); }
    }
	
	function __destruct() {
		$res = self::close();
	}
	
	
	// Force a DIE ...
	public function DSNDIE() {
		
		$trace = debug_backtrace();
		$caller = $trace[1];
		$caller_function	=  (isset($caller['function'])) ? $caller['function']	: 'no_function';
		$caller_class		=  (isset($caller['class']))	? $caller['class']		: 'no_class';		
		
		$s =  ' BY DSNDebug IN FUNCTION ' . $caller_function . ' IN CLASS ' .  $caller_class;
		self::screen('KILLED', $s);
		self::swrite("\n" . '== Closed by DSNDIE == ');
		$res = self::close();
		
		die;
	}
	
	
	// Used to force screen output in a LIVE environment...
	// Don't use this function when not really needed ...
	public function forceScreen($bForce = FALSE) {
		$this->on_screen = $bForce;
	}
	
	
	// Shows the debug info on the screen ...
	public function screen($objName = '', $objValue = NULL) {
		if($this->on_screen) {
			
			$k = self::the_date() . $objName; 
			$v = (!is_null($objValue)) ? ' = ' . var_export($objValue,TRUE) : ''; 
			
			$s = "\n" ; 
			$s .= "<pre>" ; 
			$s .= "<b>" . $k  .  "</b>" . $v;
			$s .= "</pre>";
			echo $s;
		}
	}
	
	// Shows the debug info on the screen ...
	public function screen_success($objName = '', $objValue = NULL) {
		$objName = "<font color='green'>" . $objName . "</font>"; 
		self::screen($objName, $objValue);
	}
	
	public function screen_error($objName = '', $objValue = NULL) {
		$objName = "<font color='red'>" . $objName . "</font>"; 
		self::screen($objName, $objValue);
	}
	
	public function screen_warning($objName = '', $objValue = NULL) {
		$objName = "<font color='orange'>" . $objName . "</font>"; 
		self::screen($objName, $objValue);
	}
	
	public function screen_info($objName = '', $objValue = NULL) {
		$objName = "<font color='blue'>" . $objName . "</font>"; 
		self::screen($objName, $objValue);
	}
	
	
	
	// Writes the debug to a file ...
	public function write($objName = '', $objValue = 'dsndummyvalue') {
		$v = (!($objValue === 'dsndummyvalue')) ? ' = ' . var_export($objValue,TRUE) : ''; 
		$s =  $objName . $v;
		self::swrite($s);
	}
	
	
	// **** The PRIVATE Functions used in this class ...
	
	private function getDebugFolder() {
	 	return self::$debugFolder;
	}

	private function the_date() {
		$ret =  ($this->dateIncluded) ? '[' . date_format(date_create(), $this->dta_format) . '] ' : '';
		return $ret;
	}
	
	private static function inDevMode() {
		if (self::$forceLoggingInProduction) {
			$ret = self::$forceLoggingInProduction;
		} else {
			$ret = (strtoupper(getenv('CI_ENV')) == strtoupper("development"));
		}
		return $ret;
	}
	
	
	private function open($filename = '') {
		
		// Only allow writing Debug files when in Development mode !!
		if(self::inDevMode()) {
			if ($filename === '') {
				$trace = debug_backtrace();
				// 2 step back... cause it's coming from the constructor of THIS class
				$caller = $trace[2];
				$caller_function	=  (isset($caller['function'])) ? $caller['function']	: 'no_function';
				$caller_class		=  (isset($caller['class']))	? $caller['class']		: 'no_class';
				
				$filename = $caller_class . '_' . $caller_function . self::$debugFileExt;	
			}	
			$this->fp = fopen(self::$debugFolder . $filename, 'a+');
			$this->fp_isopen = TRUE;
			self::swrite('== Open Debugging == ');
			$sfunc = debug_backtrace()[1]['function'];
			$sclass = debug_backtrace()[1]['class'];
			
			return $this->fp;
		}
	}
	
	private function close() {
		if(($this->fp) && ($this->fp_isopen)) {
			self::swrite('== Close Debugging == ' . "\n");
			fclose($this->fp);
			$this->fp_isopen = FALSE;
		}
		return $this->fp_isopen;
	}
	
	private function swrite($sname = '', $svalue = '') {
		if($this->fp) { 
			fwrite($this->fp, self::the_date() . $sname . ' ' . $svalue . "\n"); 
		}	
	}

	
}