<?php

// dump and die! :D funkcia na debugovanie premenných
function dd($var){
    var_dump($var); exit;
}

// vráti základnú url projektu
function url(){
    return BASE_URL;
}

// vráti názov obrázku
function image_name($post_id){
    return IMAGE_DIRECTORY . "/" . $post_id . ".jpg";
}

// vráti url k obrázku
function image_url($post_id){
    return url() . "/" . image_name($post_id);
}

// vráti true ak sa jedná o post request, inak false
function is_post(){
    return ($_SERVER["REQUEST_METHOD"] == "POST");
}

// vráti segmenty v url pre segment() funkciu
function get_segments(){
    // najprv vyskladáme aktuálnu url adresu
    $current_url  = "http";
    $current_url .= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s://" : "://");
    $current_url .= $_SERVER['HTTP_HOST'];
    $current_url .= $_SERVER['REQUEST_URI'];

    // odstránime z nej nepotrebnú časť url nastavenú v config súbore ako BASE_URL
    $path = str_replace(BASE_URL, "", $current_url);

    // naparsujeme cestu ktorá nam ostala, a odstránime z nej nepotrebné lomítka na začiatku a na konci
    $path = trim(parse_url($path, PHP_URL_PATH), '/');

    // rozbijeme cestu na segmenty podľa lomítok
    $segments = explode('/', $path);

    // vrátime segmenty
    return $segments;
}

// funkcia vracia segment podľa indexu, alebo false ak segment s takým indexom neexistuje
function segment($index){
    // získame segmenty
    $segments = get_segments();

    // ak segment s daným indexom existuje, vrátime ho, inak vrátime false
    return isset($segments[$index-1]) ? $segments[$index-1] : false;
}

// funkcia na presmerovanie na inú stránku
function redirect($page = "/", $status_code = 302){
    $page = str_replace(BASE_URL, '', $page);
    $page = ltrim($page, '/');

    $location = BASE_URL . "/" . $page;

    header("Location: " . $location, true, $status_code);
    exit;
}

// zobrazí 404 stránku
function show_404(){
    header("HTTP/1.0 404 Not Found");
    include_once "_pages/404.php";
    exit;
}

// pridá do kódu vrchnú časť stránky s head časťou, headerom a navigáciou
function include_header($vars = array()){
    extract($vars);
    include_once "_layout/header.php";
}

// pridá do kódu spodnú časť stránky s footerom
function include_footer($vars = array()){
    extract($vars);
    include_once "_layout/footer.php";
}

// vráti text ošetrený od XSS útoku
function plain($str){
    return htmlspecialchars($str, ENT_NOQUOTES);
}

// funkcia ktorá dokáže limitovať počet slov
function word_limiter($str, $limit = 100, $end_char = '&#8230;'){
    if (trim($str) === '') {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

    if (strlen($str) === strlen($matches[0])) {
        $end_char = '';
    }

    return rtrim($matches[0]) . $end_char;
}

// funkcia ktorá naformátuje text blogu aby mal html elementy
function add_paragraphs($str)
{
    if (($str = trim($str)) === '') return '';

    $str = str_replace(array("\r\n", "\r"), "\n", $str);

    $str = preg_replace('~^[ \t]+~m', '', $str);
    $str = preg_replace('~[ \t]+$~m', '', $str);

    if ($html_found = (strpos($str, '<') !== FALSE)) {
        $no_p = '(?:p|div|article|header|aside|hgroup|canvas|output|progress|section|figcaption|audio|video|nav|figure|footer|video|details|main|menu|summary|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';

        $str = preg_replace('~^<' . $no_p . '[^>]*+>~im', "\n$0", $str);
        $str = preg_replace('~</' . $no_p . '\s*+>$~im', "$0\n", $str);
    }

    $str = '<p>' . trim($str) . '</p>';
    $str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);

    if ($html_found !== FALSE) {
        $str = preg_replace('~<p>(?=</?' . $no_p . '[^>]*+>)~i', '', $str);
        $str = preg_replace('~(</?' . $no_p . '[^>]*+>)</p>~i', '$1', $str);
    }

    $str = preg_replace('~(?<!\n)\n(?!\n)~', "<br>\n", $str);

    return $str;
}
