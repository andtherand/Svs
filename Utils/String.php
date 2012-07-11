<?php
/**
 * @author Andreas Ruck - Essen, Germany
 * @link mychiara@googlemail.com
 * @created 06.02.2010
 * @version $Id$
 * @package ARBS/SVS/UTILS
 *
 *
 * DESCRIPTION:
 *	A utils class to check strings against certain states
 */

class Svs_Utils_String
{
	// ---------------------------------------------
	//	- STATIC
	// ---------------------------------------------

	/**
	 * searchs for the last occurence of a needle and removes it
	 * @param string $needle
	 * @param string $haystack
	 * @return string
	 */
	public static function removeLastOccurenceOf($needle, $haystack) {
		return substr($haystack, 0, strrpos($haystack, $needle));
	}

	/**
	 * creates a random generated hex color string;
	 * @return string hexColor
	 */
	public static function randomColor($min = 0, $max = 255) {
		$r = mt_rand($min, $max);
		$g = mt_rand($min, $max);
		$b = mt_rand($min, $max);

		$hr = dechex($r);
		$hg = dechex($g);
		$hb = dechex($b);

		$hr = 2 === strlen($hr) ?  $hr : '0' . $hr;
		$hg = 2 === strlen($hg) ?  $hg : '0' . $hg;
		$hb = 2 === strlen($hb) ?  $hb : '0' . $hb;

		return '#' . strtoupper($hr . $hg . $hb);
	}

	/**
	 * generates an id given through the input
	 * @param string $input
	 * @param [string $prefix] optional
	 * @param [string $suffix] optional
	 * @return string
	 */
	public static function generateID($input, $prefix = '', $suffix = '')
	{
		$id = sprintf('%s%s%s', $prefix, md5($input), $suffix);
		return $id;
	}


	public static function generateCacheId($str1, $str2)
    {
        $prefix = sprintf('%s_%s', $str1, $str2);
        return Svs_Utils_String::generateID($prefix, $prefix . '_');
    }

	/**
	 * replaces unwanted chars with ascii chars - replaces special chars with dashes '-'
	 * @param string $str
	 * @return string The sanitized string
	 */
	public static function sanitizeInput($str) {
		$str = strtolower(trim($str));

		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
    	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

		$str = str_replace($a, $b, $str);

		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+', ',', '_');
		$str  = str_replace ($find, '-', $str);

		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$str  = preg_replace($find, $repl, $str);

		$len = strlen($str);
		if(strrpos($str, '-') === $len) {
			$str = substring();
		}
		return $str;
	}

	/**
	 * adds the base url to a resource if it´s necessary otherwise returns
	 * the given resource path
	 *
	 * @param 	string $url the resource url
	 * @param 	string $baseUrl the baseUrl to add
	 * @return 	string
	 */
	public static function addBaseUrl($url, $baseUrl)
	{
		$return = $url;
		if (preg_match('/http*/', $url, $hits)) {
			return str_replace(array('https:', 'http:'), array('', ''), $return);
		}

		if ('development' === APPLICATION_ENV) {
			$baseUrl = $_SERVER['SERVER_NAME'] . '/';
			$return = $baseUrl . $url;
			$return = str_replace('//', '/', $return);
			$return = '//' . $return;

		} else {
			$return = '//' . $_SERVER['SERVER_NAME'] . $baseUrl . $url;
		}

		return $return;
	}
}