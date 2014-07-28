<?php

namespace OpenBarcode\Linear;

/**
 * Class Codabar
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
class Codabar implements LinearBarcode
{

    private $bars = array();
    private $code = null;

    public function __construct($code)
    {
        $this->code = strtoupper($code);

        $this->buildSequence();
    }

    public function buildSequence()
    {
        $code = "A" . $this->code . "B";

        for ($i = 0; $i < strlen($code); $i++) {
            $this->bars = array_merge($this->bars, $this->mapSequence($code[$i], $i));
            $this->bars[] = 0;
        }
    }

    public function barcode()
    {
        return $this->bars;
    }

    public function mapSequence($char, $pos)
    {

        $sequence = array(
            "0" => array(1, 0, 1, 0, 1, 0, 0, 1, 1),
            "1" => array(1, 0, 1, 0, 1, 1, 0, 0, 1),
            "2" => array(1, 0, 1, 0, 0, 1, 0, 1, 1),
            "3" => array(1, 1, 0, 0, 1, 0, 1, 0, 1),
            "4" => array(1, 0, 1, 1, 0, 1, 0, 0, 1),
            "5" => array(1, 1, 0, 1, 0, 1, 0, 0, 1),
            "6" => array(1, 0, 0, 1, 0, 1, 0, 1, 1),
            "7" => array(1, 0, 0, 1, 0, 1, 1, 0, 1),
            "8" => array(1, 0, 0, 1, 1, 0, 1, 0, 1),
            "9" => array(1, 1, 0, 1, 0, 0, 1, 0, 1),
            "-" => array(1, 0, 1, 0, 0, 1, 1, 0, 1),
            "$" => array(1, 0, 1, 1, 0, 0, 1, 0, 1),
            ":" => array(1, 1, 0, 1, 0, 1, 1, 0, 1, 1),
            "/" => array(1, 1, 0, 1, 1, 0, 1, 0, 1, 1),
            "." => array(1, 1, 0, 1, 1, 0, 1, 1, 0, 1),
            "+" => array(1, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1),
            "A" => array(1, 0, 1, 1, 0, 0, 1, 0, 0, 1),
            "B" => array(1, 0, 1, 0, 0, 1, 0, 0, 1, 1),
            "C" => array(1, 0, 0, 1, 0, 0, 1, 0, 1, 1),
            "D" => array(1, 0, 1, 0, 0, 1, 1, 0, 0, 1),
        );

        return $sequence[$char];
    }


    public function calculateCheckDigit()
    {
        return 0;
    }

    public function code()
    {
        return $this->code;
    }
}