<?php
class LifePath {
		
	protected $date;
	
	public function __construct($date) {
		if (!$date instanceof \DateTime) {
			$date = new \DateTime($date);
		}
		$this->date = $date;
	}
	
	protected function is_master($number) {
		$parts = str_split($number);
		if (strlen($number) == 2 && $parts[0] == $parts[1] && $parts[0] < 3) {
			return true;
		}
		return false;
	}
	
	protected function sum_piece($piece) {
		if ($this->is_master($piece)) {
			return $piece;
		} else {
			$total = 0;
			$numbers = str_split($piece);
			foreach ($numbers as $number) {
				$total = $total + $number;
			}
			return $total;
		}
	}
	
	protected function calculate() {
		$pieces = [];
		$total = 0;
		$pieces['y'] = $this->date->format('Y');
		$pieces['m'] = $this->date->format('m');
		$pieces['d'] = $this->date->format('d');
		foreach ($pieces as $piece) {
			$total = $total + $this->sum_piece($piece);
		}
		while (count(str_split($total)) > 1) {
			$total = $this->sum_piece($total);
		}
		return $total;
	}
	
	public function get() {
		return $this->calculate();
	}
}