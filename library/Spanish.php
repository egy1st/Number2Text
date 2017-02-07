<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Spanish {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Spanish" );
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
			
			$n_unit = ($N [$x + 1] * 10) + $N [$x + 2];
			$n_all = $N [$x] + $n_unit;
			// keywords are 30 not 20 as usual
			if ($n_unit > 0 & $n_unit < 31) {
				$str_unit = $R [$n_unit];
				// tens
			} else if ($N [$x + 2] == 0) {
				$str_unit = $Z [$N [$x + 1]];
				// Notice that "y" is used only in numbers 31-99 (and 131-199, 231-299, 331-399, etc.)
				// others
			} else {
				$str_unit = $Z [$N [$x + 1]] . " " . $M [0] . " " . $R [$N [$x + 2]];
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			// y "and" is not used to separate hundreds from tens.
			if ($n_all != 0) {
				// When there is exactly 100 of something use the shortened form "cien" rather than ciento
				// for exactly 100
				if ($N [$x] == 1 & $N [$x + 1] + $N [$x + 2] == 0) {
					$Num .= "cien" . " " . $str_unit . " " . $id2 + " ";
				} else {
					$Num .= $H [$N [$x]] . " " . $str_unit . " " . $id2 . " ";
					// others
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if (NumberingSystem::NoCurrency ( $L, $Forma )) {
				$Num = removeAnd ( $Num );
				$Num .= " " . $id2;
			}
		}
		
		// Num = removeComma(Num) ' no comma is used in Spanish
		$Num = NumberingSystem::removeSpaces ( $Num );
		// $Num = NumberingSystem::removeAnd($Num);
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
}

?>