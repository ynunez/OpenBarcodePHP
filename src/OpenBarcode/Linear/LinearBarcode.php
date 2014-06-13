<?php
namespace OpenBarcode\Linear;

/**
 * Class LinearBarcode
 * @package OpenBarcode\Linear
 *
 * @author Yoel Nunez (y.nunez@developers.floms.com)
 */
interface LinearBarcode
{
    public function buildSequence();

    public function mapSequence($char, $pos);

    public function barcode();

    public function code();

    public function calculateCheckDigit();
}