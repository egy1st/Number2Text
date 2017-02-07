<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// ini_set('error_reporting', E_ALL);
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class English {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "English" );
		
		for($x = 7; $x <= 12; $x ++) {
			$M [$x] = $aCur [$x - 7];
		}
		
		// ===================================================================================
		// each cycle represent a scale hunderds and tens, thousnads, millions and milliars
		
		$L = 0;
		for($L = 1; $L <= 5; $L ++) {
			$id1 = $M [($L * 2) - 1];
			
			$id2 = $M [$L * 2];
			if ($L == 1) {
				$x = 1;
				$n_sum = NumberingSystem::getSum ( $N, 1 );
			} else if ($L == 2) {
				$x = 4;
				$n_sum = NumberingSystem::getSum ( $N, 2 );
			} else if ($L == 3) {
				$x = 7;
				$n_sum = NumberingSystem::getSum ( $N, 3 );
			} else if ($L == 4) {
				$x = 10;
			} else if ($L == 5) {
				$x = 14;
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepre numbers from 0 to 99
			
			$Forma = Num2Text::prepareNumber ( $str_Number, $N );
			
			$n_unit = $N [$x + 2] + ($N [$x + 1] * 10);
			// keywords
			if ($n_unit < 21) {
				$str_unit = $R [$n_unit];
				// tens
			} else if ($N [$x + 2] == 0) {
				$str_unit = $Z [$N [$x + 1]];
				// others
			} else {
				$str_unit = $Z [$N [$x + 1]] . "-" . $R [$N [$x + 2]];
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			
			if ($N [$x] != 0) {
				// hundereds with (tens or units) eg. 250, 385, 504
				if ($N [$x + 1] + $N [$x + 2] != 0) {
					$Num .= $H [$N [$x]] . " " . $M [0] . " " . $str_unit . " " . $id2 . ", ";
				} else {
					$Num .= $H [$N [$x]] . " " . $id2 . ", ";
					// hundereds without (tens and units) eg. 100, 200
				}
			} else if ($N [$x + 1] + $N [$x + 2] != 0) {
				$Num .= $str_unit . " " . $id2 . ", ";
			} else {
				// nothing to do
			}
			
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// Special condition for english language
			if ($n_sum > 0 & $n_sum < 100 & $L < 4 & substr ( $Num, (- 1) * (strlen ( $M [0] ) + 1) ) != $M [0] + " ") {
				$Num = trim ( $Num );
				$Ln = strlen ( $Num );
				if (substr ( $Num, - 1 ) == ",") {
					$Num = substr ( $Num, 0, $Ln - 1 );
					$Num .= " " . $M [0] . " ";
				}
			}
			// End of special condition
			
			if ($L == 4) {
				
				if (substr ( $Forma, 0, 12 ) == "000000000001") {
					$Num = $R [1] . " " . $id1;
				} else if (substr ( $Forma, 0, 12 ) == "000000000000") {
					$Num = "";
				} else {
					$Num = trim ( $Num );
					$Ln = strlen ( $Num );
					if (substr ( $Num, - 1 ) == ",") {
						$Num = substr ( $Num, 0, $Ln - 1 );
					}
				}
				
				// this shoud apear prior to cond.4
				$Ln = strlen ( $M [7] );
				$Ln2 = strlen ( $M [8] );
				if (substr ( $Num, - $Ln ) != $M [7] & substr ( $Num, - $Ln2 ) != $M [8] & substr ( $Forma, 0, 12 ) != "000000000000") {
					$Num .= " " . $M [8];
				}
				
				// cond.4
				if (substr ( $Forma, - 3 ) != "000" & substr ( $Forma, 0, 12 ) != "000000000000") {
					$Num .= " " . $M [0] . " ";
				}
			}
			
			if ($L == 5) {
				// one cent
				if (substr ( $Forma, - 3 ) == "001") {
					$Num = trim ( $Num );
					$Ln = strlen ( $Num );
					if (substr ( $Num, - 1 ) == ",") {
						$Num = substr ( $Num, 0, $Ln - 1 );
					}
					
					$Ln = strlen ( $Num );
					$Ln2 = strlen ( $id2 );
					$Num = substr ( $Num, 0, ($Ln - $Ln2) ) . $id1;
					// remove s
				}
			}
		}
		
		$Num = NumberingSystem::removeComma ( $Num );
		$Num = NumberingSystem::removeSpaces ( $Num );
		$Num = NumberingSystem::removeAnd ( $Num, $M [0] );
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
}
?>