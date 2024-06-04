<?php

class MathOperations {
    public function add($a, $b) {
        return $a + $b;
    }

    public function subtract($a, $b) {
        return $a - $b;
    }

    public function multiply($a, $b) {
        return $a * $b;
    }

    public function divide($a, $b) {
        if ($b == 0) {
            throw new InvalidArgumentException("Division by zero");
        }
        return $a / $b;
    }

    public function power($base, $exponent) {
        return pow($base, $exponent);
    }

    public function percentage($value, $total) {
        if ($total == 0) {
            throw new InvalidArgumentException("Total cannot be zero");
        }
        return ($value / $total) * 100;
    }

    public function squareRoot($number) {
        if ($number < 0) {
            throw new InvalidArgumentException("Cannot calculate square root of a negative number");
        }
        return sqrt($number);
    }

    public function logarithm($number, $base = M_E) {
        if ($number <= 0) {
            throw new InvalidArgumentException("Number must be positive");
        }
        if ($base <= 0 || $base == 1) {
            throw new InvalidArgumentException("Invalid base");
        }
        return log($number, $base);
    }
}