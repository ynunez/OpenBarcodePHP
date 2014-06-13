<?php
namespace OpenBarcode\Linear;

/**
 * Class UPC
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class UPC implements LinearBarcode
{
    private $bars = array();
    private $code = null;

    public function __construct($code)
    {
        if (\strlen($code) != 11 and \strlen($code) != 12) {
            throw new \Exception("Invalid UPC code length");
        }

        $this->code = \substr($code, 0, 11);
        $this->buildSequence();
    }

    public function buildSequence()
    {
        $this->code .= $this->calculateCheckDigit();

        $code = "*" . \substr($this->code, 0, 6) . "#" . \substr($this->code, 6, 6) . "*";

        for ($i = 0; $i < \strlen($code); $i++) {
            $this->bars = \array_merge($this->bars, $this->mapSequence($code[$i], $i));
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    public function mapSequence($char, $pos)
    {
        $sequence = array(
            "0" => array(0, 0, 0, 1, 1, 0, 1), "1" => array(0, 0, 1, 1, 0, 0, 1), "2" => array(0, 0, 1, 0, 0, 1, 1),
            "3" => array(0, 1, 1, 1, 1, 0, 1), "4" => array(0, 1, 0, 0, 0, 1, 1), "5" => array(0, 1, 1, 0, 0, 0, 1),
            "6" => array(0, 1, 0, 1, 1, 1, 1), "7" => array(0, 1, 1, 1, 0, 1, 1), "8" => array(0, 1, 1, 0, 1, 1, 1),
            "9" => array(0, 0, 0, 1, 0, 1, 1), "#" => array(0, 1, 0, 1, 0), "*" => array(1, 0, 1)
        );

        if ($pos >= 7) {
            $sequence["0"] = array(1, 1, 1, 0, 0, 1, 0);
            $sequence["1"] = array(1, 1, 0, 0, 1, 1, 0);
            $sequence["2"] = array(1, 1, 0, 1, 1, 0, 0);
            $sequence["3"] = array(1, 0, 0, 0, 0, 1, 0);
            $sequence["4"] = array(1, 0, 1, 1, 1, 0, 0);
            $sequence["5"] = array(1, 0, 0, 1, 1, 1, 0);
            $sequence["6"] = array(1, 0, 1, 0, 0, 0, 0);
            $sequence["7"] = array(1, 0, 0, 0, 1, 0, 0);
            $sequence["8"] = array(1, 0, 0, 1, 0, 0, 0);
            $sequence["9"] = array(1, 1, 1, 0, 1, 0, 0);
        }

        return $sequence[$char];
    }

    public function calculateCheckDigit()
    {
        $check_sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $digit = (int)$this->code[$i];
            if ($i % 2 == 0)
                $check_sum += $digit * 3;
            else
                $check_sum += $digit;
        }

        $check_sum %= 10;

        if ($check_sum != 0)
            return 10 - $check_sum;
        else
            return $check_sum;
    }

    public function code()
    {
        return $this->code;
    }
}