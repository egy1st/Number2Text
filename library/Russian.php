<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Russian {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Russian" );
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
			// tens space units ==> There is no need to use the word "and" in Russian
			
			$Forma = Num2Text::prepareNumber ( $str_Number, $N );
			
			$n_unit = ($N [$x + 1] * 10) + $N [$x + 2];
			$n_all = $N [$x] + $n_unit;
			// keywords
			if ($n_unit > 0 & $n_unit < 21) {
				$str_unit = $R [$n_unit];
				// tens
			} else if ($N [$x + 2] == 0) {
				$str_unit = $Z [$N [$x + 1]];
				// others
			} else {
				$str_unit = $Z [$N [$x + 1]] . " " . $R [$N [$x + 2]];
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			// hundreds and tens are linked just space eg. 131 is сто тридцать один
			
			if ($n_all != 0) {
				// тысяча not один тысяча.
				if (NumberingSystem::checkOneThousnad ( $L, $Forma )) {
					$Num .= " " . $id1 . " ";
				} else if ($N [$x] == 0) {
					$Num .= $str_unit . " " . $id2 . " ";
					// only units and tens
				} else if ($n_unit == 0) {
					$Num .= $H [$N [$x]] . " " . $id2 . " ";
					// only hundreds
				} else {
					$Num .= $H [$N [$x]] . " " . $str_unit . " " . $id2 . " ";
					// complete compund number
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if (NumberingSystem::NoCurrency ( $L, $Forma )) {
				$Num = NumberingSystem::removeAnd ( $Num, $M [0] );
				$Num .= " " . $id2;
			}
		}
		
		// Num = removeComma(Num) ' no comma is used in russian
		$Num = NumberingSystem::removeSpaces ( $Num );
		$Num = NumberingSystem::removeAnd ( $Num, $M [0] );
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
}

?>