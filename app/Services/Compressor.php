<?php

namespace App\Services;

class Compressor
{
    private const COMPRESSION_HIGHEST = 0;
    private const COMPRESSION_HIGH = 1;
    private const COMPRESSION_STANDARD = 2;
    private const COMPRESSION_LOW = 3;

    private const SORT_ABC = 0;
    private const SORT_LENGTH = 1;


    private $compressLevel = self::COMPRESSION_HIGHEST;
    private $sortMode = self::SORT_ABC;

    private $compressColors = true;
    private $compressFontWeigh = true;
    private $removeBackSlashes = true;
    private $removeSemicolons = true;

    public static function getCompressionLevels()
    {
        return [
            self::COMPRESSION_HIGHEST => 'Highest (all data in one line)',
            self::COMPRESSION_HIGH => 'High (each selector from the new line)',
            self::COMPRESSION_STANDARD => 'Standard (each property on the new line)',
            self::COMPRESSION_LOW => 'Low (each property on the new line with tab at the beginning)',
        ];
    }

    public static function getSortModes()
    {
        return [
            self::SORT_ABC => 'Alphabetically',
            self::SORT_LENGTH => 'By Length',
        ];
    }

    public function __construct(
        int $compressLevel,
        int $sortMode,
        bool $compressColors,
        bool $compressFontWeigh,
        bool $removeBackSlashes,
        bool $removeSemicolons
    ) {
        $this->compressLevel = $compressLevel;
        $this->sortMode = $sortMode;
        $this->compressColors = $compressColors;
        $this->compressFontWeigh = $compressFontWeigh;
        $this->removeBackSlashes = $removeBackSlashes;
        $this->removeSemicolons = $removeSemicolons;
    }

    public function process($cssSource): string
    {
        $cssSource = $this->compress($cssSource);
        // TODO: sort mode

        if ($this->compressFontWeigh) {
            $cssSource = $this->compressFontWeigh($cssSource);
        }

        if ($this->compressColors) {
            $cssSource = $this->compressColors($cssSource);
        }

        if ($this->removeBackSlashes) {
            $cssSource = $this->removeBackSlashes($cssSource);
        }

        if ($this->removeSemicolons) {
            $cssSource = $this->removeSemicolons($cssSource);
        }

        return $cssSource;
    }

    private function compress(string $cssSource)
    {
        switch ($this->compressLevel) {
            case self::COMPRESSION_HIGHEST:
                $cssSource = preg_replace(
                    [
                        // Remove comment(s)
                        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                        // Remove unused white-space(s)
                        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                    ],
                    [
                        '$1',
                        '$1$2$3$4$5$6$7',
                    ],
                    $cssSource
                );
                break;
            //TODO: implement other levels;
        }

        return $cssSource;
    }

    private function compressFontWeigh(string $cssSource)
    {
        return preg_replace(
            [
                '/font-weight:\\s*normal/',
                '/font-weight:\\s*bold/',
            ],
            [
                'font-weight:400',
                'font-weight:700',
            ],
            $cssSource
        );
    }

    private function compressColors(string $cssSource)
    {
        return preg_replace(
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            '$1$2$3',
            $cssSource
        );
    }

    private function removeBackSlashes(string $cssSource)
    {
        return stripcslashes($cssSource);
    }

    private function removeSemicolons(string $cssSource)
    {
        return preg_replace('/;?\\s*}/', "}", $cssSource);
    }
}
