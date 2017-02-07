<?php
require_once "NumberingSystem.php";
require_once "Num2Text.php";
class Italian {
	public function TranslateNumber($str_Number, $aCur) {
		$ITA = new Italian ();
		$Num = "";
		
		NumberingSystem::getLanguage ( $R, $Z, $H, $M, $N, "Italian" );
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
			// keywords
			if ($n_unit > 0 & $n_unit < 21) {
				$str_unit = $R [$n_unit];
				// tens
			} else if ($N [$x + 2] == 0) {
				$str_unit = $Z [$N [$x + 1]];
				
				// case compound number whers tens ends with vowels(all tens are do) and units strat with vowels too
				// as in (1,8)
				// thus The numbers venti, trenta, and so on drop the final vowel before adding -uno or otto:
				// Asc Integer ventuno, ventotto.
			} else if ($N [$x + 2] == 1 | $N [$x + 2] == 8) {
				$str_unit = $ITA->removeVowels ( $Z [$N [$x + 1]] ) . $R [$N [$x + 2]];
				// others
			} else {
				$str_unit = $Z [$N [$x + 1]] . $R [$N [$x + 2]];
			}
			
			// When -tre is the last digit of a larger number, it takes an accent: eg. ventitré
			// note 253623 is duecentocinquantatremila seicentoventitré
			// only last tre has accent
			// independent case
			if ($N [$x + 2] == 3 & $L == 4) {
				$str_unit = $ITA->modifyAccent ( $str_unit );
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			// ==============================================================================
			// prepare numbers from 100 to 999
			// Hundreds, tens and units are linked together with no space (e.g.: centonove [109]
			if ($n_all != 0) {
				if (NumberingSystem::checkOneThousnad ( $L, $Forma )) {
					$Num .= $id1;
					// Numbers are grouped in words of three digits, with the specific rule that
					// a space is added after the word for thousand if its multiplier
					// is greater than one hundred and does not end with a double zero
					// (e.g.: duemilatrecentoquarantacinque [2,345], tecentosessantacinquemila duecento [765,200]).
				} else if ($ITA->checkHundredThousnad ( $L, $Forma )) {
					$Num .= $H [$N [$x]] . $str_unit . $id2 . " ";
					
					// experimantal at http://www.languagesandnumbers.com/how-to-count-in-italian/en/ita/
					// add space when thausand multipliers greater than 100, for 100 exatly no space , so we use trim function
				} else if ($ITA->checkSuperOneHundred ( $L, $Forma )) {
					$Num .= $H [$N [$x]] . $str_unit . trim ( $id2 ) . " ";
					//
				} else {
					$Num .= $H [$N [$x]] . $str_unit . $id2;
					// others
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if (NumberingSystem::NoCurrency ( $L, $Forma )) {
				$Num = NumberingSystem::removeAnd ( $Num );
				$Num .= " " . id2;
			}
		}
		
		// Num = removeComma(Num) ' no comma is used in Italin
		$Num = NumberingSystem::removeSpaces ( $Num );
		$Num = NumberingSystem::removeAnd ( $Num, $M [0] );
		
		if ($Forma == "000000000000.000") {
			$Num = $R [0];
		}
		
		return $Num;
	}
	function modifyAccent($str) {
		$Ln = strlen ( $str );
		$Ln2 = strlen ( "tre" );
		if (substr ( $str, - $Ln2 ) == "tre") {
			$str = substr ( $str, 0, ($Ln - $Ln2) ) . "tré";
		}
		return $str;
	}
	function removeVowels($str) {
		$Ln = strlen ( $str );
		if ($Ln > 0) {
			$str = substr ( $str, 0, ($Ln - 1) );
		}
		return $str;
	}
	function checkHundredThousnad($L, $Forma) {
		$NS = new NumberingSystem ();
		if ($L == 3 & NumberingSystem::isPattern ( $Forma, "xxxxxxdxxxxx.xxx" ) & ! NumberingSystem::isPattern ( $Forma, "xxxxxxxxxx00.xxx" )) {
			return true;
		}
		
		return false;
	}
	function checkSuperOneHundred($L, $Forma) {
		$NS = new NumberingSystem ();
		if ($L == 3 & NumberingSystem::isPattern ( $Forma, "xxxxxx100xxx.xxx" )) {
			return true;
		} else if ($L == 2 & NumberingSystem::isPattern ( $Forma, "xxx100xxxxxx.xxx" )) {
			return true;
		} else if ($L == 1 & NumberingSystem::isPattern ( $Forma, "100xxxxxxxxx.xxx" )) {
			return true;
		}
		
		return false;
	}
}

?>