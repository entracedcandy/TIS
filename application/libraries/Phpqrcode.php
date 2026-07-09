<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Phpqrcode {
    public function __construct()
    {
        include(APPPATH . 'libraries/phpqrcode/qrlib.php');
    }
    
    public function generate($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        QRcode::png($text, $outfile, $level, $size, $margin);
    }
}