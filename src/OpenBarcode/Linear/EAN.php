<?php
namespace OpenBarcode\Linear;

/**
 * Class EAN
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class EAN implements LinearBarcode
{

    private $bars = array();
    private $code = null;
    private $base = null;

    public function __construct($code)
    {
        if (strlen($code) != 12 && strlen($code) != 13) {
            throw new Exception("Invalid UPC code length");
        }

        $this->code = substr($code, 0, 12);
        $this->base = (int)$code[0];

        $this->buildSequence();
    }

    public function buildSequence()
    {
        $this->code .= $this->calculateCheckDigit();

        $code = "*" . substr($this->code, 1, 6) . "#" . substr($this->code, 7, 6) . "*";

        for ($i = 0; $i < strlen($code); $i++) {
            $this->bars = array_merge($this->bars, $this->mapSequence($code[$i], $i));
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    protected function codeL()
    {
        return array(
            "0" => array(0, 0, 0, 1, 1, 0, 1),
            "1" => array(0, 0, 1, 1, 0, 0, 1),
            "2" => array(0, 0, 1, 0, 0, 1, 1),
            "3" => array(0, 1, 1, 1, 1, 0, 1),
            "4" => array(0, 1, 0, 0, 0, 1, 1),
            "5" => array(0, 1, 1, 0, 0, 0, 1),
            "6" => array(0, 1, 0, 1, 1, 1, 1),
            "7" => array(0, 1, 1, 1, 0, 1, 1),
            "8" => array(0, 1, 1, 0, 1, 1, 1),
            "9" => array(0, 0, 0, 1, 0, 1, 1),
            "#" => array(0, 1, 0, 1, 0),
            "*" => array(1, 0, 1)
        );
    }

    protected function codeG()
    {
        $base = $this->codeL();

        $base["0"] = array(0, 1, 0, 0, 1, 1, 1);
        $base["1"] = array(0, 1, 1, 0, 0, 1, 1);
        $base["2"] = array(0, 0, 1, 1, 0, 1, 1);
        $base["3"] = array(0, 1, 0, 0, 0, 0, 1);
        $base["4"] = array(0, 0, 1, 1, 1, 0, 1);
        $base["5"] = array(0, 1, 1, 1, 0, 0, 1);
        $base["6"] = array(0, 0, 0, 0, 1, 0, 1);
        $base["7"] = array(0, 0, 1, 0, 0, 0, 1);
        $base["8"] = array(0, 0, 0, 1, 0, 0, 1);
        $base["9"] = array(0, 0, 1, 0, 1, 1, 1);

        return $base;
    }


    protected function codeR()
    {
        $base = $this->codeL();

        $base["0"] = array(1, 1, 1, 0, 0, 1, 0);
        $base["1"] = array(1, 1, 0, 0, 1, 1, 0);
        $base["2"] = array(1, 1, 0, 1, 1, 0, 0);
        $base["3"] = array(1, 0, 0, 0, 0, 1, 0);
        $base["4"] = array(1, 0, 1, 1, 1, 0, 0);
        $base["5"] = array(1, 0, 0, 1, 1, 1, 0);
        $base["6"] = array(1, 0, 1, 0, 0, 0, 0);
        $base["7"] = array(1, 0, 0, 0, 1, 0, 0);
        $base["8"] = array(1, 0, 0, 1, 0, 0, 0);
        $base["9"] = array(1, 1, 1, 0, 1, 0, 0);

        return $base;
    }


    public function mapSequence($char, $pos)
    {

        $b = $this->base;

        if ($pos > 6) {
            $sequence = $this->codeR();
        } else if ($b == 0) {
            $sequence = $this->codeL();
        } else if ($b == 1) {
            if ($pos == 1 || $pos == 2 || $pos == 4) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 2) {
            if ($pos == 1 || $pos == 2 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }

        } else if ($b == 3) {
            if ($pos == 1 || $pos == 2 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 4) {
            if ($pos == 1 || $pos == 3 || $pos == 4) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 5) {
            if ($pos == 1 || $pos == 4 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 6) {
            if ($pos == 1 || $pos == 5 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 7) {
            if ($pos == 1 || $pos == 3 || $pos == 5) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else if ($b == 8) {
            if ($pos == 1 || $pos == 3 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        } else {
            if ($pos == 1 || $pos == 4 || $pos == 6) {
                $sequence = $this->codeL();
            } else {
                $sequence = $this->codeG();
            }
        }

        return $sequence[$char];
    }


    public function calculateCheckDigit()
    {
        $check_sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$this->code[$i];
            if ($i % 2 == 1) {
                $check_sum += $digit * 3;
            } else {
                $check_sum += $digit;
            }

        }
        $check_sum %= 10;

        if ($check_sum != 0) {
            return 10 - $check_sum;
        } else {
            return $check_sum;
        }
    }

    public function code()
    {
        return $this->code;
    }
}