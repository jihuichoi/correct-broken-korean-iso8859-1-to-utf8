<?php

/**
 * [correct_broken_korean]
 * @param  [string] $pattern             [검출용 패턴]
 * @param  [array] $iso8859_ksc5601_map [매핑 데이터]
 * @param  [string] $str                 [검출 대상 데이터]
 * @return [string] $correct_str         [수정 완료 데이터]
 */
function correct_broken_korean($pattern, $iso8859_ksc5601_map, $str)
{
	$correct_str = '';
	$tmp = ''; $cnt = 0;
	for($i=0; $i < strlen($str); $i++) {
		if(ord($str[$i]) < 127) {	//ascii 는 패스
			$correct_str .= $str[$i];
		} 
		else {
			$cnt++;
			//2바이트가 1문자, 2문자가 1개의 한글 문자를 만듬.
			if($cnt < 4) {
				$tmp .= $str[$i];
			}
			else {
			//2바이트를 묶어서 유니코드 hex 값을 알아내고
			//그 두개를 합친 뒤, 매핑 테이블에서 한글 완성형 매핑 hex 값 가져옴
			//한글 완성형 매핑 hex 값을 스트링으로 변경
				$tmp .= $str[$i];
				$tmp = dechex(_uniord($tmp[0].$tmp[1])).dechex(_uniord($tmp[2].$tmp[3]));
				$correct_str .= mb_chr2($iso8859_ksc5601_map[strtoupper($tmp)]);					
				$tmp = ''; $cnt = 0;
			}
		}
	}
	//-----------------------------------//
	echo " => 변환 완료\n";
	
	//재 확인
	if(preg_match($pattern, $correct_str, $matches)) {
		echo $correct_str."\n";
		echo "\n변환 안됨 : ";
		print_r($matches[0]);
		echo "\n";
	}
	
	return $correct_str;
}


/**
 * [mb_html_entity_decode]
 * @param  [string] $string
 * @return [string]
 */
function mb_html_entity_decode($string)
{
    {
        mb_language('Neutral');
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));

        return mb_convert_encoding($string, 'UTF-8', 'HTML-ENTITIES');
    }

    return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
}


/**
 * [mb_chr2]
 * @param  [string] $string
 * @return [string]
 */
function mb_chr2($string)
{
    return mb_html_entity_decode('&#' . intval($string) . ';');
}


/**
 * [_uniord]
 * @param  [char] $c
 * @return [char]
 */
function _uniord($c) {
    if (ord($c{0}) >=0 && ord($c{0}) <= 127)
        return ord($c{0});
    if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
        return (ord($c{0})-192)*64 + (ord($c{1})-128);
    if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
        return (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
    if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
        return (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
    if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
        return (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
    if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
        return (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
    if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
        return FALSE;
    return 0;
} 
