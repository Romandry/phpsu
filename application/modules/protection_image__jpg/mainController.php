<?php


/**
 * mainController
 *
 * Protection image (captcha) module main controller class
 */

namespace modules\protection_image__jpg;

class mainController extends \BaseController
{


    /**
     * $_length
     *
     * Captcha string length,
     * You can set integer value or "random" for random length
     */

    private $_length = 8;


    /**
     * $_width
     *
     * Width of captcha image (px)
     */

    private $_width = 140;


    /**
     * $_height
     *
     * Height of captcha image (px)
     */

    private $_height = 60;


    /**
     * $_foregroundColor
     *
     * Foreground color RGB array (0-255, 0-255, 0-255) or "random"
     */

    private $_foregroundColor = 'random';


    /**
     * $_backgroundColor
     *
     * Background color RGB array (0-255, 0-255, 0-255)
     */

    private $_backgroundColor = array(240, 240, 240);


    /**
     * $_jpegQuality
     *
     * JPEG image quality (percents)
     */

    private $_jpegQuality = 100;


    /**
     * $_fluctuationAmplitude
     *
     * Fluctuation (wave) amplitude of image (px)
     */

    private $_fluctuationAmplitude = 5;



    /**
     * runBefore
     *
     * Check availability of session storage
     * Don't expect more route parameters
     *
     * @return null
     */

    public function runBefore()
    {
        if (\Router::shiftParam()) {
            throw new \SystemErrorException(array(
                'title'       => 'Protection image error',
                'description' => 'More route parameters found'
            ));
        }
    }


    /**
     * indexAction
     *
     * generate image and stored keystring into storage
     *
     * @return null
     */

    public function indexAction()
    {
        if ($this->_length === 'random') {
            $this->_length = mt_rand(5, 8);
        }

        $keyString = '';
        $fontsPath = APPLICATION . 'resources/protection-image/*.png';
        if (!$fonts = \FsUtils::glob($fontsPath)) {
            throw new \SystemErrorException(array(
                'title'       => 'Protection image error',
                'description' => 'Fonts in resources not found'
            ));
        }

        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyz';
        $alphabetLength = strlen($alphabet);
        $allowedSymbols = '23456789abcdeghkmnpqsuvxyz';

        if ($this->_foregroundColor === 'random') {
            $this->_foregroundColor = array(
                mt_rand(0,150),
                mt_rand(0,150),
                mt_rand(0,15)
            );
        }

        do {

            $asLen = strlen($allowedSymbols) - 1;
            for ($i = 0; $i < $this->_length; $i++) {
                $keyString .= $allowedSymbols{mt_rand(0, $asLen)};
            }

            $font = imagecreatefrompng($fonts[mt_rand(0, sizeof($fonts) - 1)]);
            imagealphablending($font, true);

            $fFileWidth  = imagesx($font);
            $fFileHeight = imagesy($font) - 1;
            $fontMetrics = array();
            $symbol = 0;
            $readingSymbol = false;

            for ($i = 0; $i < $fFileWidth && $symbol < $alphabetLength; $i++) {
                $transparent = (imagecolorat($font, $i, 0) >> 24) == 127;
                if(!$readingSymbol && !$transparent) {
                    $fontMetrics[$alphabet{$symbol}] = array('start' => $i);
                    $readingSymbol = true;
                    continue;
                }
                if($readingSymbol && $transparent) {
                    $fontMetrics[$alphabet{$symbol}]['end'] = $i;
                    $readingSymbol = false;
                    $symbol++;
                    continue;
                }
            }

            $img = imagecreatetruecolor($this->_width, $this->_height);
            imagealphablending($img, true);

            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle(
                $img,
                0,
                0,
                $this->_width - 1,
                $this->_height - 1,
                $white
            );

            $x = 1;
            for ($i = 0; $i < $this->_length; $i++) {

                $m = $fontMetrics[$keyString{$i}];
                $y = mt_rand( - $this->_fluctuationAmplitude, $this->_fluctuationAmplitude) + ($this->_height - $fFileHeight) / 2 + 2;
                $shift = 0;

                if ($i > 0) {

                    $shift = 10000;
                    for ($sy = 7; $sy < $fFileHeight - 20; $sy += 1) {
                        for ($sx = $m['start'] - 1; $sx < $m['end']; $sx += 1) {

                            $rgb = imagecolorat($font, $sx, $sy);
                            $opacity = $rgb >> 24;

                            if ($opacity < 127) {
                                $left = $sx - $m['start'] + $x;
                                $py = $sy + $y;
                                if ($py > $this->_height) {
                                    break;
                                }
                                for ($px = min($left, $this->_width - 1); $px > $left - 12 && $px >= 0; $px -= 1) {

                                    $color = imagecolorat($img, $px, $py) & 0xff;
                                    if($color + $opacity < 190) {
                                        if($shift > $left - $px) {
                                            $shift = $left - $px;
                                        }
                                        break;
                                    }
                                }
                                break;
                            }

                        }
                    }

                    if($shift == 10000) {
                        $shift = mt_rand(4, 6);
                    }

                }

                imagecopy(
                    $img,
                    $font,
                    $x - $shift,
                    $y,
                    $m['start'],
                    1,
                    $m['end'] - $m['start'],
                    $fFileHeight
                );
                $x += $m['end'] - $m['start'] - $shift;

            }

        } while ( $x >= ($this->_width - 10) );

        $center = $x / 2;
        $img2 = imagecreatetruecolor($this->_width, $this->_height);
        $foreground = imagecolorallocate(
            $img2,
            $this->_foregroundColor[0],
            $this->_foregroundColor[1],
            $this->_foregroundColor[2]
        );
        $background = imagecolorallocate(
            $img2,
            $this->_backgroundColor[0],
            $this->_backgroundColor[1],
            $this->_backgroundColor[2]
        );

        imagefilledrectangle(
            $img2,
            0,
            0,
            $this->_width  - 1,
            $this->_height - 1,
            $background
        );
        imagestring(
            $img2,
            2,
            $this->_width  - 2,
            $this->_height - 2,
            0,
            $background
        );

        // periods
        $rand1 = mt_rand(750000, 1200000) / 10000000;
        $rand2 = mt_rand(750000, 1200000) / 10000000;
        $rand3 = mt_rand(750000, 1200000) / 10000000;
        $rand4 = mt_rand(750000, 1200000) / 10000000;

        // phases
        $rand5 = mt_rand(0, 31415926) / 10000000;
        $rand6 = mt_rand(0, 31415926) / 10000000;
        $rand7 = mt_rand(0, 31415926) / 10000000;
        $rand8 = mt_rand(0, 31415926) / 10000000;

        // amplitudes
        $rand9  = mt_rand(330, 420) / 110;
        $rand10 = mt_rand(330, 450) / 110;


        // wave distortion
        for ($x = 0; $x < $this->_width; $x++) {
            for ($y = 0; $y < $this->_height; $y++) {

                $sx = $x + (sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6)) * $rand9 - $this->_width / 2 + $center + 1;
                $sy = $y + (sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8)) * $rand10;

                if ($sx < 0 || $sy < 0 || $sx >= $this->_width - 1 || $sy >= $this->_height - 1) {
                    continue;
                } else {
                    $color    = imagecolorat($img, $sx, $sy) & 0xFF;
                    $color_x  = imagecolorat($img, $sx + 1, $sy) & 0xFF;
                    $color_y  = imagecolorat($img, $sx, $sy + 1) & 0xFF;
                    $color_xy = imagecolorat($img, $sx + 1, $sy + 1) & 0xFF;
                }

                if ($color == 255 && $color_x == 255 && $color_y == 255 && $color_xy == 255) {
                    continue;
                } else if ($color == 0 && $color_x == 0 && $color_y == 0 && $color_xy == 0) {
                    $newred   = $this->_foregroundColor[0];
                    $newgreen = $this->_foregroundColor[1];
                    $newblue  = $this->_foregroundColor[2];
                } else {

                    $frsx  = $sx - floor($sx);
                    $frsy  = $sy - floor($sy);
                    $frsx1 = 1 - $frsx;
                    $frsy1 = 1 - $frsy;

                    $newcolor = $color * $frsx1 * $frsy1 + $color_x * $frsx * $frsy1 + $color_y * $frsx1 * $frsy + $color_xy * $frsx * $frsy;
                    if ($newcolor > 255) {
                        $newcolor = 255;
                    }

                    $newcolor  = $newcolor / 255;
                    $newcolor0 = 1 - $newcolor;
                    $newred    = $newcolor0 * $this->_foregroundColor[0] + $newcolor * 255;
                    $newgreen  = $newcolor0 * $this->_foregroundColor[1] + $newcolor * 255;
                    $newblue   = $newcolor0 * $this->_foregroundColor[2] + $newcolor * 255;

                }

                imagesetpixel(
                    $img2,
                    $x,
                    $y,
                    imagecolorallocate($img2, $newred, $newgreen, $newblue)
                );

            }
        }

        imagedestroy($img);

        $action = preg_replace('/[^a-z-]+/', '', \Request::getParam('action'));
        \Storage::write('__captcha' . ($action ?: '-' . $action), $keyString);

        \Request::addHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        \Request::addHeader('Cache-Control: no-store, no-cache, must-revalidate');
        \Request::addHeader('Cache-Control: post-check=0, pre-check=0');
        \Request::addHeader('Pragma: no-cache');
        \Request::addHeader('Content-Type: image/jpeg');

        \Request::sendHeaders();
        imagejpeg($img2, null, $this->_jpegQuality);

        exit();
    }
}
