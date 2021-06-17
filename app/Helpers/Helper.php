<?php

namespace App\Helpers;

use App\Helpers\Linkify\Linkify;
use App\Helpers\Sentence\Sentence;
use App\Helpers\Stringizer\Stringizer;
use Carbon\Carbon;
use DonatelloZa\RakePlus\RakePlus;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
## or
use OpenGraph;
use SEO;
use SEOMeta;
use Session;


define('DECIMALS', 2);
define('DEC_POINT', ".");
define('THOUSANDS_SEP', ",");

class Helper
{

    /**
     * Unlike standard empty function isEmpty also assigns true if value contains whitespaces, newlines, tabs
     * @param mixed $value
     * @return boolean
     */
    public function isEmpty($value)
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        if (is_array($value)) {
            if (count(array_filter($value)) == 0) {
                return true;
            }
        } elseif (!isset($value) || empty($value) || $value == '' || is_null($value)) {
            return true;
        }
        return false;
    }

    /**
     * Search for the contents of an array in a given string
     * @param type $haystack The string to search
     * @param mixed(Array/String) $arr The array to whose contents will be searched for
     * @return boolean True on success, false on failure
     */
    public function stringSearch($haystack, $arr)
    {
        $found = false;
        if (is_array($arr)) {
            foreach ($arr as $value) {
                $found = $this->searchWholeWord($haystack, $value);
                if ($found) {
                    break;
                }
            }
        } else {
            $found = $this->searchWholeWord($haystack, $arr);
        }
        return $found;
    }

    /**
     * Check if string contains array item
     * @param type $str
     * @param array $words
     * @return boolean
     */
    public function containsArrayItem($str, array $words)
    {
        if (!is_string($str)) {
            return false;
        }
        foreach ($words as $word) {
            if (is_string($word) && stripos($str, $word) === false) {
                return true;
            }
        }
        return false;
    }

    public function searchWholeWord($haystack, $needle)
    {
        if (preg_match("/\b$needle\b/i", $haystack) === 1) {
            return true;
        }
        return false;
    }

    public function getFileExtension($target_file)
    {
        return pathinfo($target_file, PATHINFO_EXTENSION);
    }

    public function getFilenameWithExtension($target_file)
    {
        return pathinfo($target_file, PATHINFO_BASENAME);
    }

    public function getFilename($target_file)
    {
        return pathinfo($target_file, PATHINFO_FILENAME);
    }

    public function getFileDirectoryName($target_file)
    {
        return pathinfo($target_file, PATHINFO_DIRNAME);
    }

    public function getFileIcon($file)
    {
        $icono = asset("frontend/images/icons/{$this->getFileExtension($file)}.png");
        return $icono;
    }

    public function countFiles($directory)
    {
        $files = File::files(public_path($directory));

        $filecount = 0;
        if ($files !== false) {

            $filecount = count($files);
        }
        return $filecount;
    }


    private function read_doc($filename)
    {
        $fileHandle = fopen($filename, "r");
        $line = @fread($fileHandle, filesize($filename));
        $lines = explode(chr(0x0D), $line);
        $outtext = "";
        foreach ($lines as $thisline) {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== false) || (strlen($thisline) == 0)) {
            } else {
                $outtext .= $thisline . " ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
        return $outtext;
    }

    private function read_docx($filename)
    {

        $striped_content = '';
        $content = '';

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) {
            return false;
        }

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == false) {
                continue;
            }

            if (zip_entry_name($zip_entry) != "word/document.xml") {
                continue;
            }

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        } // end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }



    public function convertToText($filename)
    {

        if (isset($filename) && !file_exists($filename)) {
            return "File Not exists";
        }

        $fileArray = pathinfo($filename);
        $file_ext = $fileArray['extension'];
        if ($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx") {
            if ($file_ext == "doc") {
                return $this->read_doc($filename);
            } elseif ($file_ext == "docx") {
                return $this->read_docx($filename);
            } elseif ($file_ext == "xlsx") {
                return $this->xlsx_to_text($filename);
            } elseif ($file_ext == "pptx") {
                return $this->pptx_to_text($filename);
            }
        } else {
            return "Invalid File Type";
        }
    }

    public function encrypt($value)
    {
        return encrypt($value);
    }

    public function decrypt($value)
    {
        return decrypt($value);
    }

    public function String2Hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    public function Hex2String($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    /**
     * Change date format between MySQL and System date
     * @param type $date
     * @param type $revert Set to true if you want to change to DD/MM/YYYY
     * @param type $revert Set to false if you want MySQL date format YYYY-MM-DD H:i:s
     * @return type
     */
    public function changeDateFormat($date)
    {
        return date("Y-m-d H:i:s", strtotime($date));
    }

    /**
     * Change time format
     * @param type $time
     * @return type time object
     */
    public function changeTimeFormat($time)
    {
        $time = date('H:i:s', strtotime($time));

        return $time;
    }

    /**
     * Determines if $number is between $min and $max
     *
     * @param  integer  $number     The number to test
     * @param  integer  $min        The minimum value in the range
     * @param  integer  $max        The maximum value in the range
     * @param  boolean  $inclusive  Whether the range should be inclusive or not
     * @return boolean              Whether the number was in the range
     */
    public function numberInRange($number, $min, $max, $inclusive = true)
    {
        return ($inclusive) ? ($number >= $min && $number <= $max) : ($number > $min && $number < $max);
    }

    public $naira = "&#8358;";

    /**
     * Display number in Naira format
     * @param type $amount
     * @return string
     */
    public function showMoney($amount)
    {
        return $this->naira . $this->formatDecimal($amount);
    }

    /**
     * Format a number into decimal places
     * @param type $amount
     * @return mixed
     */
    public function formatDecimal($amount)
    {
        $formatted = number_format($amount, DECIMALS, DEC_POINT, THOUSANDS_SEP);
        return $formatted;
    }

    /**
     * function, receives string, returns SEO friendly version for that strings,
     * sample: 'Hotels in Buenos Aires' => 'hotels-in-buenos-aires'
     * - converts all alpha chars to lowercase
     * - converts any char that is not digit, letter or - into - symbols into "-"
     * - not allow two "-" chars continued, convert them into only one single "-"
     * @param type $vp_string
     * @return string
     */
    public function URLify($vp_string)
    {
        $vp_string = trim($vp_string);
        $vp_string = html_entity_decode($vp_string);
        $vp_string = strip_tags($vp_string);
        $vp_string = strtolower($vp_string);
        $vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
        $vp_string = preg_replace('~ ~', '-', $vp_string);
        $vp_string = preg_replace('~-+~', '-', $vp_string);
        return $vp_string;
    }

    /**
     * Converts URLs and email addresses into clickable links
     * @param string $text
     * @return string
     */
    public function LINKify($text)
    {
        $linkify = new Linkify;
        return $linkify->process($text, array('attr' => array('style' => 'font-weight: bold; color: #0d59af;')));
    }

    public function getMimeType($file)
    {
        $mimetype = "";
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $file);
            finfo_close($finfo);
        } else {
            $mimetype = mime_content_type($file);
        }
        if ($this->isEmpty($mimetype)) {
            $mimetype = 'application/octet-stream';
        }
        return $mimetype;
    }

    public function base64Encode($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->base64Encode()->getString();
    }

    public function base64Decode($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->base64Decode()->getString();
    }

    public function betweenValues($value, $left, $right)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->between($left, $right)->getString();
    }

    public function camelize($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->camelize()->getString();
    }

    public function camelToSnake($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->camelToSnake()->getString();
    }

    public function charAt($value, $index)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->charAt($index)->getString();
    }

    public function chars($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->chars()->getString();
    }

    public function chopLeft($value, $prefix)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->chopLeft($prefix)->getString();
    }

    public function chopRight($value, $prefix)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->chopRight(
            $prefix
        )->getString();
    }

    public function collapseWhitespace($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->collapseWhitespace()->getString();
    }

    /**
     * Append 2 String values
     *
     * @param string $value
     *
     * @param string $preAppend
     *            flag when true to prepend value
     *
     * @return \Stringizer\Stringizer
     */
    public function concat($value, $value2, $prepend = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->concat($value2, $prepend)->getString();
    }

    public function contains($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->contains($needle);
    }

    public function containsIncaseSensitive($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->containsIncaseSensitive($needle);
    }

    public function containsCount($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->containsCount($needle);
    }

    public function containsCountIncaseSensitive($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->containsCountIncaseSensitive($needle);
    }

    public function dasherize($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->dasherize()->getString();
    }

    public function endsWith($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->endsWith($needle);
    }

    public function ensureLeft($value, $prefix)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->ensureLeft($prefix)->getString();
    }

    public function ensureRight($value, $suffix)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->ensureRight(
            $suffix
        )->getString();
    }

    public function first($value, $numberOfCharacters)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->first($numberOfCharacters)->getString();
    }

    public function hashCode($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->hashCode()->getString();
    }

    public function hasLowercase($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->hasLowercase()->getString();
    }

    public function hasUppercase($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->hasUppercase()->getString();
    }

    public function indexOf($value, $needle, $offset = 0)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->indexOf($needle, $offset)->getString();
    }

    public function indexOfCaseInsensitive($value, $needle, $offset = 0)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->indexOfCaseInsensitive($needle, $offset)->getString();
    }

    public function insertBetween($value, $left, $right)
    {
        return $left . $value . $right;
    }

    public function isAlpha($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isAlpha();
    }

    public function isAlphaNumeric($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isAlphaNumeric();
    }

    public function isAlphaNumericSpace($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isAlphaNumericSpace();
    }

    public function isAlphaNumericSpaceDash($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isAlphaNumericSpaceDash();
    }

    public function isAscii($value, $isPrintableOnly = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isAscii($isPrintableOnly);
    }

    public function isBase64($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isBase64();
    }

    /**
     * Alias for isEmpty
     */
    public function isBlank($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isBlank();
    }

    public function isDate($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isDate();
    }

    public function isDecimal($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isDecimal();
    }

    public function isEmail($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isEmail();
    }

    public function isHexColor($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isHexColor();
    }

    public function isHexDecimal($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isHexDecimal();
    }

    public function isIsbn10($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isIsbn10();
    }

    public function isIsbn13($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isIsbn13();
    }

    public function isIpv4($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isIpv4();
    }

    public function isIpv6($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isIpv6();
    }

    public function isJson($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isJson();
    }

    public function isLatitude($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isLatitude();
    }

    public function isLongitude($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isLongitude();
    }

    public function isMultiByte($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isMultiByte();
    }

    public function isNumber($value)
    {
        if ($this->isEmpty($value) && ($value !== 0)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isNumber();
    }

    public function isRgbColor($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->isRgbColor();
    }

    public function isSemver($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);


        return $s->isSemver();
    }

    public function isUrl($value, $santize = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->isUrl($santize);
    }

    public function join($value, $separator = ",")
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->join($value, $separator)->getString();
    }

    public function last($value, $numberOfCharacters)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->last($numberOfCharacters)->getString();
    }

    public function lastIndexOf($value, $needle, $offset = 0)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->lastIndexOf(
            $needle,
            $offset
        );
    }

    public function lastIndexOfCaseInsensitive($value, $needle, $offset = 0)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->lastIndexOfCaseInsensitive(
            $needle,
            $offset
        );
    }

    public function lineCount($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->lineCount();
    }

    /**
     * Length
     *
     * @return int length of string
     */
    public function length($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->length();
    }

    /**
     * Convert entire string to lowercase
     *
     * @return \Stringizer\Stringizer
     */
    public function lowercase($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->lowercase()->getString();
    }

    public function lowercaseFirst($value, $ignoreUppercaseFirst = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->lowercaseFirst(
            $ignoreUppercaseFirst
        )->getString();
    }

    public function padBoth($value, $padValue, $padAmount)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->padBoth($padValue, $padAmount)->getString();
    }

    public function padLeft($value, $padValue, $padAmount)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->padLeft($padValue, $padAmount)->getString();
    }

    public function padRight($value, $padValue, $padAmount)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->padRight($padValue, $padAmount)->getString();
    }

    public function randomAlpha($value, $length = 10)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->randomAlpha($length)->getString();
    }

    public function randomAlphanumeric($value, $length = 10)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->randomAlphanumeric($length)->getString();
    }

    public function randomNumeric($value, $length = 10)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->randomNumeric($length)->getString();
    }

    public function repeat($value, $repeatNumber)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->repeat($repeatNumber)->getString();
    }

    public function replace($search, $replace, $value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->replace($search, $replace)->getString();
    }

    public function replaceAccents($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->replaceAccents()->getString();
    }

    public function replaceIncaseSensitive($search, $replace, $value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->replaceIncaseSensitive($search, $replace)->getString();
    }

    public function removeNonAscii($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->removeNonAscii()->getString();
    }

    public function removeWhitespace($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->removeWhitespace()->getString();
    }

    public function reverse($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->reverse()->getString();
    }

    public function sentenceCount($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->sentenceCount();
    }

    public function startsWith($value, $needle)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->startsWith($needle);
    }

    public function stripPunctuation($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->stripPunctuation()->getString();
    }

    public function stripTags($value, $allowableTags = '')
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->stripTags($allowableTags)->getString();
    }

    public function split($value, $delimiter = ",")
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->split($delimiter);
    }

    public function subString($value, $start, $length = null)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->subString($start, $length)->getString();
    }

    public function swapCase($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->swapCase()->getString();
    }

    public function toBoolean($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);

        return $s->toBoolean();
    }

    public function trim($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->trim()->getString();
    }

    public function trimLeft($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->trimLeft()->getString();
    }

    public function trimRight($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->trimRight()->getString();
    }

    /**
     * Truncate remove the number of indicated values at the end of the string
     *
     * @param int $numberToTruncate
     *
     * @throws \InvalidArgumentException
     *
     * @return \Stringizer\Stringizer
     */
    public function truncate($value, $numberToTruncate)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->truncate($numberToTruncate)->getString();
    }

    public function truncateMatch($value, $stringToMatch, $truncateBefore = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->truncateMatch($stringToMatch, $truncateBefore)->getString();
    }

    public function truncateMatchCaseInsensitive($value, $stringToMatch, $truncateBefore = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->truncateMatchCaseInsensitive($stringToMatch, $truncateBefore)->getString();
    }

    public function uppercase($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->uppercase()->getString();
    }

    public function uppercaseFirst($value, $ignoreLowercaseFirst = false)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->uppercaseFirst($ignoreLowercaseFirst)->getString();
    }

    public function uppercaseWords($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $value = str_replace(',', ', ', $value);
        $value = str_replace('. ,', ', ', $value);
        $value = str_replace(' ,', ', ', $value);
        $value = str_replace('  ', ' ', $value);
        $s = new Stringizer($value);
        $value = $s->uppercaseWords()->getString();
        $delimiters = array('-', '\'', '/', '(', "'", ".", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        foreach ($delimiters as $delimiter) {
            if (strpos($value, $delimiter) !== FALSE) {
                $value = implode($delimiter, array_map('ucfirst', explode($delimiter, $value)));
            }
        }
        $value = str_replace(".", ". ", $value);
        return $value;
    }

    public function width($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->width();
    }

    public function wordCount($value)
    {
        if ($this->isEmpty($value)) {
            return $value;
        }
        $s = new Stringizer($value);
        return $s->wordCount();
    }

    public function setEncoding($encoding)
    {
        $s = new Stringizer("dummy-value");
        return $s->setEncoding("UTF-8");
    }

    public function getEncoding()
    {
        $s = new Stringizer("dummy-value");
        return $s->getEncoding();
    }

    /* alternative of var_dump with pre or json formating */

    public function vardump($var, $task = null)
    {
        if ($this->isEmpty($var)) {
            return NULL;
        }
        if (!empty($task)) {
            if ($task == 'pre') {
                echo '<div><pre>';
                var_dump($var, true);
                echo '</pre></div>';
            } elseif ($task == 'json') {
                $json = json_encode((array) $var);
                echo ($json);
            }
        } else {
            var_dump($var);
        }
    }

    /**
     * Generate random password
     * @param int $len
     * @return string
     */
    public function generateRandomPassword($len = 10)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789[{(*%+-_^$#&!=)}]";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $len; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    /**
     * Generate a string of random characters
     * @param int $numberOfCharacters
     * @return string of random characters
     */
    public function generateRandomString($numberOfCharacters = 32)
    {
        $string = NULL;
        try {
            $string = random_bytes($numberOfCharacters);
        } catch (\TypeError $e) {
            // Well, it's an integer, so this IS unexpected.
            die("An unexpected error has occurred");
        } catch (\Error $e) {
            // This is also unexpected because 32 is a reasonable integer.
            die("An unexpected error has occurred");
        } catch (\Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            die("Could not generate a random string. Is our OS secure?");
        }

        return $string;
    }

    /**
     * Generate a random integer between two given integers (inclusive)
     * @param int $start
     * @param int $end
     * @return int of random numbers
     */
    public function generateRandomNumber($min = 0, $max = 255)
    {
        $int = 0;
        if (!$this->isEmpty($min) && !$this->isEmpty($max)) {
            $min = intval($min);
            $max = intval($max);
            if ($min > $max) {
                $tmp = $max;
                $max = $min;
                $min = $tmp;
            }
            $int = mt_rand($min, $max);
        } else {
            $int = mt_rand();
        }
        return $int;
    }

    /**
     * Generate a random float between two given integers (inclusive)
     * @param float $start
     * @param float $end
     * @return float of random numbers
     */
    public function generateRandomFloat($min = 0, $max = 1)
    {
        $min = floatval($min);
        $max = floatval($max);
        if ($min > $max) {
            $tmp = $max;
            $max = $min;
            $min = $tmp;
        }
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * Convert time stamp to date
     * @param type $timestamp
     * @param type $dateTimeFormat
     * @return type
     */
    public function timestampToDate($timestamp, $dateTimeFormat = "Y-m-d H:i:s")
    {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        return $date->format($dateTimeFormat);
    }

    /**
     * Copy file to array
     * @param string $file F
     * @return array
     */
    public function fileToArray($file)
    {
        $codes = array();
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            @list($key, $val) = explode(" ", $line, 2);
            $codes[$key] = $val;
        }
        return $codes;
    }

    /**
     * Return local images as base64 encrypted code
     * @param string $filename
     * @param string $filetype
     * @return string
     */
    public function encodeImageTobase64($filename)
    {
        $retVal = NULL;
        if (!$this->isEmpty($filename)) {
            $check = getimagesize($filename);
            if ($check !== false) {
                $data = base64_encode(file_get_contents($filename));
                $retVal = 'data:' . $check["mime"] . ";base64," . $data;
            } else {
                $retVal = "File is not an image.";
            }
        }
        return $retVal;
    }

    /**
     * Return local file as base64 encrypted
     * @param type $filename
     * @return type
     */
    function encodeFileTobase64($filename)
    {
        if ($filename) {
            $vidbinary = fread(fopen($filename, "r"), filesize($filename));
            $filetype = $this->getMimeType($filename);
            return 'data:' . $filetype . ';base64,' . base64_encode($vidbinary);
        }
        return NULL;
    }


    /**
     * Computes the difference of arrays with additional index check.
     *
     * @param array $array1 the array to compare from
     * @param array $arrays an array(s) to compare against
     *
     * @return array an array containing all the values from
     *               array1 that are not present in any of the other arrays
     */
    public function arrayDiff(array $array1, ...$arrays): array
    {
        $difference = [];
        foreach ($arrays as $array2) {
            foreach ($array1 as $key => $value) {
                if (is_array($value)) {
                    if (!isset($array2[$key]) || !is_array($array2[$key])) {
                        $difference[$key] = $value;
                    } else {
                        $new_diff = static::diff($value, $array2[$key]);
                        if (!empty($new_diff)) {
                            $difference[$key] = $new_diff;
                        }
                    }
                } elseif (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                    $difference[$key] = $value;
                }
            }
        }

        return $difference;
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = array();
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }



    /**
     * Get a random value from an array.
     *
     * @param array $array
     * @param int   $numReq The amount of values to return
     *
     * @return mixed
     */
    public function arrayRandValue(array $array, $numReq = 1)
    {
        if (!count($array)) {
            return;
        }

        $keys = array_rand($array, $numReq);

        if ($numReq === 1) {
            return $array[$keys];
        }

        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Get a random value from an array, with the ability to skew the results.
     * Example: array_rand_weighted(['foo' => 1, 'bar' => 2]) has a 66% chance of returning bar.
     *
     * @param array $array
     *
     * @return mixed
     */
    public function arrayRandWeighted(array $array)
    {
        $options = [];

        foreach ($array as $option => $weight) {
            for ($i = 0; $i < $weight; ++$i) {
                $options[] = $option;
            }
        }

        return $this->arrayRandValue($options);
    }

    /**
     * Determine if all given needles are present in the haystack.
     *
     * @param array|string $needles
     * @param array        $haystack
     *
     * @return bool
     */
    public function valuesInArray($needles, array $haystack)
    {
        if (!is_array($needles)) {
            $needles = [$needles];
        }

        return count(array_intersect($needles, $haystack)) === count($needles);
    }

    /**
     * Determine if all given needles are present in the haystack as array keys.
     *
     * @param array|string $needles
     * @param array        $haystack
     *
     * @return bool
     */
    public function arrayKeysExist($needles, array $haystack)
    {
        if (!is_array($needles)) {
            return array_key_exists($needles, $haystack);
        }

        return $this->valuesInArray($needles, array_keys($haystack));
    }

    /**
     * Returns an array with two elements.
     *
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from array is returned in the first
     * element of result array. If not, it is return in the second element of result array.
     *
     * Array keys are preserved.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public function arraySplitFilter(array $array, callable $callback)
    {
        $passesFilter = array_filter($array, $callback);

        $negatedCallback = function ($item) use ($callback) {
            return !$callback($item);
        };

        $doesNotPassFilter = array_filter($array, $negatedCallback);

        return [$passesFilter, $doesNotPassFilter];
    }

    /**
     * Split an array in the given amount of pieces.
     *
     * @param array $array
     * @param int   $numberOfPieces
     * @param bool  $preserveKeys
     *
     * @return array
     */
    public function arraySplit(array $array, $numberOfPieces = 2, $preserveKeys = false)
    {
        if (count($array) === 0) {
            return [];
        }

        $splitSize = ceil(count($array) / $numberOfPieces);

        return array_chunk($array, $splitSize, $preserveKeys);
    }

    /**
     * Returns an array with the unique values from all the given arrays.
     *
     * @param \array[] $arrays
     *
     * @return array
     */
    public function arrayMergeValues(array ...$arrays)
    {
        $allValues = array_reduce($arrays, function ($carry, $array) {
            return array_merge($carry, $array);
        }, []);

        return array_values(array_unique($allValues));
    }

    /**
     * Flatten an array of arrays. The `$levels` parameter specifies how deep you want to
     * recurse in the array. If `$levels` is -1, the function will recurse infinitely.
     *
     * @param array $array
     * @param int   $levels
     *
     * @return array
     */
    public function arrayFlatten(array $array, $levels = -1)
    {
        if ($levels === 0) {
            return $array;
        }

        $flattened = [];

        if ($levels !== -1) {
            --$levels;
        }

        foreach ($array as $element) {
            $flattened = array_merge(
                $flattened,
                is_array($element) ? array_flatten($element, $levels) : [$element]
            );
        }

        return $flattened;
    }

    private function count_words($string)
    {
        // Return the number of words in a string.
        $string = str_replace("&#039;", "'", $string);
        $t = array(' ', "\t", '=', '+', '-', '*', '/', '\\', ',', '.', ';', ':', '[', ']', '{', '}', '(', ')', '<', '>', '&', '%', '$', '@', '#', '^', '!', '?', '~'); // separators
        $string = str_replace($t, " ", $string);
        $string = trim(preg_replace("/\s+/", " ", $string));
        $num = 0;
        if ($this->my_strlen($string) > 0) {
            $word_array = explode(" ", $string);
            $num = count($word_array);
        }
        return $num;
    }

    private function my_strlen($s)
    {
        // Return mb_strlen with encoding UTF-8.
        return mb_strlen($s, "UTF-8");
    }

    public function countWordsInfile($filename)
    {

        $retVal = $this->readFile($filename);

        if (FALSE !== strpos($retVal, "Invalid")) {
            return $retVal;
        }
        return $this->count_words($retVal);
    }

    public function numberOfPagesInFile($filename, $numberOfWordsPerPage)
    {
        return $this->countWordsInfile($filename) / $numberOfWordsPerPage;
    }

    public function getArrayValueByKey(array $array, $search)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === $key) {
                return $value;
            }
        }
        return -1;
    }

    public function getArrayKeyByValue($search, array $array)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === $value) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * Get the next value in an array after the provided key of the previous value
     *
     * @param string $key
     * @param array $array
     * @return mixed
     */
    public function getArrayNextValueAfterKey(string $key, array $array)
    {
        $currentKey = key($array);
        while ($currentKey !== null && $currentKey != $key) {
            next($array);
            $currentKey = key($array);
        }
        return next($array);
    }

    /**
     * Get the previous value in an array before the provided key of the current value
     *
     * @param string $key
     * @param array $array
     * @return mixed
     */
    public function getArrayPreviousValueAfterKey(string $key, array $array)
    {
        $currentKey = key($array);
        while ($currentKey !== null && $currentKey != $key) {
            prev($array);
            $currentKey = key($array);
        }
        return next($array);
    }

    public function searchNestedArray($search, array $array, $mode = 'value')
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === ${${"mode"}}) {
                return true;
            }
        }
        return false;
    }

    public function inArray($search, array $array, $mode = 'value')
    {
        return $this->searchNestedArray($search,  $array, $mode);
    }

    public function countItemsInArray($array, $value)
    {
        $count = 0;
        foreach ($array as $val) {
            if ($val == $value) {
                $count++;
            }
        }
        return $count;
    }

    public function isMultiDimensionalArray(array $array)
    {
        $flag = FALSE;
        while (list($k, $value) = each($array)) {
            if (is_array($value)) {
                $flag = TRUE;
            }
        }
        return $flag;
    }

    public function removeStopWords($word)
    {
        if (is_array($word)) {
            $word = $this->removeElementFromArray($word, $this->getStopWords());
        } else {
            foreach ($this->getStopWords() as $stopWord) {
                $newStopWords = "/\b$stopWord\b/";
                $word = preg_replace($newStopWords, '', $word);
            }
        }
        return $word;
    }

    public function isStopWord($word)
    {
        $word = $this->lowercase($word);
        foreach ($this->getStopWords() as $value) {
            if ($word === $value) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Remove duplicate values in array
     * @param array $arr
     * @param string $glue
     * @return mixed array/string
     */
    public function removeDuplicatesInArray(array $arr, $glue = NULL)
    {
        $result = array_unique($this->removeEmptyArrayElements($arr));
        if (!$this->isEmpty($glue)) {
            $result = $this->implodeArray($glue, $result);
        }
        return $result;
    }

    /**
     * Combine the values of an array using a glue
     * @param string $glue
     * @param array $array
     * @return array
     */
    public function implodeArray(string $glue, array $array)
    {
        return array_reduce($array, function ($carry, $item) use ($glue) {
            return !$carry ? $item : ($carry . $glue . $item);
        });
    }

    public function removeEmptyArrayElements($array)
    {
        return array_values(array_filter($array));
    }

    /**
     * Remove elements from array
     * @param array $array
     * @param array $to_remove
     * @return array
     */
    public function removeElementFromArray(array $array, array $to_remove)
    {
        return array_diff($this->removeEmptyArrayElements($array), $to_remove);
    }

    /**
     * Find the largest value in array
     * @param array $array
     * @return mixed
     */
    public function getLargestArrayValue(array $array)
    {
        $result = array_reduce($array, function ($carry, $item) {
            return $item > $carry ? $item : $carry;
        });
        return $result;
    }

    /**
     * Find the smallest value in array
     * @param array $array
     * @return mixed
     */
    public function getSmallestArrayValue(array $array)
    {
        $result = array_reduce($array, function ($carry, $item) {
            return $item < $carry ? $item : $carry;
        });
        return $result;
    }

    /**
     * Calculate the sum of values in array
     * @param array $array
     * @return mixed
     */
    public function getSumOfArrayValues(array $array)
    {
        $result = array_reduce($array, function ($carry, $item) {
            return $carry + $item;
        });
        return $result;
    }

    /**
     *
     * @param string $value
     * @return float
     */
    public function toFloat($value)
    {
        $num = floatval($this->replaceComma($value));
        return $num;
    }

    public function nairaToKobo($value)
    {
        return $value * 100;
    }

    public function koboToNaira($value)
    {
        return $value / 100;
    }

    /**
     * underscoreToCamelCase
     * Covert lower_underscored mysql notation into Camel/Pascal case notation
     * @param $string string to convert into Camel/Pascal case notation
     * @param bool $pascalCase If true the result is PascalCase
     * @return string
     */
    public static function underscoreToCamelCase($string, $pascalCase = false)
    {
        $string = strtolower($string);

        if ($pascalCase == true) {
            $string[0] = strtoupper($string[0]);
        }
        $func = function ($c) {
            return strtoupper($c[1]);
        };
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    /**
     * Replaces backslash present into MySQL strings which containing apostrophes.
     *
     * @param  string $field The field to replace
     * @return string the field without backslash for the apostrophes
     */
    public function replaceAposBackSlash($field)
    {
        $r1 = str_replace("\'", "'", $field);
        $r2 = str_replace("\\\\", "\\", $r1);
        return $r2;
    }

    /**
     * replace dashes with underscore
     * @param string $string
     * @return string
     */
    public function replaceDash($string)
    {
        $r = str_replace("-", "_", $string);

        return $r;
    }

    public function replaceComma($string)
    {
        $r = str_replace(",", "", $string);
        return $r;
    }

    public function detectBrowserLanguage()
    {
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
        } else {
            $lang = "en";
        }

        return $lang;
    }

    public function getPhrases($text)
    {
        // Note: en_US is the default language.
        $rake = RakePlus::create(strip_tags($text), 'en_US');

        // 'asc' is optional and is the default sort order
        $phrases = $rake->sort('asc')->get();

        return $phrases;
    }

    public  function getKeywords($text)
    {
        $keywords = RakePlus::create(strip_tags($text))->keywords();
        $keywords = $this->removeDuplicatesInArray($keywords);
        return $keywords;
    }


    public function randomColor()
    {
        $result = array('rgb' => '', 'hex' => '');
        foreach (array('r', 'b', 'g') as $col) {
            $rand = mt_rand(0, 255);
            $result['rgb'][$col] = $rand;
            $dechex = dechex($rand);
            if (strlen($dechex) < 2) {
                $dechex = '0' . $dechex;
            }
            $result['hex'] .= "#$dechex";
        }
        return $result;
    }

    public function deleteFile($folder = null, $filename = null)
    {
        $disk = 'public';
        Storage::disk($disk)->delete($folder . $filename);
    }

    public function uploadFile(UploadedFile $uploadedFile, $folder = null, $filename = null)
    {
        $disk = 'public';
        $name = !$this->isEmpty($filename) ? $filename : str_random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    public function checkFile($path)
    {
        $new_path = $path;
        $filename = $this->getFilename($path);
        $extension = $this->getFileExtension($path);
        $dir = $this->getFileDirectoryName($path);
        $i = 1;
        while (file_exists($new_path)) {
            // add and combine the filename, iterator, extension
            $new_path = implode("/", [$dir, $filename . '_' . $i . '.' . $extension]);
            $i++;
        }

        return $new_path;
    }

    /**
     * Check if it is a file
     * @param type $file
     * @return type
     */
    public function isFile($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return (strlen($ext) > 0) ? true : false;
    }

    /**
     * Check if upload is a video file
     * @param object $fileObject HTML File input element name
     * @return boolean
     */
    public function isVideo($filename)
    {
        if ($this->isEmpty($filename)) {
            return FALSE;
        }
        $EXT_LIST = ['mp4', 'mov', 'mpg', 'mpeg', 'wmv', 'mkv', 'ogg', 'webm',];
        return $this->contains($filename, 'video') || in_array($this->getFileExtension($filename), $EXT_LIST);
    }

    public function isImage($filename)
    {
        if ($this->isEmpty($filename)) {
            return FALSE;
        }
        $EXT_LIST = ['jpg', 'png', 'bmp', 'jpeg', 'gif',];
        return $this->contains($filename, 'image') || in_array($this->getFileExtension($filename), $EXT_LIST);
    }

    public function isAudio($filename)
    {
        if ($this->isEmpty($filename)) {
            return FALSE;
        }
        $EXT_LIST = ['ogg', 'mp3', 'wav', 'wmv'];
        return $this->contains($filename, 'audio') || in_array($this->getFileExtension($filename), $EXT_LIST);
    }

    public function isDocument($filename)
    {
        if ($this->isEmpty($filename)) {
            return FALSE;
        }
        $EXT_LIST = array(
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar', 'txt', 'pdf'
        );
        return in_array($this->getFileExtension($filename), $EXT_LIST);
    }

    public function getFirstWord($text)
    {
        if ($this->isEmpty($text)) {
            return $text;
        }
        $text = $this->stripTags($text);
        $words = str_word_count($text, 1);
        return $words[0];
    }

    /**
     * Extract all the urls in the text
     * @param type $text
     * @return type
     */
    public function extractUrls($text)
    {
        $url = [];
        preg_match_all('/(https?|ssh|ftp):\/\/[^\s"]+/', $text, $url);
        $all_url = (isset($url[0])) ? $url[0] : NULL; // Returns Array Of all Found URLs
        return $all_url;
    }

    /**
     * Get the first url in the text
     * @param type $text
     * @return type
     */
    public function getFirstUrl($text)
    {
        $url = (isset($this->extractUrls($text)[0])) ? $this->extractUrls($text)[0] : NULL; // Gives the First URL in Array of URL's
        return $url;
    }

    public function isLowerCase($string)
    {
        return $string === strtolower($string);
    }

    public function isUpperCase($string)
    {
        return $string === strtoupper($string);
    }

    public function isAnagram($string1, $string2)
    {
        return count_chars($string1, 1) === count_chars($string2, 1);
    }

    public function palindrome($string)
    {
        return strrev($string) === $string;
    }

    public function firstStringBetween($haystack, $start, $end)
    {
        $char = strpos($haystack, $start);
        if (!$char) {
            return '';
        }

        $char += strlen($start);
        $len = strpos($haystack, $end, $char) - $char;

        return substr($haystack, $char, $len);
    }

    public function compose($functions)
    {
        return array_reduce(
            $functions,
            function ($carry, $function) {
                return function ($x) use ($carry, $function) {
                    return $function($carry($x));
                };
            },
            function ($x) {
                return $x;
            }
        );
    }

    /**
     * Formats paragraphs around given text for all line breaks
     *  <br /> added for single line return
     *  <p> added for double line return
     *
     * @param string $text Text
     * @return string The text with proper <p> and <br /> tags
     * @link https://book.cakephp.org/3.0/en/views/helpers/text.html#converting-text-into-paragraphs
     */
    public function autoParagraph($text)
    {
        if (trim($text) !== '') {
            $text = preg_replace('|<br[^>]*>\s*<br[^>]*>|i', "\n\n", $text . "\n");
            $text = preg_replace("/\n\n+/", "\n\n", str_replace(["\r\n", "\r"], "\n", $text));
            $texts = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
            $text = '';
            foreach ($texts as $txt) {
                $text .= '<p>' . nl2br(trim($txt, "\n")) . "</p>\n";
            }
            $text = preg_replace('|<p>\s*</p>|', '', $text);
        }
        return $text;
    }

    public function getFirstSentence($text)
    {
        // Create a new instance
        $Sentence = new Sentence;
        $text = $this->stripTags($text);
        // Split into array of sentences
        $sentences = $Sentence->split($text);
        $val = isset($sentences[0]) ? $sentences[0] : NULL;
        return $val;
    }

    public function isSimilarImages($img1, $img2)
    {
        $images = new compareImages;
        $retVal = ($images->compare($img1, $img2) === 0) && ($this->isSimilarFiles($img1, $img2));
        return $retVal;
    }

    public function compareDirectories($srcDir, $destDir)
    {
        $cmp = new \App\Helpers\compareDirectories\compareDirectories; // Initialize the class set up the source and update(pristine) directories:
        $cmp->set_source($srcDir . '\Source'); // Directory where Source files are
        $cmp->set_update($destDir . '\Update'); // Directory where pristeen files are do the compare:
        $cmp->do_compare();     // Do the compare and get the results:
        $dir['removed'] = $cmp->get_removed();  // Get the results
        $dir['added'] = $cmp->get_added();   // ...
        $dir['changed'] = $cmp->get_changed();  // ...

        return $dir;
    }

    public function isSimilarFiles($file1, $file2)
    {
        return md5_file($file1) === md5_file($file2);
    }

    public function isSimilarText($text1, $text2)
    {
        $percent = floatval(0);
        similar_text($text1, $text2, $percent);
        return $percent === 100;
    }

    public function all($items, $func)
    {
        return count(array_filter($items, $func)) === count($items);
    }

    public function any($items, $func)
    {
        return count(array_filter($items, $func)) > 0;
    }

    public function chunk($items, $size)
    {
        return array_chunk($items, $size);
    }

    public function flatten($items)
    {
        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $result = array_merge($result, array_values($item));
            }
        }

        return $result;
    }

    public function deepFlatten($items)
    {
        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $result = array_merge($result, $this->deepFlatten($item));
            }
        }

        return $result;
    }

    public function drop($items, $n = 1)
    {
        return array_slice($items, $n);
    }

    public function findLast($items, $func)
    {
        $filteredItems = array_filter($items, $func);

        return array_pop($filteredItems);
    }

    public function findLastIndex($items, $func)
    {
        $keys = array_keys(array_filter($items, $func));

        return array_pop($keys);
    }

    public function head($items)
    {
        return reset($items);
    }

    public function tail($items)
    {
        return count($items) > 1 ? array_slice($items, 1) : $items;
    }

    public function lastArrayItem($items)
    {
        return end($items);
    }

    public function pull(&$items, ...$params)
    {
        $items = array_values(array_diff($items, $params));
        return $items;
    }

    public function pluck($items, $key)
    {
        return array_map(function ($item) use ($key) {
            return is_object($item) ? $item->$key : $item[$key];
        }, $items);
    }

    public function reject($items, $func)
    {
        return array_values(array_diff($items, array_filter($items, $func)));
    }

    public function remove($items, $func)
    {
        $filtered = array_filter($items, $func);

        return array_diff_key($items, $filtered);
    }

    public function take($items, $n = 1)
    {
        return array_slice($items, 0, $n);
    }

    public function without($items, ...$params)
    {
        return array_values(array_diff($items, $params));
    }

    public function hasDuplicates($items)
    {
        return count($items) > count(array_unique($items));
    }

    public function groupBy($items, $func)
    {
        $group = [];
        foreach ($items as $item) {
            if ((!is_string($func) && is_callable($func)) || function_exists($func)) {
                $key = call_user_func($func, $item);
                $group[$key][] = $item;
            } elseif (is_object($item)) {
                $group[$item->{$func}][] = $item;
            } elseif (isset($item[$func])) {
                $group[$item[$func]][] = $item;
            }
        }

        return $group;
    }

    /**
     * Get the average of values in array
     * @param array $items
     * @return int
     */
    public function averageOfArrayValues(array $items)
    {
        return count($items) === 0 ? 0 : array_sum($items) / count($items);
    }

    public function factorial($n)
    {
        if ($n <= 1) {
            return 1;
        }

        return $n * $this->factorial($n - 1);
    }

    public function fibonacci($n)
    {
        $sequence = [0, 1];

        for ($i = 0; $i < $n - 2; $i++) {
            array_push($sequence, array_sum(array_slice($sequence, -2, 2, true)));
        }

        return $sequence;
    }

    public function gcd(array $numbers)
    {
        if (count($numbers) > 2) {
            return array_reduce($numbers, 'gcd');
        }

        $r = $numbers[0] % $numbers[1];
        return $r === 0 ? abs($numbers[1]) : $this->gcd($numbers[1], $r);
    }

    public function lcm(array $numbers)
    {
        $ans = $numbers[0];
        for ($i = 1; $i < count($numbers); $i++) {
            $ans = ((($numbers[$i] * $ans)) / ($this->gcd($numbers[$i], $ans)));
        }

        return $ans;
    }

    public function isPrime($number)
    {
        $boundary = floor(sqrt($number));
        for ($i = 2; $i <= $boundary; $i++) {
            if ($number % $i === 0) {
                return false;
            }
        }

        return $number >= 2;
    }

    public function isEven($number)
    {
        return ($number % 2) === 0;
    }

    public function median($numbers)
    {
        sort($numbers);
        $totalNumbers = count($numbers);
        $mid = floor($totalNumbers / 2);

        return ($totalNumbers % 2) === 0 ? ($numbers[$mid - 1] + $numbers[$mid]) / 2 : $numbers[$mid];
    }

    public function timeAgo($date)
    {
        $now = new Carbon;
        $dt = new Carbon($date);
        return $dt->diffForHumans($now);
    }
    /**
     * Encode a string making sure that there are no symbols in retval
     * @param string $value
     * @return string
     */
    public function encodeString($value)
    {
        $value = $this->urlsafe_b64encode($value);
        return $value;
    }

    /**
     * Decode a string making sure that there are no symbols in retval
     * @param string $value
     * @return string
     */
    public function decodeString($value)
    {
        $value = $this->urlsafe_b64decode($value);
        return $value;
    }

    public function urlsafe_b64encode($string)
    {
        $data = $this->base64Encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_', ''), array('+', '/', '='), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return $this->base64Decode($data);
    }

    public function headingDivider($heading)
    {
        $head = <<< HEAD
        <div class="heading-divider"><span></span><span>$heading</span><span></span></div>
HEAD;
        return $head;
    }

    /**
     * Add active class to navigation links
     *
     * @param  string $routeName
     * @param  string $className
     * @return string
     */
    function active(string $routeName, string $className = 'active'): string
    {
        return (Route::current()->getName() === $routeName) ? $className : '';
    }

    /**
     * Return a formatted Carbon date.
     */
    function humanize_date(Carbon $date, string $format = 'd F Y, H:i'): string
    {
        return $date->format($format);
    }

    public function isRoute($url)
    {
        if ($this->isUrl($url)) {
            if (\Request::is($url)) {
                // show companies menu or something
                return true;
            }
        } else {
            if (\Route::current()->getName() == 'comp') {
                // We are on a correct route!
                return true;
            }
        }
        return false;
    }

    public function getDownload($file)
    {
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return response()->download($file, str_random(15) . '.' . $this->getFileExtension($file), $headers);
    }

    public function getSessionId()
    {
        return Session::getId();
    }

    /** @var string */
    protected $alphabet;

    /** @var int */
    protected $alphabetLength;


    /**
     * @param string $alphabet
     */
    public function __construct($alphabet = '')
    {
        if ('' != $alphabet) {
            $this->setAlphabet($alphabet);
        } else {
            $this->setAlphabet(
                implode(range('a', 'z'))
                    . implode(range('A', 'Z'))
                    . implode(range(0, 9))
            );
        }
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }

    /**
     * @param int $length
     * @return string
     */
    public function generate($length = 50)
    {
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    protected function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);

        return ($min + $rnd);
    }

    public function getReferenceCode()
    {
        // Call method to generate random string.
        $token = $this->generate();
        return  $token;
    }

    /**
     * Convert $_FILES to array
     * @param array $file_post
     * @return array
     */
    public function reArrayFiles(&$file_post)
    {
        $file_ary = array();
        $multiple = is_array($file_post['name']);
        $file_count = $multiple ? count($file_post['name']) : 1;
        $file_keys = array_keys($file_post);
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $multiple ? $file_post[$key][$i] : $file_post[$key];
            }
        }
        return $file_ary;
    }

    public function convertToObject(array $array)
    {
        return json_decode(json_encode($array));
    }

    public function SEOIndex($title, $description, $type)
    {
        SEO::setTitle($title);
        SEO::setDescription($description);
        SEO::opengraph()->setUrl('www.pharmacytherapon.com');
        SEO::setCanonical(url()->current());
        SEO::opengraph()->addProperty('type', $type);
        SEO::twitter()->setSite('@PharmaTherapon');
    }

    public function SEOPage($title, $description, $type, array $property)
    {
        $property = collect($property);

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::addMeta('article:published_time', $property->created_at->toW3CString(), 'property');
        SEOMeta::addMeta('article:section', $property->category, 'property');
        SEOMeta::addKeyword($property->keywords);

        OpenGraph::setDescription($description);
        OpenGraph::setTitle($title);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', $type);
        OpenGraph::addProperty('locale', 'pt-br');
        OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

        OpenGraph::addImage(url($property->cover_image));


        if ('video' == strtolower($type)) {
            // og:video
            OpenGraph::addVideo(url($property->path), [
                'secure_url' => secure_url($property->path),
                'type' => $property->mimeType,
                'width' => 400,
                'height' => 300
            ]);
        } elseif ('audio' == strtolower($type)) {
            // og:audio
            OpenGraph::addAudio(url($property->path), [
                'secure_url' => secure_url($property->path),
                'type' => $property->mimeType,
            ]);
        } elseif ('article' == strtolower($type)) {
            // article
            OpenGraph::setTitle($title)
                ->setDescription($description)
                ->setType($type)
                ->setArticle([
                    'published_time' => $property->created_at,
                    'modified_time' => $property->updated_at,
                    'author' => $property->author,
                    'section' => $property->category,
                    'tag' => $property->tag
                ]);
        } elseif ('download' == strtolower($type)) {
            // book
            OpenGraph::setTitle($title)
                ->setDescription($description)
                ->setType($type)
                ->setBook([
                    'author' => $property->author,
                    'isbn' => $property->isbn,
                    'release_date' => $property->release_date,
                    'tag' => $property->tag
                ]);
        } elseif ('profile' == strtolower($type)) {
            // book
            OpenGraph::setTitle($title)
                ->setDescription($description)
                ->setType($type)
                ->setBook([
                    'first_name' => $property->firstname,
                    'last_name' => $property->lastname,
                ]);
        }
    }
    public function SEOView()
    {
        echo SEO::generate(true);
    }

    /**
     * Pluralizes a word if quantity is not one.
     *
     * @param int $quantity Number of items
     * @param string $singular Singular form of word
     * @param string $plural Plural form of word; function will attempt to deduce plural form from singular if not provided
     * @return string Pluralized word if quantity is not one, otherwise singular
     */
    public function pluralize($quantity, $singular, $plural = null)
    {
        if ($quantity == 1 || !strlen($singular)) {
            return $singular;
        }
        if ($plural !== null) {
            return $plural;
        }

        $last_letter = strtolower($singular[strlen($singular) - 1]);
        switch ($last_letter) {
            case 'y':
                $second_to_last_letter = strtolower($singular[strlen($singular) - 2]);
                if ($second_to_last_letter === "a") {
                    return $singular . 's';
                } else {
                    return substr($singular, 0, -1) . 'ies';
                }
            case 's':
                return $singular . 'es';
            default:
                return $singular . 's';
        }
    }

    public function callClassMethod($class, $method, $arg = NULL)
    {
        $obj = new $class();
        return $obj->$method($arg);
    }

    public function callMethod($obj, $method, $arg = NULL)
    {
        return $obj->$method($arg);
    }

    public function toObject(array $array)
    {
        return (object) $array;
    }

    /**
     * Builds a file path with the appropriate directory separator.
     *
     * @param string $segments,... unlimited number of path segments
     * @return string Path
     */
    function file_build_path(...$segments)
    {
        return join(DIRECTORY_SEPARATOR, $segments);
    }




    /**
     * @return string
     */
    public function pageJsonData()
    {
        $jobModalOpen = false;
        if (session('job_validation_fails')) {
            $jobModalOpen = true;
        }

        $data = [
            'home_url'      => route('home'),
            'asset_url'     => asset('assets'),
            'csrf_token'    => csrf_token(),
            'jobModalOpen'  => $jobModalOpen,
            'flag_job_validation_fails' => session('flag_job_validation_fails'),
            'share_job_validation_fails' => session('share_job_validation_fails'),
            //'my_dashboard' => route('my_dashboard'),
        ];

        $routeLists = \Illuminate\Support\Facades\Route::getRoutes();

        $routes = [];
        foreach ($routeLists as $route) {
            $routes[$route->getName()] = $data['home_url'] . '/' . $route->uri;
        }
        $data['routes'] = $routes;

        return json_encode($data);
    }





    /**
     * @param string $option_key
     * @return string
     */
    public function get_option($option_key = '', $default = false)
    {
        $options = config('options');
        if (isset($options[$option_key])) {
            return $options[$option_key];
        }
        return $default;
    }


    /**
     * @param string $title
     * @param $model
     * @return string
     */

    public function unique_slug($title = '', $model = 'Job', $col = 'slug')
    {
        $slug = str_slug($title);
        if ($slug === '') {
            $string = mb_strtolower($title, "UTF-8");;
            $string = preg_replace("/[\/\.]/", " ", $string);
            $string = preg_replace("/[\s-]+/", " ", $string);
            $slug = preg_replace("/[\s_]/", '-', $string);
        }

        //get unique slug...
        $nSlug = $slug;
        $i = 0;

        $model = str_replace(' ', '', "\App\ " . $model);
        while (($model::where($col, '=', $nSlug)->count()) > 0) {
            $i++;
            $nSlug = $slug . '-' . $i;
        }
        if ($i > 0) {
            $newSlug = substr($nSlug, 0, strlen($slug)) . '-' . $i;
        } else {
            $newSlug = $slug;
        }
        return $newSlug;
    }

    public function e_form_error($field = '', $errors)
    {
        $output = $errors->has($field) ? '<span class="invalid-feedback" role="alert"><strong>' . $errors->first($field) . '</strong></span>' : '';
        return $output;
    }

    public function e_form_invalid_class($field = '', $errors)
    {
        return $errors->has($field) ? ' is-invalid' : '';
    }




    /**
     * @param int $amount
     * @return string
     */
    public function get_amount($amount = 0, $currency = null)
    {
        $currency_position = $this->get_option('currency_position');

        if (!$currency) {
            $currency = $this->get_option('currency_sign');
        }

        $currency_sign = $this->get_currency_symbol($currency);
        $get_price = $this->get_amount_raw($amount);

        if ($currency_position == 'right') {
            $show_price = $get_price . $currency_sign;
        } else {
            $show_price = $currency_sign . $get_price;
        }

        return $show_price;
    }


    public function get_amount_raw($amount = 0)
    {
        $get_price = '0.00';
        $none_decimal_currencies = $this->get_zero_decimal_currency();

        if (in_array($this->get_option('currency_sign'), $none_decimal_currencies)) {
            $get_price = (int) $amount;
        } else {
            if ($amount > 0) {
                $get_price = number_format($amount, 2);
            }
        }

        return $get_price;
    }


    public function get_zero_decimal_currency()
    {
        $zero_decimal_currency = [
            'BIF',
            'MGA',
            'CLP',
            'PYG',
            'DJF',
            'RWF',
            'GNF',
            'UGX',
            'JPY',
            'VND',
            'VUV',
            'KMF',
            'XAF',
            'KRW',
            'XOF',
            'XPF',
        ];

        return $zero_decimal_currency;
    }

    public function get_stripe_amount($amount = 0, $type = 'to_cents')
    {
        if (!$amount) {
            return $amount;
        }

        $non_decimal_currency = $this->get_zero_decimal_currency();

        if (in_array($this->get_option('currency_sign'), $non_decimal_currency)) {
            return $amount;
        }

        if ($type === 'to_cents') {
            return ($amount * 100);
        }
        return $amount / 100;
    }

    /**
     * @return array
     *
     * Get currencies
     */

    public function get_currencies()
    {
        return array(
            'USD' => 'United States dollar',
            'EUR' => 'Euro',
            'AED' => 'United Arab Emirates dirham',
            'AFN' => 'Afghan afghani',
            'ALL' => 'Albanian lek',
            'AMD' => 'Armenian dram',
            'ANG' => 'Netherlands Antillean guilder',
            'AOA' => 'Angolan kwanza',
            'ARS' => 'Argentine peso',
            'AUD' => 'Australian dollar',
            'AWG' => 'Aruban florin',
            'AZN' => 'Azerbaijani manat',
            'BAM' => 'Bosnia and Herzegovina convertible mark',
            'BBD' => 'Barbadian dollar',
            'BDT' => 'Bangladeshi taka',
            'BGN' => 'Bulgarian lev',
            'BHD' => 'Bahraini dinar',
            'BIF' => 'Burundian franc',
            'BMD' => 'Bermudian dollar',
            'BND' => 'Brunei dollar',
            'BOB' => 'Bolivian boliviano',
            'BRL' => 'Brazilian real',
            'BSD' => 'Bahamian dollar',
            'BTC' => 'Bitcoin',
            'BTN' => 'Bhutanese ngultrum',
            'BWP' => 'Botswana pula',
            'BYR' => 'Belarusian ruble',
            'BZD' => 'Belize dollar',
            'CAD' => 'Canadian dollar',
            'CDF' => 'Congolese franc',
            'CHF' => 'Swiss franc',
            'CLP' => 'Chilean peso',
            'CNY' => 'Chinese yuan',
            'COP' => 'Colombian peso',
            'CRC' => 'Costa Rican colon',
            'CUC' => 'Cuban convertible peso',
            'CUP' => 'Cuban peso',
            'CVE' => 'Cape Verdean escudo',
            'CZK' => 'Czech koruna',
            'DJF' => 'Djiboutian franc',
            'DKK' => 'Danish krone',
            'DOP' => 'Dominican peso',
            'DZD' => 'Algerian dinar',
            'EGP' => 'Egyptian pound',
            'ERN' => 'Eritrean nakfa',
            'ETB' => 'Ethiopian birr',
            'FJD' => 'Fijian dollar',
            'FKP' => 'Falkland Islands pound',
            'GBP' => 'Pound sterling',
            'GEL' => 'Georgian lari',
            'GGP' => 'Guernsey pound',
            'GHS' => 'Ghana cedi',
            'GIP' => 'Gibraltar pound',
            'GMD' => 'Gambian dalasi',
            'GNF' => 'Guinean franc',
            'GTQ' => 'Guatemalan quetzal',
            'GYD' => 'Guyanese dollar',
            'HKD' => 'Hong Kong dollar',
            'HNL' => 'Honduran lempira',
            'HRK' => 'Croatian kuna',
            'HTG' => 'Haitian gourde',
            'HUF' => 'Hungarian forint',
            'IDR' => 'Indonesian rupiah',
            'ILS' => 'Israeli new shekel',
            'IMP' => 'Manx pound',
            'INR' => 'Indian rupee',
            'IQD' => 'Iraqi dinar',
            'IRR' => 'Iranian rial',
            'ISK' => 'Icelandic krona',
            'JEP' => 'Jersey pound',
            'JMD' => 'Jamaican dollar',
            'JOD' => 'Jordanian dinar',
            'JPY' => 'Japanese yen',
            'KES' => 'Kenyan shilling',
            'KGS' => 'Kyrgyzstani som',
            'KHR' => 'Cambodian riel',
            'KMF' => 'Comorian franc',
            'KPW' => 'North Korean won',
            'KRW' => 'South Korean won',
            'KWD' => 'Kuwaiti dinar',
            'KYD' => 'Cayman Islands dollar',
            'KZT' => 'Kazakhstani tenge',
            'LAK' => 'Lao kip',
            'LBP' => 'Lebanese pound',
            'LKR' => 'Sri Lankan rupee',
            'LRD' => 'Liberian dollar',
            'LSL' => 'Lesotho loti',
            'LYD' => 'Libyan dinar',
            'MAD' => 'Moroccan dirham',
            'MDL' => 'Moldovan leu',
            'MGA' => 'Malagasy ariary',
            'MKD' => 'Macedonian denar',
            'MMK' => 'Burmese kyat',
            'MNT' => 'Mongolian t&ouml;gr&ouml;g',
            'MOP' => 'Macanese pataca',
            'MRO' => 'Mauritanian ouguiya',
            'MUR' => 'Mauritian rupee',
            'MVR' => 'Maldivian rufiyaa',
            'MWK' => 'Malawian kwacha',
            'MXN' => 'Mexican peso',
            'MYR' => 'Malaysian ringgit',
            'MZN' => 'Mozambican metical',
            'NAD' => 'Namibian dollar',
            'NGN' => 'Nigerian naira',
            'NIO' => 'Nicaraguan c&oacute;rdoba',
            'NOK' => 'Norwegian krone',
            'NPR' => 'Nepalese rupee',
            'NZD' => 'New Zealand dollar',
            'OMR' => 'Omani rial',
            'PAB' => 'Panamanian balboa',
            'PEN' => 'Peruvian nuevo sol',
            'PGK' => 'Papua New Guinean kina',
            'PHP' => 'Philippine peso',
            'PKR' => 'Pakistani rupee',
            'PLN' => 'Polish z&#x142;oty',
            'PRB' => 'Transnistrian ruble',
            'PYG' => 'Paraguayan guaran&iacute;',
            'QAR' => 'Qatari riyal',
            'RON' => 'Romanian leu',
            'RSD' => 'Serbian dinar',
            'RUB' => 'Russian ruble',
            'RWF' => 'Rwandan franc',
            'SAR' => 'Saudi riyal',
            'SBD' => 'Solomon Islands dollar',
            'SCR' => 'Seychellois rupee',
            'SDG' => 'Sudanese pound',
            'SEK' => 'Swedish krona',
            'SGD' => 'Singapore dollar',
            'SHP' => 'Saint Helena pound',
            'SLL' => 'Sierra Leonean leone',
            'SOS' => 'Somali shilling',
            'SRD' => 'Surinamese dollar',
            'SSP' => 'South Sudanese pound',
            'STD' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra',
            'SYP' => 'Syrian pound',
            'SZL' => 'Swazi lilangeni',
            'THB' => 'Thai baht',
            'TJS' => 'Tajikistani somoni',
            'TMT' => 'Turkmenistan manat',
            'TND' => 'Tunisian dinar',
            'TOP' => 'Tongan pa&#x2bb;anga',
            'TRY' => 'Turkish lira',
            'TTD' => 'Trinidad and Tobago dollar',
            'TWD' => 'New Taiwan dollar',
            'TZS' => 'Tanzanian shilling',
            'UAH' => 'Ukrainian hryvnia',
            'UGX' => 'Ugandan shilling',
            'UYU' => 'Uruguayan peso',
            'UZS' => 'Uzbekistani som',
            'VEF' => 'Venezuelan bol&iacute;var',
            'VND' => 'Vietnamese &#x111;&#x1ed3;ng',
            'VUV' => 'Vanuatu vatu',
            'WST' => 'Samoan t&#x101;l&#x101;',
            'XAF' => 'Central African CFA franc',
            'XCD' => 'East Caribbean dollar',
            'XOF' => 'West African CFA franc',
            'XPF' => 'CFP franc',
            'YER' => 'Yemeni rial',
            'ZAR' => 'South African rand',
            'ZMW' => 'Zambian kwacha',
        );
    }

    /**
     * Get Currency symbol.
     *
     * @param string $currency (default: '')
     * @return string
     */
    public function get_currency_symbol($currency = '')
    {
        if (!$currency) {
            $currency = 'USD';
        }

        $symbols = array(
            'AED' => '&#x62f;.&#x625;',
            'AFN' => '&#x60b;',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => '&fnof;',
            'AOA' => 'Kz',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&fnof;',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '&#36;',
            'BDT' => '&#2547;&nbsp;',
            'BGN' => '&#1083;&#1074;.',
            'BHD' => '.&#x62f;.&#x628;',
            'BIF' => 'Fr',
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => 'Bs.',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTC' => '&#3647;',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BZD' => '&#36;',
            'CAD' => '&#36;',
            'CDF' => 'Fr',
            'CHF' => '&#67;&#72;&#70;',
            'CLP' => '&#36;',
            'CNY' => '&yen;',
            'COP' => '&#36;',
            'CRC' => '&#x20a1;',
            'CUC' => '&#36;',
            'CUP' => '&#36;',
            'CVE' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD&#36;',
            'DZD' => '&#x62f;.&#x62c;',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '&euro;',
            'FJD' => '&#36;',
            'FKP' => '&pound;',
            'GBP' => '&pound;',
            'GEL' => '&#x10da;',
            'GGP' => '&pound;',
            'GHS' => '&#x20b5;',
            'GIP' => '&pound;',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => 'L',
            'HRK' => 'Kn',
            'HTG' => 'G',
            'HUF' => '&#70;&#116;',
            'IDR' => 'Rp',
            'ILS' => '&#8362;',
            'IMP' => '&pound;',
            'INR' => '&#8377;',
            'IQD' => '&#x639;.&#x62f;',
            'IRR' => '&#xfdfc;',
            'ISK' => 'kr.',
            'JEP' => '&pound;',
            'JMD' => '&#36;',
            'JOD' => '&#x62f;.&#x627;',
            'JPY' => '&yen;',
            'KES' => 'KSh',
            'KGS' => '&#x441;&#x43e;&#x43c;',
            'KHR' => '&#x17db;',
            'KMF' => 'Fr',
            'KPW' => '&#x20a9;',
            'KRW' => '&#8361;',
            'KWD' => '&#x62f;.&#x643;',
            'KYD' => '&#36;',
            'KZT' => 'KZT',
            'LAK' => '&#8365;',
            'LBP' => '&#x644;.&#x644;',
            'LKR' => '&#xdbb;&#xdd4;',
            'LRD' => '&#36;',
            'LSL' => 'L',
            'LYD' => '&#x644;.&#x62f;',
            'MAD' => '&#x62f;. &#x645;.',
            'MDL' => 'L',
            'MGA' => 'Ar',
            'MKD' => '&#x434;&#x435;&#x43d;',
            'MMK' => 'Ks',
            'MNT' => '&#x20ae;',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '&#x20a8;',
            'MVR' => '.&#x783;',
            'MWK' => 'MK',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => 'MT',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => 'C&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#x631;.&#x639;.',
            'PAB' => 'B/.',
            'PEN' => 'S/.',
            'PGK' => 'K',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PRB' => '&#x440;.',
            'PYG' => '&#8370;',
            'QAR' => '&#x631;.&#x642;',
            'RMB' => '&yen;',
            'RON' => 'lei',
            'RSD' => '&#x434;&#x438;&#x43d;.',
            'RUB' => '&#8381;',
            'RWF' => 'Fr',
            'SAR' => '&#x631;.&#x633;',
            'SBD' => '&#36;',
            'SCR' => '&#x20a8;',
            'SDG' => '&#x62c;.&#x633;.',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&pound;',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '&#36;',
            'SSP' => '&pound;',
            'STD' => 'Db',
            'SYP' => '&#x644;.&#x633;',
            'SZL' => 'L',
            'THB' => '&#3647;',
            'TJS' => '&#x405;&#x41c;',
            'TMT' => 'm',
            'TND' => '&#x62f;.&#x62a;',
            'TOP' => 'T&#36;',
            'TRY' => '&#8378;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => 'Sh',
            'UAH' => '&#8372;',
            'UGX' => 'UGX',
            'USD' => '&#36;',
            'UYU' => '&#36;',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VND' => '&#8363;',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'Fr',
            'XCD' => '&#36;',
            'XOF' => 'Fr',
            'XPF' => 'Fr',
            'YER' => '&#xfdfc;',
            'ZAR' => '&#82;',
            'ZMW' => 'ZK',
        );

        $currency_symbol = isset($symbols[$currency]) ? $symbols[$currency] : '';

        return $currency_symbol;
    }







    /**
     * Form Helper
     */

    /**
     * @param $checked
     * @param bool $current
     * @param bool $echo
     * @return string
     */

    public  function checked($checked, $current = true, $echo = true)
    {
        return $this->__checked_selected_helper($checked, $current, $echo, 'checked');
    }

    /**
     * @param $selected
     * @param bool $current
     * @param bool $echo
     * @return string
     */

    public function selected($selected, $current = true, $echo = true)
    {
        return $this->__checked_selected_helper($selected, $current, $echo, 'selected');
    }


    /**
     * @param $helper
     * @param $current
     * @param $echo
     * @param $type
     * @return string
     */

    public function __checked_selected_helper($helper, $current, $echo, $type)
    {
        if ((string) $helper === (string) $current)
            $result = " $type='$type'";
        else
            $result = '';

        if ($echo)
            echo $result;

        return $result;
    }


    /**
     * End Form Helper
     */


    /**
     * @param null $code
     * @return array|mixed
     *
     * Get Company Size
     */

    public function company_size($code = null)
    {
        $size = [
            'A' => __('app.1-10'),
            'B' => __('app.11-50'),
            'C'  => __('app.51-200'),
            'D'  => __('app.201-500'),
            'E'  => __('app.501-1000'),
            'F'  => __('app.1001-5000'),
            'G'  => __('app.5001-10,000'),
            'H'  => __('app.10,001+'),
        ];

        if ($code && isset($size[$code])) {
            return $size[$code];
        }
        return $size;
    }

    public function limit_words($text = null, $limit = 30)
    {
        $text = strip_tags($text);
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }


    public function get_text_tpl($text = '')
    {
        $tpl = ['[year]', '[copyright_sign]', '[site_name]'];
        $variable = [date('Y'), '&copy;', get_option('site_name')];

        $tpl_option = str_replace($tpl, $variable, $text);
        return $tpl_option;
    }
}
