<?php
/*
* Copyright 2007 ZXing authors
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

namespace Zxing\Qrcode\Decoder;

use Zxing\FormatException;
use Zxing\Common\BitMatrix;

/**
 * See ISO 18004:2006 Annex D
 *
 * @author Sean Owen
 */
class Version
{

    /**
     * See ISO 18004:2006 Annex D.
     * Element i represents the raw version bits that specify version i + 7
     */
    private static $VERSION_DECODE_INFO = array(
        0x07C94, 0x085BC, 0x09A99, 0x0A4D3, 0x0BBF6,
        0x0C762, 0x0D847, 0x0E60D, 0x0F928, 0x10B78,
        0x1145D, 0x12A17, 0x13532, 0x149A6, 0x15683,
        0x168C9, 0x177EC, 0x18EC4, 0x191E1, 0x1AFAB,
        0x1B08E, 0x1CC1A, 0x1D33F, 0x1ED75, 0x1F250,
        0x209D5, 0x216F0, 0x228BA, 0x2379F, 0x24B0B,
        0x2542E, 0x26A64, 0x27541, 0x28C69
    );

    private static $VERSIONS;
    private $versionNumber;
    private $alignmentPatternCenters;
    private $ecBlocks;
    private $totalCodewords;

    public function __construct($versionNumber,
                                $alignmentPatternCenters,
                                $ecBlocks)
    {//ECBlocks... ecBlocks



        $this->versionNumber = $versionNumber;
        $this->alignmentPatternCenters = $alignmentPatternCenters;
        $this->ecBlocks = $ecBlocks;
        $total = 0;
        if(is_array($ecBlocks)) {
            $ecCodewords = $ecBlocks[0]->getECCodewordsPerBlock();
            $ecbArray = $ecBlocks[0]->getECBlocks();
        }else{
            $ecCodewords = $ecBlocks->getECCodewordsPerBlock();
            $ecbArray = $ecBlocks->getECBlocks();
        }
        foreach ($ecbArray as $ecBlock) {
            $total += $ecBlock->getCount() * ($ecBlock->getDataCodewords() + $ecCodewords);
        }
        $this->totalCodewords = $total;
    }
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }

    public function getAlignmentPatternCenters()
    {
        return $this->alignmentPatternCenters;
    }

    public function getTotalCodewords()
    {
        return $this->totalCodewords;
    }

    public function getDimensionForVersion()
    {
        return 17 + 4 * $this->versionNumber;
    }

    public function getECBlocksForLevel($ecLevel)
    {
        return $this->ecBlocks[$ecLevel->getOrdinal()];
    }

    /**
     * <p>Deduces version information purely from QR Code dimensions.</p>
     *
     * @param dimension dimension in modules
     * @return Version for a QR Code of that dimension
     * @throws FormatException if dimension is not 1 mod 4
     */
    public static function getProvisionalVersionForDimension($dimension)
    {
        if ($dimension % 4 != 1) {
            throw FormatException::getFormatInstance();
        }
        try {
            return self::getVersionForNumber(($dimension - 17) / 4);
        } catch (InvalidArgumentException $ignored) {
            throw FormatException::getFormatInstance();
        }
    }

    public  static function getVersionForNumber($versionNumber)
    {
        if ($versionNumber < 1 || $versionNumber > 40) {
            throw new \InvalidArgumentException();
        }
        if(!self::$VERSIONS){

            self::$VERSIONS = self::buildVersions();

        }
        return self::$VERSIONS[$versionNumber - 1];
    }

    static function decodeVersionInformation($versionBits)
    {
        $bestDifference = PHP_INT_MAX;
        $bestVersion = 0;
        for ($i = 0; $i < count(self::$VERSION_DECODE_INFO); $i++) {
            $targetVersion = self::$VERSION_DECODE_INFO[$i];
// Do the version info bits match exactly? done.
            if ($targetVersion == $versionBits) {
                return self::getVersionForNumber($i + 7);
            }
// Otherwise see if this is the closest to a real version info bit string
// we have seen so far
            $bitsDifference = FormatInformation::numBitsDiffering($versionBits, $targetVersion);
            if ($bitsDifference < $bestDifference) {
                $bestVersion = $i + 7;
                $bestDifference = $bitsDifference;
            }
        }
// We can tolerate up to 3 bits of error since no two version info codewords will
// differ in less than 8 bits.
        if ($bestDifference <= 3) {
            return self::getVersionForNumber($bestVersion);
        }
// If we didn't find a close enough match, fail
        return null;
    }

    /**
     * See ISO 18004:2006 Annex E
     */
    function buildFunctionPattern()
    {
        $dimension = self::getDimensionForVersion();
        $bitMatrix = new BitMatrix($dimension);

// Top left finder pattern + separator + format
        $bitMatrix->setRegion(0, 0, 9, 9);
// Top right finder pattern + separator + format
        $bitMatrix->setRegion($dimension - 8, 0, 8, 9);
// Bottom left finder pattern + separator + format
        $bitMatrix->setRegion(0, $dimension - 8, 9, 8);

// Alignment patterns
        $max = count($this->alignmentPatternCenters);
        for ($x = 0; $x < $max; $x++) {
            $i = $this->alignmentPatternCenters[$x] - 2;
            for ($y = 0; $y < $max; $y++) {
                if (($x == 0 && ($y == 0 || $y == $max - 1)) || ($x == $max - 1 && $y == 0)) {
// No alignment patterns near the three finder paterns
                    continue;
                }
                $bitMatrix->setRegion($this->alignmentPatternCenters[$y] - 2, $i, 5, 5);
            }
        }

// Vertical timing pattern
        $bitMatrix->setRegion(6, 9, 1, $dimension - 17);
// Horizontal timing pattern
        $bitMatrix->setRegion(9, 6, $dimension - 17, 1);

        if ($this->versionNumber > 6) {
// Version info, top right
            $bitMatrix->setRegion($dimension - 11, 0, 3, 6);
// Version info, bottom left
            $bitMatrix->setRegion(0, $dimension - 11, 6, 3);
        }

        return $bitMatrix;
    }
    /**
     * See ISO 18004:2006 6.5.1 Table 9
     */
    private static function buildVersions()
    {


        return array(
            new Version(1, array(),
                array(new ECBlocks(7, array(new ECB(1, 19))),
                    new ECBlocks(10, array(new ECB(1, 16))),
                    new ECBlocks(13, array(new ECB(1, 13))),
                    new ECBlocks(17, array(new ECB(1, 9))))),
            new Version(2, array(6, 18),
                array(new ECBlocks(10, array(new ECB(1, 34))),
                    new ECBlocks(16, array(new ECB(1, 28))),
                    new ECBlocks(22, array(new ECB(1, 22))),
                    new ECBlocks(28, array(new ECB(1, 16))))),
            new Version(3, array(6, 22),
                array( new ECBlocks(15, array(new ECB(1, 55))),
                    new ECBlocks(26, array(new ECB(1, 44))),
                    new ECBlocks(18, array(new ECB(2, 17))),
                    new ECBlocks(22, array(new ECB(2, 13))))),
            new Version(4, array(6, 26),
                array(new ECBlocks(20, array(new ECB(1, 80))),
                    new ECBlocks(18, array(new ECB(2, 32))),
                    new ECBlocks(26, array(new ECB(2, 24))),
                    new ECBlocks(16, array(new ECB(4, 9))))),
            new Version(5, array(6, 30),
                array(new ECBlocks(26, array(new ECB(1, 108))),
                    new ECBlocks(24, array(new ECB(2, 43))),
                    new ECBlocks(18, array(new ECB(2, 15),
                        new ECB(2, 16))),
                    new ECBlocks(22, array(new ECB(2, 11),
                        new ECB(2, 12))))),
            new Version(6, array(6, 34),
                array(new ECBlocks(18, array(new ECB(2, 68))),
                    new ECBlocks(16, array(new ECB(4, 27))),
                    new ECBlocks(24, array(new ECB(4, 19))),
                    new ECBlocks(28, array(new ECB(4, 15))))),
            new Version(7, array(6, 22, 38),
                array(new ECBlocks(20, array(new ECB(2, 78))),
                    new ECBlocks(18, array(new ECB(4, 31))),
                    new ECBlocks(18, array(new ECB(2, 14),
                        new ECB(4, 15))),
                    new ECBlocks(26, array(new ECB(4, 13),
                        new ECB(1, 14))))),
            new Version(8, array(6, 24, 42),
                array(new ECBlocks(24, array(new ECB(2, 97))),
                    new ECBlocks(22, array(new ECB(2, 38),
                        new ECB(2, 39))),
                    new ECBlocks(22, array(new ECB(4, 18),
                        new ECB(2, 19))),
                    new ECBlocks(26, array(new ECB(4, 14),
                        new ECB(2, 15))))),
            new Version(9, array(6, 26, 46),
                array(new ECBlocks(30, array(new ECB(2, 116))),
                    new ECBlocks(22, array(new ECB(3, 36),
                        new ECB(2, 37))),
                    new ECBlocks(20, array(new ECB(4, 16),
                        new ECB(4, 17))),
                    new ECBlocks(24, array(new ECB(4, 12),
                        new ECB(4, 13))))),
            new Version(10, array(6, 28, 50),
                array(new ECBlocks(18, array(new ECB(2, 68),
                    new ECB(2, 69))),
                    new ECBlocks(26, array(new ECB(4, 43),
                        new ECB(1, 44))),
                    new ECBlocks(24, array(new ECB(6, 19),
                        new ECB(2, 20))),
                    new ECBlocks(28, array(new ECB(6, 15),
                        new ECB(2, 16))))),
            new Version(11, array(6, 30, 54),
                array(new ECBlocks(20, array(new ECB(4, 81))),
                    new ECBlocks(30, array(new ECB(1, 50),
                        new ECB(4, 51))),
                    new ECBlocks(28, array(new ECB(4, 22),
                        new ECB(4, 23))),
                    new ECBlocks(24, array(new ECB(3, 12),
                        new ECB(8, 13))))),
            new Version(12, array(6, 32, 58),
                array(new ECBlocks(24, array(new ECB(2, 92),
                    new ECB(2, 93))),
                    new ECBlocks(22, array(new ECB(6, 36),
                        new ECB(2, 37))),
                    new ECBlocks(26, array(new ECB(4, 20),
                        new ECB(6, 21))),
                    new ECBlocks(28, array(new ECB(7, 14),
                        new ECB(4, 15))))),
            new Version(13, array(6, 34, 62),
                array(new ECBlocks(26, array(new ECB(4, 107))),
                    new ECBlocks(22, array(new ECB(8, 37),
                        new ECB(1, 38))),
                    new ECBlocks(24, array(new ECB(8, 20),
                        new ECB(4, 21))),
                    new ECBlocks(22, array(new ECB(12, 11),
                        new ECB(4, 12))))),
            new Version(14, array(6, 26, 46, 66),
                array(new ECBlocks(30, array(new ECB(3, 115),
                    new ECB(1, 116))),
                    new ECBlocks(24, array(new ECB(4, 40),
                        new ECB(5, 41))),
                    new ECBlocks(20, array(new ECB(11, 16),
                        new ECB(5, 17))),
                    new ECBlocks(24, array(new ECB(11, 12),
                        new ECB(5, 13))))),
            new Version(15, array(6, 26, 48, 70),
                array(new ECBlocks(22, array(new ECB(5, 87),
                    new ECB(1, 88))),
                    new ECBlocks(24, array(new ECB(5, 41),
                        new ECB(5, 42))),
                    new ECBlocks(30, array(new ECB(5, 24),
                        new ECB(7, 25))),
                    new ECBlocks(24, array(new ECB(11, 12),
                        new ECB(7, 13))))),
            new Version(16, array(6, 26, 50, 74),
                array(new ECBlocks(24, array(new ECB(5, 98),
                    new ECB(1, 99))),
                    new ECBlocks(28, array(new ECB(7, 45),
                        new ECB(3, 46))),
                    new ECBlocks(24, array(new ECB(15, 19),
                        new ECB(2, 20))),
                    new ECBlocks(30, array(new ECB(3, 15),
                        new ECB(13, 16))))),
            new Version(17, array(6, 30, 54, 78),
                array(new ECBlocks(28, array(new ECB(1, 107),
                    new ECB(5, 108))),
                    new ECBlocks(28, array(new ECB(10, 46),
                        new ECB(1, 47))),
                    new ECBlocks(28, array(new ECB(1, 22),
                        new ECB(15, 23))),
                    new ECBlocks(28, array(new ECB(2, 14),
                        new ECB(17, 15))))),
            new Version(18, array(6, 30, 56, 82),
                array(new ECBlocks(30, array(new ECB(5, 120),
                    new ECB(1, 121))),
                    new ECBlocks(26, array(new ECB(9, 43),
                        new ECB(4, 44))),
                    new ECBlocks(28, array(new ECB(17, 22),
                        new ECB(1, 23))),
                    new ECBlocks(28, array(new ECB(2, 14),
                        new ECB(19, 15))))),
            new Version(19, array(6, 30, 58, 86),
                array(new ECBlocks(28, array(new ECB(3, 113),
                    new ECB(4, 114))),
                    new ECBlocks(26, array(new ECB(3, 44),
                        new ECB(11, 45))),
                    new ECBlocks(26, array(new ECB(17, 21),
                        new ECB(4, 22))),
                    new ECBlocks(26, array(new ECB(9, 13),
                        new ECB(16, 14))))),
            new Version(20, array(6, 34, 62, 90),
                array(new ECBlocks(28, array(new ECB(3, 107),
                    new ECB(5, 108))),
                    new ECBlocks(26, array(new ECB(3, 41),
                        new ECB(13, 42))),
                    new ECBlocks(30, array(new ECB(15, 24),
                        new ECB(5, 25))),
                    new ECBlocks(28, array(new ECB(15, 15),
                        new ECB(10, 16))))),
            new Version(21, array(6, 28, 50, 72, 94),
                array( new ECBlocks(28, array(new ECB(4, 116),
                    new ECB(4, 117))),
                    new ECBlocks(26, array(new ECB(17, 42))),
                    new ECBlocks(28, array(new ECB(17, 22),
                        new ECB(6, 23))),
                    new ECBlocks(30, array(new ECB(19, 16),
                        new ECB(6, 17))))),
            new Version(22, array(6, 26, 50, 74, 98),
                array(new ECBlocks(28, array(new ECB(2, 111),
                    new ECB(7, 112))),
                    new ECBlocks(28, array(new ECB(17, 46))),
                    new ECBlocks(30, array(new ECB(7, 24),
                        new ECB(16, 25))),
                    new ECBlocks(24, array(new ECB(34, 13))))),
            new Version(23, array(6, 30, 54, 78, 102),
                new ECBlocks(30, array(new ECB(4, 121),
                    new ECB(5, 122))),
                new ECBlocks(28, array(new ECB(4, 47),
                    new ECB(14, 48))),
                new ECBlocks(30, array(new ECB(11, 24),
                    new ECB(14, 25))),
                new ECBlocks(30, array(new ECB(16, 15),
                    new ECB(14, 16)))),
            new Version(24, array(6, 28, 54, 80, 106),
                array(new ECBlocks(30, array(new ECB(6, 117),
                    new ECB(4, 118))),
                    new ECBlocks(28, array(new ECB(6, 45),
                        new ECB(14, 46))),
                    new ECBlocks(30, array(new ECB(11, 24),
                        new ECB(16, 25))),
                    new ECBlocks(30, array(new ECB(30, 16),
                        new ECB(2, 17))))),
            new Version(25, array(6, 32, 58, 84, 110),
                array(new ECBlocks(26, array(new ECB(8, 106),
                    new ECB(4, 107))),
                    new ECBlocks(28, array(new ECB(8, 47),
                        new ECB(13, 48))),
                    new ECBlocks(30, array(new ECB(7, 24),
                        new ECB(22, 25))),
                    new ECBlocks(30, array(new ECB(22, 15),
                        new ECB(13, 16))))),
            new Version(26, array(6, 30, 58, 86, 114),
                array(new ECBlocks(28, array(new ECB(10, 114),
                    new ECB(2, 115))),
                    new ECBlocks(28, array(new ECB(19, 46),
                        new ECB(4, 47))),
                    new ECBlocks(28, array(new ECB(28, 22),
                        new ECB(6, 23))),
                    new ECBlocks(30, array(new ECB(33, 16),
                        new ECB(4, 17))))),
            new Version(27, array(6, 34, 62, 90, 118),
                array(new ECBlocks(30, array(new ECB(8, 122),
                    new ECB(4, 123))),
                    new ECBlocks(28, array(new ECB(22, 45),
                        new ECB(3, 46))),
                    new ECBlocks(30, array(new ECB(8, 23),
                        new ECB(26, 24))),
                    new ECBlocks(30, array(new ECB(12, 15),
                        new ECB(28, 16))))),
            new Version(28, array(6, 26, 50, 74, 98, 122),
                array(new ECBlocks(30, array(new ECB(3, 117),
                    new ECB(10, 118))),
                    new ECBlocks(28, array(new ECB(3, 45),
                        new ECB(23, 46))),
                    new ECBlocks(30, array(new ECB(4, 24),
                        new ECB(31, 25))),
                    new ECBlocks(30, array(new ECB(11, 15),
                        new ECB(31, 16))))),
            new Version(29, array(6, 30, 54, 78, 102, 126),
                array(new ECBlocks(30, array(new ECB(7, 116),
                    new ECB(7, 117))),
                    new ECBlocks(28, array(new ECB(21, 45),
                        new ECB(7, 46))),
                    new ECBlocks(30, array(new ECB(1, 23),
                        new ECB(37, 24))),
                    new ECBlocks(30, array(new ECB(19, 15),
                        new ECB(26, 16))))),
            new Version(30, array(6, 26, 52, 78, 104, 130),
                array(new ECBlocks(30, array(new ECB(5, 115),
                    new ECB(10, 116))),
                    new ECBlocks(28, array(new ECB(19, 47),
                        new ECB(10, 48))),
                    new ECBlocks(30, array(new ECB(15, 24),
                        new ECB(25, 25))),
                    new ECBlocks(30, array(new ECB(23, 15),
                        new ECB(25, 16))))),
            new Version(31, array(6, 30, 56, 82, 108, 134),
                array(new ECBlocks(30, array(new ECB(13, 115),
                    new ECB(3, 116))),
                    new ECBlocks(28, array(new ECB(2, 46),
                        new ECB(29, 47))),
                    new ECBlocks(30, array(new ECB(42, 24),
                        new ECB(1, 25))),
                    new ECBlocks(30, array(new ECB(23, 15),
                        new ECB(28, 16))))),
            new Version(32, array(6, 34, 60, 86, 112, 138),
                array(new ECBlocks(30, array(new ECB(17, 115))),
                    new ECBlocks(28, array(new ECB(10, 46),
                        new ECB(23, 47))),
                    new ECBlocks(30, array(new ECB(10, 24),
                        new ECB(35, 25))),
                    new ECBlocks(30, array(new ECB(19, 15),
                        new ECB(35, 16))))),
            new Version(33, array(6, 30, 58, 86, 114, 142),
                array(new ECBlocks(30, array(new ECB(17, 115),
                    new ECB(1, 116))),
                    new ECBlocks(28, array(new ECB(14, 46),
                        new ECB(21, 47))),
                    new ECBlocks(30, array(new ECB(29, 24),
                        new ECB(19, 25))),
                    new ECBlocks(30, array(new ECB(11, 15),
                        new ECB(46, 16))))),
            new Version(34, array(6, 34, 62, 90, 118, 146),
                array(new ECBlocks(30, array(new ECB(13, 115),
                    new ECB(6, 116))),
                    new ECBlocks(28, array(new ECB(14, 46),
                        new ECB(23, 47))),
                    new ECBlocks(30, array(new ECB(44, 24),
                        new ECB(7, 25))),
                    new ECBlocks(30, array(new ECB(59, 16),
                        new ECB(1, 17))))),
            new Version(35, array(6, 30, 54, 78, 102, 126, 150),
                array(new ECBlocks(30, array(new ECB(12, 121),
                    new ECB(7, 122))),
                    new ECBlocks(28, array(new ECB(12, 47),
                        new ECB(26, 48))),
                    new ECBlocks(30, array(new ECB(39, 24),
                        new ECB(14, 25))),
                    new ECBlocks(30, array(new ECB(22, 15),
                        new ECB(41, 16))))),
            new Version(36, array(6, 24, 50, 76, 102, 128, 154),
                array(new ECBlocks(30, array(new ECB(6, 121),
                    new ECB(14, 122))),
                    new ECBlocks(28, array(new ECB(6, 47),
                        new ECB(34, 48))),
                    new ECBlocks(30, array(new ECB(46, 24),
                        new ECB(10, 25))),
                    new ECBlocks(30, array(new ECB(2, 15),
                        new ECB(64, 16))))),
            new Version(37, array(6, 28, 54, 80, 106, 132, 158),
                array(new ECBlocks(30, array(new ECB(17, 122),
                    new ECB(4, 123))),
                    new ECBlocks(28, array(new ECB(29, 46),
                        new ECB(14, 47))),
                    new ECBlocks(30, array(new ECB(49, 24),
                        new ECB(10, 25))),
                    new ECBlocks(30, array(new ECB(24, 15),
                        new ECB(46, 16))))),
            new Version(38, array(6, 32, 58, 84, 110, 136, 162),
                array(new ECBlocks(30, array(new ECB(4, 122),
                    new ECB(18, 123))),
                    new ECBlocks(28, array(new ECB(13, 46),
                        new ECB(32, 47))),
                    new ECBlocks(30, array(new ECB(48, 24),
                        new ECB(14, 25))),
                    new ECBlocks(30, array(new ECB(42, 15),
                        new ECB(32, 16))))),
            new Version(39, array(6, 26, 54, 82, 110, 138, 166),
                array(new ECBlocks(30, array(new ECB(20, 117),
                    new ECB(4, 118))),
                    new ECBlocks(28, array(new ECB(40, 47),
                        new ECB(7, 48))),
                    new ECBlocks(30, array(new ECB(43, 24),
                        new ECB(22, 25))),
                    new ECBlocks(30, array(new ECB(10, 15),
                        new ECB(67, 16))))),
            new Version(40, array(6, 30, 58, 86, 114, 142, 170),
                array(new ECBlocks(30, array(new ECB(19, 118),
                    new ECB(6, 119))),
                    new ECBlocks(28, array(new ECB(18, 47),
                        new ECB(31, 48))),
                    new ECBlocks(30, array(new ECB(34, 24),
                        new ECB(34, 25))),
                    new ECBlocks(30, array(new ECB(20, 15),
                        new ECB(61, 16)))))
        );
    }
}

/**
 * <p>Encapsulates a set of error-correction blocks in one symbol version. Most versions will
 * use blocks of differing sizes within one version, so, this encapsulates the parameters for
 * each set of blocks. It also holds the number of error-correction codewords per block since it
 * will be the same across all blocks within one version.</p>
 */
final class ECBlocks
{
    private $ecCodewordsPerBlock;
    private $ecBlocks;

    function __construct($ecCodewordsPerBlock, $ecBlocks)
    {
        $this->ecCodewordsPerBlock = $ecCodewordsPerBlock;
        $this->ecBlocks = $ecBlocks;
    }

    public function getECCodewordsPerBlock()
    {
        return $this->ecCodewordsPerBlock;
    }

    public function getNumBlocks()
    {
        $total = 0;
        foreach ($this->ecBlocks as $ecBlock) {
            $total += $ecBlock->getCount();
        }
        return $total;
    }

    public function getTotalECCodewords()
    {
        return $this->ecCodewordsPerBlock * $this->getNumBlocks();
    }

    public function getECBlocks()
    {
        return $this->ecBlocks;
    }
}

/**
 * <p>Encapsualtes the parameters for one error-correction block in one symbol version.
 * This includes the number of data codewords, and the number of times a block with these
 * parameters is used consecutively in the QR code version's format.</p>
 */
final class ECB
{
    private $count;
    private $dataCodewords;

    function __construct($count, $dataCodewords)
    {
        $this->count = $count;
        $this->dataCodewords = $dataCodewords;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getDataCodewords()
    {
        return $this->dataCodewords;
    }


//@Override
    public function toString()
    {
        die('Version ECB toString()');
        //  return parent::$versionNumber;
    }


}


