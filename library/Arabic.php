<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Arabic {
	public function TranslateNumber($str_Number, $aCur) {
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Arabic" );
		for($x = 7; $x <= 12; $x ++) {
			$M [$x] = $aCur [$x - 7];
		}
		
		// ===================================================================================
		// each cycle represent a scale hunderds and tens, thousnads, millions and milliars
		
		for($L = 1; $L <= 5; $L ++) {
			$G = 0;
			
			if ($L == 1) {
				$x = 1;
				$id1 = " مليار ";
				$id2 = " ملياران ";
				$id3 = " مليارات ";
			} else if ($L == 2) {
				$x = 4;
				$id1 = " مليون ";
				$id2 = " مليونان ";
				$id3 = " ملايين ";
			} else if ($L == 3) {
				$x = 7;
				$id1 = " ألف ";
				$id2 = " ألفان ";
				$id3 = " آلاف ";
			} else if ($L == 4) {
				$x = 10;
				$id1 = " جنيه ";
				$id2 = " جنيهان ";
				$id3 = " جنيهات ";
				
				if (substr ( $Forma, 0, 12 ) == "001000000000") {
					$Num = " مليار جنيه ";
				} else if (substr ( $Forma, 0, 12 ) == "002000000000") {
					$Num = " مليارى جنيه ";
				} else if (substr ( $Forma, 0, 12 ) == "000001000000") {
					$Num = " مليون جنيه ";
				} else if (substr ( $Forma, 0, 12 ) == "002002000000") {
					$Num = " مليونى جنيه ";
				} else if (substr ( $Forma, 0, 12 ) == "000000001000") {
					$Num = " ألف جنيه ";
				} else if (substr ( $Forma, 0, 12 ) == "000000002000") {
					$Num = " ألفى جنيه ";
				}
			} else if ($L == 5) {
				$x = 14;
				$id1 = " قرش ";
				$id2 = " قرشان ";
				$id3 = " قروش ";
			}
			
			if ($N [$x + 1] == 0 & $N [$x + 2] == 0) {
				$H [2] = "مائتى ";
			} else {
				$H [2] = "مائتين ";
			}
			
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			
			$Forma = Num2Text::prepareNumber ( $str_Number, $N );
			
			if ($N [$x] == 0 & $N [$x + 1] == 0 & $N [$x + 2] == 0) {
				$G = 1;
			} else if ($N [$x] == 0 & $N [$x + 1] == 0 & $N [$x + 2] == 1) {
				$Num = $Num . " و " . $id1;
				$G = 1;
			} else if ($N [$x] == 0 & $N [$x + 1] == 0 & $N [$x + 2] == 2) {
				$Num = $Num . " و " . $id2;
				$G = 1;
			}
			
			if ($N [$x] > 0) {
				$Num = $Num . " و " . $H [$N [$x]];
			}
			
			if ($N [$x + 1] == 1 & $N [$x + 2] == 0) {
				$Num = $Num . " و " . $Z [1] . $id3;
				$G = 4;
			} else if ($N [$x + 2] == 1 & $N [$x + 1] == 1) {
				$Num = $Num . " و " . $R [11] . $Z [1] . $id1;
				$G = 4;
			} else if ($N [$x + 2] == 2 & $N [$x + 1] == 1) {
				$Num = $Num . " و " . $R [12] . $Z [1] . $id1;
				$G = 4;
			}
			
			if ($N [$x] == 0 & $N [$x + 1] == 0 & $N [$x + 2] > 2) {
				$Num = $Num . " و " . $R [$N [$x + 2]] . $id3;
				$G = 1;
			}
			
			if ($N [$x + 2] > 0 & $G != 4 & $G != 1) {
				$Num = $Num . " و " . $R [$N [$x + 2]];
				$G = 2;
			}
			
			if ($N [$x + 1] > 1) {
				$Num = $Num . " و " . $Z [$N [$x + 1]];
				$G = 2;
			}
			
			if ($N [$x + 1] == 1 & $G != 4) {
				$Num = $Num . $Z [$N [$x + 1]];
				$G = 2;
			}
			
			if ($G != 1 & $G != 4) {
				$Num = $Num . $id1;
			}
		}
		
		$NewNum = $Num;
		$Ln = strlen ( $NewNum );
		
		if (substr ( $NewNum, 0, strlen ( " و " ) ) == " و ") {
			$NewNum = substr ( $NewNum, - ($Ln - strlen ( " و " )) );
		}
		
		if ($Forma == "000000000000.000") {
			$NewNum = "";
		}
		
		$NewNum = str_replace ( "جنيه", $M [7], $NewNum );
		$NewNum = str_replace ( "جنيهات", $M [8], $NewNum );
		$NewNum = str_replace ( "قرش", $M [9], $NewNum );
		$NewNum = str_replace ( "قروش", $M [10], $NewNum );
		$NewNum = str_replace ( "جنيهان", $M [11], $NewNum );
		$NewNum = str_replace ( "قرشان", $M [12], $NewNum );
		
		return $NewNum;
	}
}
?>	