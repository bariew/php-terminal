<?php

/**
 * @copyright (c) 2015, Galament
 * @author Petrov Aleksanr <burnb83@gmail.com>
 * Date: 17.09.2015
 * Time: 11:32
 */
class Decode
{
    /**
     * Decode Unicode Characters from \u0000 ASCII syntax.
     *
     * This algorithm was originally developed for the
     * Solar Framework by Paul M. Jones
     *
     * @link   http://solarphp.com/
     * @link   http://svn.solarphp.com/core/trunk/Solar/Json.php
     * @param  string $chrs
     * @return string
     */
    public static function utf($chrs)
    {
        $utf8        = '';
        $strlen_chrs = strlen($chrs);

        for($i = 0; $i < $strlen_chrs; $i++) {

            $ord_chrs_c = ord($chrs[$i]);

            switch (true) {
                case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $i, 6)):
                    // single, escaped unicode character
                    $utf16 = chr(hexdec(substr($chrs, ($i + 2), 2)))
                        . chr(hexdec(substr($chrs, ($i + 4), 2)));
                    $utf8 .= self::_utf162utf8($utf16);
                    $i += 5;
                    break;
                case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                    $utf8 .= $chrs[$i];
                    break;
                case ($ord_chrs_c & 0xE0) == 0xC0:
                    // characters U-00000080 - U-000007FF, mask 110XXXXX
                    //see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $i, 2);
                    ++$i;
                    break;
                case ($ord_chrs_c & 0xF0) == 0xE0:
                    // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $i, 3);
                    $i += 2;
                    break;
                case ($ord_chrs_c & 0xF8) == 0xF0:
                    // characters U-00010000 - U-001FFFFF, mask 11110XXX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $i, 4);
                    $i += 3;
                    break;
                case ($ord_chrs_c & 0xFC) == 0xF8:
                    // characters U-00200000 - U-03FFFFFF, mask 111110XX
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $i, 5);
                    $i += 4;
                    break;
                case ($ord_chrs_c & 0xFE) == 0xFC:
                    // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                    // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $i, 6);
                    $i += 5;
                    break;
            }
        }

        var_dump($utf8);
    }

    /**
     * Convert a string from one UTF-16 char to one UTF-8 char.
     *
     * Normally should be handled by mb_convert_encoding, but
     * provides a slower PHP-only method for installations
     * that lack the multibye string extension.
     *
     * This method is from the Solar Framework by Paul M. Jones
     *
     * @link   http://solarphp.com
     * @param  string $utf16 UTF-16 character
     * @return string UTF-8 character
     */
    protected static function _utf162utf8($utf16)
    {
        // Check for mb extension otherwise do by hand.
        if( function_exists('mb_convert_encoding') ) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }

        $bytes = (ord($utf16[0]) << 8) | ord($utf16[1]);

        switch (true) {
            case ((0x7F & $bytes) == $bytes):
                // this case should never be reached, because we are in ASCII range
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
                // return a 2-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xC0 | (($bytes >> 6) & 0x1F))
                . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
                // return a 3-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xE0 | (($bytes >> 12) & 0x0F))
                . chr(0x80 | (($bytes >> 6) & 0x3F))
                . chr(0x80 | ($bytes & 0x3F));
        }

        // ignoring UTF-32 for now, sorry
        return '';
    }
}