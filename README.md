# correct-broken-korean-iso8859-1-to-utf8
iso8859-1 로 잘못 인코딩된 한글 수정


# 사용법
$str ='ºÎ»êÀü´ÜÁö ¹èÆ÷»ç¿ø ¸ðÁý.  2¿ù6ÀÏºÎÅÍ ¤ý»ó¼¼³»¿ëÈ®ÀÎ';

$pattern = '/[^';	//패턴 시작
$pattern .= ' \'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\';	//일반 특수문자
$pattern .= 'a-zA-Z0-9';	//알파벳, 숫자
$pattern .= '·';	//희귀 특수문자
$pattern .= '\x{3008}-\x{301b}\x{2605}-\x{2606}';	//희귀 특수문자, 괄호 등
$pattern .= '\x{3131}-\x{3163}\x{ac00}-\x{d7a3}';	//한글
$pattern .= ']/u';	//패턴 종료

print_r(correct_broken_korean($pattern, $iso8859_ksc5601_map, $str));
