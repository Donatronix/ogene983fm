<?php

namespace App\Helpers\Stringizer;

use App\Helpers\Stringizer\Transformers\Concat;
use App\Helpers\Stringizer\Transformers\Lowercase;
use App\Helpers\Stringizer\Transformers\Uppercase;
use App\Helpers\Stringizer\Transformers\UppercaseFirst;
use App\Helpers\Stringizer\Transformers\UppercaseWords;
use App\Helpers\Stringizer\Transformers\LowercaseFirst;
use App\Helpers\Stringizer\Transformers\Trim;
use App\Helpers\Stringizer\Transformers\TrimLeft;
use App\Helpers\Stringizer\Transformers\TrimRight;
use App\Helpers\Stringizer\Transformers\Length;
use App\Helpers\Stringizer\Transformers\Width;
use App\Helpers\Stringizer\Transformers\SubString;
use App\Helpers\Stringizer\Transformers\Reverse;
use App\Helpers\Stringizer\Transformers\StartsWith;
use App\Helpers\Stringizer\Transformers\EndsWith;
use App\Helpers\Stringizer\Transformers\HashCode;
use App\Helpers\Stringizer\Transformers\Truncate;
use App\Helpers\Stringizer\Transformers\TruncateMatch;
use App\Helpers\Stringizer\Transformers\IndexOf;
use App\Helpers\Stringizer\Transformers\LastIndexOf;
use App\Helpers\Stringizer\Transformers\Split;
use App\Helpers\Stringizer\Transformers\Replace;
use App\Helpers\Stringizer\Transformers\Pad;
use App\Helpers\Stringizer\Transformers\RemoveNonAscii;
use App\Helpers\Stringizer\Transformers\ReplaceAccents;
use App\Helpers\Stringizer\Transformers\Camelize;
use App\Helpers\Stringizer\Transformers\RemoveWhitespace;
use App\Helpers\Stringizer\Transformers\Contains;
use App\Helpers\Stringizer\Transformers\SubStringCount;
use App\Helpers\Stringizer\Transformers\Dasherize;
use App\Helpers\Stringizer\Transformers\StripTags;
use App\Helpers\Stringizer\Transformers\EnsureLeft;
use App\Helpers\Stringizer\Transformers\EnsureRight;
use App\Helpers\Stringizer\Transformers\EmptyCheck;
use App\Helpers\Stringizer\Transformers\StripPunctuation;
use App\Helpers\Stringizer\Transformers\Alpha;
use App\Helpers\Stringizer\Transformers\AlphaNumeric;
use App\Helpers\Stringizer\Transformers\AlphaNumericSpace;
use App\Helpers\Stringizer\Transformers\AlphaNumericSpaceDash;
use App\Helpers\Stringizer\Transformers\Email;
use App\Helpers\Stringizer\Transformers\Number;
use App\Helpers\Stringizer\Transformers\Date;
use App\Helpers\Stringizer\Transformers\Decimal;
use App\Helpers\Stringizer\Transformers\Ipv4;
use App\Helpers\Stringizer\Transformers\Ipv6;
use App\Helpers\Stringizer\Transformers\HexDecimal;
use App\Helpers\Stringizer\Transformers\LowercaseCheck;
use App\Helpers\Stringizer\Transformers\UppercaseCheck;
use App\Helpers\Stringizer\Transformers\Random;
use App\Helpers\Stringizer\Transformers\WordCount;
use App\Helpers\Stringizer\Transformers\SentenceCount;
use App\Helpers\Stringizer\Transformers\Url;
use App\Helpers\Stringizer\Transformers\Chars;
use App\Helpers\Stringizer\Transformers\CollapseWhitespace;
use App\Helpers\Stringizer\Transformers\Base64Check;
use App\Helpers\Stringizer\Transformers\Base64;
use App\Helpers\Stringizer\Transformers\Between;
use App\Helpers\Stringizer\Transformers\CamelToSnake;
use App\Helpers\Stringizer\Transformers\ChopLeft;
use App\Helpers\Stringizer\Transformers\ChopRight;
use App\Helpers\Stringizer\Transformers\BooleanConverter;
use App\Helpers\Stringizer\Transformers\Repeat;
use App\Helpers\Stringizer\Transformers\SwapCase;
use App\Helpers\Stringizer\Transformers\Join;
use App\Helpers\Stringizer\Transformers\HexColor;
use App\Helpers\Stringizer\Transformers\RgbColor;
use App\Helpers\Stringizer\Transformers\MultiByte;
use App\Helpers\Stringizer\Transformers\Semver;
use App\Helpers\Stringizer\Transformers\Ascii;
use App\Helpers\Stringizer\Transformers\Latitude;
use App\Helpers\Stringizer\Transformers\Longitude;
use App\Helpers\Stringizer\Transformers\Json;
use App\Helpers\Stringizer\Transformers\Isbn;
use App\Helpers\Stringizer\Transformers\Hash;


/**
 * Stringizer
 *
 * @link https://github.com/jasonlam604/Stringizer
 * @copyright Copyright (c) 2016 Jason Lam
 * @license https://github.com/jasonlam604/Stringizer/blob/master/LICENSE (MIT License)
 */
class Stringizer
{

    /**
     * String that is manipulated / transformed
     *
     * @var string
     */
    private $value;

    /**
     * Hold the original given string value
     *
     * @var string
     */
    private $valueOriginal;

    /**
     * Hold encoding
     *
     * @var string
     */
    private $encoding;

    /**
     * Constructor
     *
     * @param string $stringValue
     * @param string $stringEncoding
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($stringValue, $encoding = null)
    {
        $this->setString($stringValue);

        if (empty($encoding))
            $encoding = \mb_internal_encoding();

        $this->setEncoding($encoding);
    }

    public function base64Encode()
    {
        $this->value = (new Base64($this->value))->execute();
        return $this;
    }

    public function base64Decode()
    {
        $this->value = (new Base64($this->value, true))->execute();
        return $this;
    }

    public function camelize()
    {
        $this->value = (new Camelize($this->value))->execute();
        return $this;
    }

    public function camelToSnake()
    {
        $this->value = (new CamelToSnake($this->value))->execute();
        return $this;
    }

    public function charAt($index)
    {
        $this->value = (new SubString($this->value, $index, 1))->execute();
        return $this;
    }

    public function chars()
    {
        return $this->value = (new Chars($this->value))->execute();
    }

    /**
     * @deprecated Since 2.8.0 named incorrectly see chopLeft
     */
    public function chompLeft($prefix)
    {
        $this->value = (new ChopLeft($this->value, $prefix))->execute();
        return $this;
    }

    /**
     * @deprecated Since 2.8.0 named incorrectly see chopRight
     */
    public function chompRight($prefix)
    {
        $this->value = (new ChopRight($this->value, $prefix))->execute();
        return $this;
    }

    public function chopLeft($prefix)
    {
        $this->value = (new ChopLeft($this->value, $prefix))->execute();
        return $this;
    }

    public function chopRight($prefix)
    {
        $this->value = (new ChopRight($this->value, $prefix))->execute();
        return $this;
    }

    public function collapseWhitespace()
    {
        $this->value = (new CollapseWhitespace($this->value))->execute();
        return $this;
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
    public function concat($value, $preAppend = false)
    {
        $transformer = new Concat($this->value, $value);
        $transformer->setPreAppend($preAppend);
        $this->value = $transformer->execute();
        return $this;
    }

    public function between($left, $right)
    {
        $this->value = (new Between($this->value, $left, $right))->execute();
        return $this;
    }

    public function contains($needle)
    {
        return (new Contains($this->value, $needle))->execute();
    }

    public function containsIncaseSensitive($needle)
    {
        return (new Contains($this->value, $needle))->enableCaseInsensitive()->execute();
    }

    public function containsCount($needle)
    {
        return (new SubStringCount($this->value, $needle))->execute();
    }

    public function containsCountIncaseSensitive($needle)
    {
        return (new SubStringCount($this->value, $needle))->enableCaseInsensitive()->execute();
    }

    public function dasherize()
    {
        $this->value = (new Dasherize($this->value))->execute();
        return $this;
    }

    public function endsWith($needle)
    {
        return (new EndsWith($this->value, $needle))->execute();
    }

    public function ensureLeft($prefix)
    {
        $this->value = (new EnsureLeft($this->value, $prefix))->execute();
        return $this;
    }

    public function ensureRight($suffix)
    {
        $this->value = (new EnsureRight($this->value, $suffix))->execute();
        return $this;
    }

    public function first($numberOfCharacters)
    {
        $this->value = (new SubString($this->value, 0, $numberOfCharacters))->execute();
        return $this;
    }

    public function hashCode()
    {
        $this->value = (string) (new HashCode($this->value))->execute();
        return $this;
    }

    public function hasLowercase()
    {
        return (new LowercaseCheck($this->value))->execute();
    }

    public function hasUppercase()
    {
        return (new UppercaseCheck($this->value))->execute();
    }

    public function indexOf($needle, $offset = 0)
    {
        return (new IndexOf($this->value, $needle, $offset))->execute();
    }

    public function indexOfCaseInsensitive($needle, $offset = 0)
    {
        return (new IndexOf($this->value, $needle, $offset))->enableCaseInsensitive()->execute();
    }

    public function isAlpha()
    {
        return (new Alpha($this->value))->execute();
    }

    public function isAlphaNumeric()
    {
        return (new AlphaNumeric($this->value))->execute();
    }

    public function isAlphaNumericSpace()
    {
        return (new AlphaNumericSpace($this->value))->execute();
    }

    public function isAlphaNumericSpaceDash()
    {
        return (new AlphaNumericSpaceDash($this->value))->execute();
    }

    public function isAscii($isPrintableOnly = false)
    {
        return (new Ascii($this->value, $isPrintableOnly))->execute();
    }

    public function isBase64()
    {
        return (new Base64Check($this->value))->execute();
    }

    /**
     * Alias for isEmpty
     */
    public function isBlank()
    {
        return (new EmptyCheck($this->value))->execute();
    }

    public function isDate()
    {
        return (new Date($this->value))->execute();
    }

    public function isDecimal()
    {
        return (new Decimal($this->value))->execute();
    }

    public function isEmail()
    {
        return (new Email($this->value))->execute();
    }

    public function isEmpty()
    {
        return (new EmptyCheck($this->value))->execute();
    }

    public function isHash($hashAlgorithm)
    {
        return (new Hash($this->value, $hashAlgorithm))->execute();
    }

    public function isHexColor()
    {
        return (new HexColor($this->value))->execute();
    }

    public function isHexDecimal()
    {
        return (new HexDecimal($this->value))->execute();
    }

    public function isIsbn10()
    {
        return (new Isbn($this->value))->execute();
    }

    public function isIsbn13()
    {
        $isbn = new Isbn($this->value);
        $isbn->checkIsbn13();
        return $isbn->execute();
    }

    public function isIpv4()
    {
        return (new Ipv4($this->value))->execute();
    }

    public function isIpv6()
    {
        return (new Ipv6($this->value))->execute();
    }

    public function isJson()
    {
        return (new Json($this->value))->execute();
    }

    public function isLatitude()
    {
        return (new Latitude($this->value))->execute();
    }

    public function isLongitude()
    {
        return (new Longitude($this->value))->execute();
    }

    public function isMultiByte()
    {
        return (new MultiByte($this->value))->execute();
    }

    public function isNumber()
    {
        return (new Number($this->value))->execute();
    }

    public function isRgbColor()
    {
        return (new RgbColor($this->value))->execute();
    }

    public function isSemver()
    {
        return (new Semver($this->value))->execute();
    }

    public function isUrl($santize = false)
    {
        return (new Url($this->value, $santize))->execute();
    }

    public function join($values, $separator = ",")
    {
        $this->value = (new Join($values, $separator))->execute();
        return $this;
    }

    public function last($numberOfCharacters)
    {
        $this->value = (new SubString($this->value, ($this->length() - $numberOfCharacters)))->execute();
        return $this;
    }

    public function lastIndexOf($needle, $offset = 0)
    {
        return (new LastIndexOf($this->value, $needle, $offset))->execute();
    }

    public function lastIndexOfCaseInsensitive($needle, $offset = 0)
    {
        return (new LastIndexOf($this->value, $needle, $offset, true))->enableCaseInsensitive()->execute();
    }

    public function lineCount()
    {
        return (new SubStringCount($this->value, "\n"))->execute();
    }

    /**
     * Length
     *
     * @return int length of string
     */
    public function length()
    {
        return (new Length($this->value))->execute();
    }

    /**
     * Convert entire string to lowercase
     *
     * @return \Stringizer\Stringizer
     */
    public function lowercase()
    {
        $this->value = (new Lowercase($this->value))->execute();
        return $this;
    }

    public function lowercaseFirst($ignoreUppercaseFirst = false)
    {
        if (!$ignoreUppercaseFirst) {
            $this->value = (new Uppercase($this->value))->execute();
        }
        $this->value = (new LowercaseFirst($this->value))->execute();
        return $this;
    }

    public function padBoth($padValue, $padAmount)
    {
        $this->value = (new Pad($this->value, $padValue, $padAmount, STR_PAD_BOTH))->execute();
        return $this;
    }

    public function padLeft($padValue, $padAmount)
    {
        $this->value = (new Pad($this->value, $padValue, $padAmount, STR_PAD_LEFT))->execute();
        return $this;
    }

    public function padRight($padValue, $padAmount)
    {
        $this->value = (new Pad($this->value, $padValue, $padAmount, STR_PAD_RIGHT))->execute();
        return $this;
    }

    public function randomAlpha($length = 10)
    {
        $this->value = (new Random(Random::$RANDOM_ALPHA, $length))->execute();
        return $this;
    }

    public function randomNumeric($length = 10)
    {
        $this->value = (new Random(Random::$RANDOM_NUMERIC, $length))->execute();
        return $this;
    }

    public function randomAlphanumeric($length = 10)
    {
        $this->value = (new Random(Random::$RANDOM_ALPHA_NUMERIC, $length))->execute();
        return $this;
    }

    public function repeat($repeatNumber)
    {
        $this->value = (new Repeat($this->value, $repeatNumber))->execute();
        return $this;
    }

    public function replace($needles, $replacements)
    {
        $this->value = (new Replace($this->value, $needles, $replacements))->execute();
        return $this;
    }

    public function replaceIncaseSensitive($needles, $replacements)
    {
        $this->value = (new Replace($this->value, $needles, $replacements))->enableCaseInsensitive()->execute();
        return $this;
    }

    public function replaceAccents()
    {
        $this->value = (new ReplaceAccents($this->value))->execute();
        return $this;
    }

    public function removeNonAscii()
    {
        $this->value = (new RemoveNonAscii($this->value))->execute();
        return $this;
    }

    public function removeWhitespace()
    {
        $this->value = (new RemoveWhitespace($this->value))->execute();
        return $this;
    }

    public function reverse()
    {
        $this->value = (new Reverse($this->value))->execute();
        return $this;
    }

    public function sentenceCount()
    {
        return (new SentenceCount($this->value))->execute();
    }

    public function startsWith($needle)
    {
        return (new StartsWith($this->value, $needle))->execute();
    }

    public function stripPunctuation()
    {
        $this->value = (new StripPunctuation($this->value))->execute();
        return $this;
    }

    public function stripTags($allowableTags = '')
    {
        $this->value = (new StripTags($this->value, $allowableTags))->execute();
        return $this;
    }

    public function split($delimiter = ",")
    {
        return (new Split($this->value, $delimiter))->execute();
    }

    public function subString($start, $length = null)
    {
        $this->value = (new SubString($this->value, $start, $length))->execute();
        return $this;
    }

    public function swapCase()
    {
        $this->value = (new SwapCase($this->value))->execute();
        return $this;
    }

    public function toBoolean()
    {
        return (new BooleanConverter($this->value))->execute();
    }

    public function trim()
    {
        $this->value = (new Trim($this->value))->execute();
        return $this;
    }

    public function trimRight()
    {
        $this->value = (new TrimRight($this->value))->execute();
        return $this;
    }

    public function trimLeft()
    {
        $this->value = (new TrimLeft($this->value))->execute();
        return $this;
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
    public function truncate($numberToTruncate)
    {
        $this->value = (new Truncate($this->value, $numberToTruncate))->execute();
        return $this;
    }

    public function truncateMatch($stringToMatch, $truncateBefore = false)
    {
        $result = (new TruncateMatch($this->value, $stringToMatch, !$truncateBefore))->execute();
        if ($result === FALSE) {
            return $result;
        } else {
            $this->value = $result;
            return $this;
        }
    }

    public function truncateMatchCaseInsensitive($stringToMatch, $truncateBefore = false)
    {
        $result = (new TruncateMatch($this->value, $stringToMatch, !$truncateBefore))->enableCaseInsensitive()->execute();
        if ($result === FALSE) {
            return $result;
        } else {
            $this->value = $result;
            return $this;
        }
    }

    public function uppercase()
    {
        $this->value = (new Uppercase($this->value))->execute();
        return $this;
    }

    public function uppercaseWords()
    {
        $this->value = (new UppercaseWords((new Lowercase($this->value))->execute()))->execute();
        return $this;
    }

    public function uppercaseFirst($ignoreLowercaseFirst = false)
    {
        if (!$ignoreLowercaseFirst) {
            $this->value = (new Lowercase($this->value))->execute();
        }
        $this->value = (new UppercaseFirst($this->value))->execute();
        return $this;
    }

    public function width()
    {
        return (new Width($this->value))->execute();
    }

    public function wordCount()
    {
        return (new WordCount($this->value))->execute();
    }

    public function setEncoding($encoding)
    {
        if (!isset($encoding))
            throw new \Exception("Given encoding value not valid");

        $this->encoding = $encoding;

        mb_internal_encoding($this->encoding);
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function getStringOriginal()
    {
        return $this->valueOriginal;
    }

    public function setString($stringValue)
    {

        if (!isset($stringValue)) {
            throw new \InvalidArgumentException("Given value is null not a string");
        } elseif (is_array($stringValue)) {
            throw new \InvalidArgumentException("Given value is an array not a string");
        } elseif (is_object($stringValue) && !method_exists($stringValue, "__toString")) {
            throw new \InvalidArgumentException("Given object does not have a __toString method");
        }

        $this->value = (string) $stringValue;

        $this->valueOriginal = $this->value;
    }

    public function getString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->value;
    }
}
