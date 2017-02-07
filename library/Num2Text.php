<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// ini_set('error_reporting', E_ALL);
require_once "Arabic.php";
require_once "English.php";
require_once "French.php";
require_once "German.php";
require_once "Italian.php";
require_once "Spanish.php";
require_once "Portuguese.php";
require_once "Russian.php";
require_once "Turkish.php";
require_once "Persian.php";
require_once "NumberingSystem.php";

$para_auth = $_GET ['apikey'];
$para_language = $_GET ['language'];
$para_number = $_GET ['number'];
$para_format = $_GET ['format'];
$para_fontsize = $_GET ['fosz'];
$aCurrenies = array ();

$secretKey = 'Egy1st';
$auth_confirm = hash_hmac ( 'md5', $_SERVER ['SERVER_NAME'], $secretKey );
// echo $auth_confirm ."**" . $para_auth;
if ($para_auth != $auth_confirm) {
	// echo "Invalid Key for this website" ;
	// exit;
}
class Language_ID {
	const Arabic = 1;
	const English = 2;
	const Frensh = 3;
	const German = 4;
	const Italian = 5;
	const Spanish = 6;
	const Portuguese = 7;
	const Russian = 8;
	const Turkish = 9;
	const Persian = 10;
	const Korean = 11;
	const Chinese_Formal_Simplified = 12;
	const Chinese_Formal_Traditional = 13;
}

Num2Text::translateNumber ( $para_number, $para_language );
class Num2Text {
	
	// This function left pad zeros, for example 123 will be 000000000123
	public static function zeroPad($num, $int_Count) {
		if ($num != NULL) {
			return str_pad ( $num, $int_Count, '0', STR_PAD_LEFT );
		}
		return "000000000000.000";
	}
	
	// This function format number as integer.decimal where integer is 12 fixed places and decimal is 3 fixed placed
	// Integer is left zero padded, for example 123 will be 000000000123
	// Decimal is left and right zeros padded, for example 0.3 will be 0.030
	public static function formatNumber($str_Number) {
		$whole = floor ( $str_Number ); // 1
		$fraction = $str_Number - $whole; // 0.25
		if ($fraction != 0)
			$fraction = round ( $fraction, 2 ) * 100;
		else if ($fraction == 0)
			$fraction = "000";
		return (self::zeroPad ( $whole, 12 ) . "." . self::zeroPad ( $fraction, 2 ));
	}
	
	// This function is a specific function for Korean language.
	// It format number in special mode depends on 4-places mode rather than 3-places mode used in latin languages
	// Thus, the multiplier is 10,000 rather than 1,000
	public static function prepareNumber4Korean($str_Number, $N) {
		$str_Number = str_replace ( ",", ".", $str_Number );
		if ($str_Number > "999999999999.0099") {
			echo ("Cannot translate numbers exceed 999,999,999,999.99");
			return false;
		}
		
		$Forma = formatNumber ( $str_Number );
		$Num = "";
		
		$E = 0;
		for($E = 1; $E <= 12; $E ++) {
			$S = substr ( $Forma, $E, 1 );
			$N [$E] = $S;
		}
		
		for($E = 14; $E <= 17; $E ++) {
			$S = substr ( $Forma, $E, 1 );
			$N [$E] = $S;
		}
		
		// make(0.23 as 0.0023)
		$N [17] = $N [15];
		$N [16] = $N [14];
		$N [14] = 0;
		$N [15] = 0;
		
		$Forma = substr ( $Forma, 0, 13 );
		for($E = 14; $E <= 17; $E ++) {
			$Forma += $N [$E];
		}
		
		return true;
	}
	
	// This function populates digits in an array to master it one by one
	// Then, it format it to the proper format
	public static function prepareNumber($str_Number, &$N) {
		
		// $str_Number = $para_number;
		$str_Number = str_replace ( ",", ".", $str_Number );
		if ($str_Number > "999999999999.099") {
			echo ("Cannot translate numbers exceed 999,999,999,999.99");
			return false;
		}
		
		$Forma = self::formatNumber ( $str_Number );
		$Num = "";
		
		$E = 0;
		for($E = 0; $E < 12; $E ++) {
			$S = substr ( $Forma, $E, 1 );
			$N [$E + 1] = $S;
		}
		
		for($E = 13; $E < 16; $E ++) {
			$S = substr ( $Forma, $E, 1 );
			$N [$E + 1] = $S;
		}
		
		// make(0.23 as 0.023)
		$N [16] = $N [15];
		$N [15] = $N [14];
		$N [14] = 0;
		
		$Forma = substr ( $Forma, 0, 13 );
		for($E = 14; $E <= 16; $E ++) {
			$Forma .= $N [$E];
		}
		
		return $Forma;
	}
	
	// This function assign currency name to each currency and units in single and plural cases.
	// For example one dollar, two euro, five cents.
	public static function setCurrency() {
		global $aCurrenies;
		
		$aCurrenies [0] = $_GET ['sc'];
		$aCurrenies [1] = $_GET ['pc'];
		$aCurrenies [2] = $_GET ['su'];
		$aCurrenies [3] = $_GET ['pu'];
		$aCurrenies [4] = $_GET ['tc'];
		$aCurrenies [5] = $_GET ['tu'];
	}
	
	// this function will output the translation into 2 format
	// 1- text 2- image
	public static function outputFormat($txt) {
		global $para_format, $para_fontsize;
		
		if ($para_format == 1) {
			$txt = iconv ( 'UTF-8', 'ASCII//TRANSLIT', $txt );
			$txt = preg_replace ( '/[ ]{2,}|[\t]/', ' ', trim ( $txt ) );
			ob_start ();
			// $para_fontsize = 5;
			$width = imagefontwidth ( $para_fontsize ) * strlen ( $txt );
			$height = imagefontheight ( $para_fontsize );
			$image = imagecreatetruecolor ( $width, $height );
			$white = imagecolorallocate ( $image, 255, 255, 255 );
			$black = imagecolorallocate ( $image, 0, 0, 0 );
			imagefill ( $image, 0, 0, $white );
			imagestring ( $image, $para_fontsize, 0, 0, $txt, $black );
			imagepng ( $image );
			$img = ob_get_clean ();
			$data = base64_encode ( $img );
			echo "<img src='data:image/gif;base64," . $data . "' width='" . $width . "' height='" . $height . "'>";
			imagedestroy ( $image );
		} elseif ($para_format == 0)
			echo $txt;
	}
	
	// This function is main function
	// It translates number to string based on the selected language
	public static function translateNumber($str_Number, $Language) {
		global $aCurrenies;
		self::setCurrency ();
		
		switch ($Language) {
			
			case Language_ID::Arabic :
				$lang = new Arabic ();
				break;
			case Language_ID::English :
				$lang = new English ();
				break;
			case Language_ID::Frensh :
				$lang = new French ();
				break;
			case Language_ID::German :
				$lang = new German ();
				break;
			case Language_ID::Spanish :
				$lang = new Spanish ();
				break;
			case Language_ID::Portuguese :
				$lang = new Portuguese ();
				break;
			case Language_ID::Italian :
				$lang = new Italian ();
				break;
			case Language_ID::Russian :
				$lang = new Russian ();
				break;
			case Language_ID::Turkish :
				$lang = new Turkish ();
				break;
			case Language_ID::Persian :
				$lang = new Persian ();
				break;
		}
		
		$txt = $lang->TranslateNumber ( $str_Number, $aCurrenies );
		self::outputFormat ( $txt, 4 );
		return $txt;
	}
}

?>