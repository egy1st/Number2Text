<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Turkish {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Turkish" );
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
			// Numbers up to ninety-nine are built by spelling out the ten, then the digit (e.g.: otuz iki [32],
			
			$Forma = Num2Text::prepareNumber ( $str_Number, $N );
			
			$n_unit = ($N [$x + 1] * 10) + $N [$x + 2];
			$n_all = $N [$x] + $n_unit;
			
			// keywords are only 10 not 20
			if ($n_unit > 0 & $n_unit < 11) {
				$str_unit = $R [$n_unit];
				// tens
			} else if ($N [$x + 2] == 0) {
				$str_unit = $Z [$N [$x + 1]];
				// Please note that üç [3] loses its umlaut when composed within a number (e.g.: on uç [13]).
				// thousnads multipier from 11 to 19 ara contcatentaed without space
				// we test it is less than 20 as we are sure it is above 10
			} else if ($N [$x + 2] == 3 & $N [$x + 1] > 0 & ($L == 3 & $n_unit < 20)) {
				$str_unit = $Z [$N [$x + 1]] + "uç";
			} else if ($N [$x + 2] == 3 & $N [$x + 1] > 0 & ! ($L == 3 & $n_unit < 20)) {
				$str_unit = $Z [$N [$x + 1]] . " " . "uç";
				// thousnads multipier from 11 to 19 ara contcatentaed without space
				// we test it is less than 20 as we are sure it is above 10
				// others
			} else if (($L == 3 & $n_unit < 20)) {
				$str_unit = $Z [$N [$x + 1]] . $R [$N [$x + 2]];
			} else {
				$str_unit = $Z [$N [$x + 1]] . " " . $R [$N [$x + 2]];
			}
			
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			// Hundreds and thousands are built by telling the multiplier digit, then the hundred or thousand word
			// (e.g.: beş yüz [500], beş bin [5,000]).
			
			if ($n_all != 0) {
				// yüz not bir yüz
				if (NumberingSystem::checkOneHundred ( $L, $Forma )) {
					$Num .= " " . $H [1] . " " . $id1 . " ";
				} else if ($N [$x] == 0) {
					$Num .= $str_unit . " " . $id2 . " ";
					// only units and tens
				} else if ($n_unit == 0) {
					$Num .= $H ( $N [$x] ) . " " . $id2 + " ";
					// only hundreds
				} else {
					$Num .= $H [$N [$x]] . " " . $str_unit . " " . $id2 . " ";
					// complete compund number
				}
			}
			
			// echo "L" . $L . $Num;
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if (NumberingSystem::NoCurrency ( $L, $Forma )) {
				$Num = removeAnd ( $Num, $M [0] );
				$Num .= " " . $id2;
			}
		}
		
		// Num = removeComma(Num) ' no comma is used in turkish
		$Num = NumberingSystem::removeSpaces ( $Num );
		$Num = NumberingSystem::removeAnd ( $Num, $M [0] );
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
}

?>
