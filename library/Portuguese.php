<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Portuguese {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Portuguese" );
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
			// Tens and units are linked with e (and), as in trinta e cinco [35]
			
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
				$str_unit = $Z [$N [$x + 1]] . " " . $M [0] . " " . $R [$N [$x + 2]];
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			// hundreds and tens are linked with e (and), as in cento e quarenta e seis [146])
			
			if ($n_all != 0) {
				// mil not um mil eg. 1210 is mil cento e vinte
				if (NumberingSystem::checkOneThousnad ( $L, $Forma )) {
					// http://www.languagesandnumbers.com/how-to-count-in-portuguese-portugal/en/por-prt/
					// experimental use added case 'cem not e cem for numbers ends with exactky 100
					$Num .= " " . $id1 . " ";
				} else if (NumberingSystem::checkOneHundred ( $L, $Forma )) {
					$Num .= " " . $H [10] . " " . $id1 . " ";
				} else if ($N [$x] == 0) {
					$Num .= $str_unit . " " . $id2 . " ";
					// only units and tens
				} else if ($n_unit == 0) {
					$Num .= $H [$N [$x]] . " " . $id2 . " ";
					// only hundreds
				} else {
					$Num .= $H [$N [$x]] . " " . $M [0] . " " . $str_unit . " " . $id2 . " ";
					// complete compund number
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// special case
			// thousands and hundreds are not linked with e (and) unless the number ends with a hundred with two zeroes
			// (e.g.: dois mil e trezentos [2,300], but dois mil trezentos e sete [2,307]).
			// http://www.languagesandnumbers.com/how-to-count-in-portuguese-portugal/en/por-prt/
			// experimental use added case and hundreds not 100
			if ($L == 3) {
				if (NumberingSystem::isPattern ( $Forma, "xxxxxxxxxx00.xxx" ) & $N [$x + 3] != 1) {
					$Num = removeComma ( $Num );
					$Num .= " " . $M [0] . " ";
				}
			}
			
			if (NumberingSystem::NoCurrency ( $L, $Forma )) {
				$Num = removeAnd ( $Num );
				$Num .= " " . $id2;
			}
		}
		
		// Num = removeComma(Num) ' no comma is used in Portuguese
		$Num = NumberingSystem::removeSpaces ( $Num );
		// $Num = NumberingSystem::removeAnd($Num);
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
}
?>