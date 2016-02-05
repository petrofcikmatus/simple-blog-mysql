<?php

// vráti či môže užívateľ editovať daný príspevok
function is_owner(array $post){
    // ak užívateľ nie je prihlásený, tak nemôže
    if (!is_logged_in()) return false;

    // ak užívateľ je adminom, tak môže
    if (is_admin()) return true;

    // ak sme nepriložili žiaden článok
    if (!$post) return false;

    $post_user_id = $post["user_id"];

    $curent_user_id = get_user_id();

    if ($post_user_id == $curent_user_id){
        return true;
    }
    return false;
}

// vráti všetky články v databáze
function get_posts($format = true){
    global $db;

    try {
        $query = $db->query("
            SELECT p.id, p.user_id, p.title, p.text, p.created_at,
                   u.email AS user_email,
                   u.name AS user_name,
                   array_to_string(array_agg(t.tag), '|') AS tags
            FROM posts p
            LEFT JOIN posts_tags pt ON (p.id = pt.post_id)
            LEFT JOIN tags t ON (t.id = pt.tag_id)
            LEFT JOIN users u ON (u.id = p.user_id)
            GROUP BY p.id, p.user_id, p.title, p.text, p.created_at, u.email, u.name
            ORDER BY p.created_at DESC
        ");
    } catch (PDOException $e){
        return array();
    }

    if ($query->rowCount()){
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($format){
            $posts = array_map('format_post', $posts);
        }

        return $posts;
    }
    return array();
}

// vráti všetky články ktoré majú daný tag
function get_posts_by_tag($tag, $format = true){
    // ak nemáme žiaden tag, prázdny string
    if (!$tag){
        return array();
    }

    $tag = urldecode($tag);

    global $db;

    try {
        $query = $db->prepare("
            SELECT p.id, p.user_id, p.title, p.text, p.created_at,
                   u.email AS user_email,
                   u.name AS user_name,
                   array_to_string(array_agg(t.tag), '|') AS tags
            FROM posts p
            LEFT JOIN posts_tags pt ON (p.id = pt.post_id)
            LEFT JOIN tags t ON (t.id = pt.tag_id)
            LEFT JOIN users u ON (u.id = p.user_id)
            WHERE t.tag = :tag
            GROUP BY p.id, p.user_id, p.title, p.text, p.created_at, u.email, u.name
            ORDER BY p.created_at DESC
        ");
        $query->execute(array('tag' => $tag));
    } catch (PDOException $e){
        return array();
    }

    if ($query->rowCount()){
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($format){
            $posts = array_map('format_post', $posts);
        }

        return $posts;
    }
    return array();
}

// vráti všetky články od užívateľa podľa jeho id
function get_posts_by_user($user_id, $format = true){
    //
    if (!$user_id || !filter_var($user_id, FILTER_VALIDATE_INT)){
        return array();
    }

    global $db;

    try {
        $query = $db->prepare("
            SELECT p.id, p.user_id, p.title, p.text, p.created_at,
                   u.email AS user_email,
                   u.name AS user_name,
                   array_to_string(array_agg(t.tag), '|') AS tags
            FROM posts p
            LEFT JOIN posts_tags pt ON (p.id = pt.post_id)
            LEFT JOIN tags t ON (t.id = pt.tag_id)
            LEFT JOIN users u ON (u.id = p.user_id)
            WHERE p.user_id = :user_id
            GROUP BY p.id, p.user_id, p.title, p.text, p.created_at, u.email, u.name
            ORDER BY p.created_at DESC
        ");
        $query->execute(array('user_id' => $user_id));
    } catch (PDOException $e){
        return array();
    }

    if ($query->rowCount()){
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($format){
            $posts = array_map('format_post', $posts);
        }

        return $posts;
    }
    return array();
}

// vráti všetky články v databáze
function get_posts_like($string, $format = true){

    $string = mb_strtolower('%' . $string . '%');

    global $db;

    try {
        $query = $db->prepare("
            SELECT p.id, p.user_id, p.title, p.text, p.created_at,
                   u.email AS user_email,
                   u.name AS user_name,
                   p.text || p.title AS tt,
                   array_to_string(array_agg(t.tag), '|') AS tags
            FROM posts p
            LEFT JOIN posts_tags pt ON (p.id = pt.post_id)
            LEFT JOIN tags t ON (t.id = pt.tag_id)
            LEFT JOIN users u ON (u.id = p.user_id)
            WHERE lower(p.title) LIKE :string OR lower(p.text) LIKE :string OR lower(u.name) LIKE :string
            GROUP BY p.id, p.user_id, p.title, p.text, p.created_at, u.email, u.name
            ORDER BY p.created_at DESC
        ");
        $query->execute(array("string" => $string));
    } catch (PDOException $e){
        return array();
    }

    if ($query->rowCount()){
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($format){
            $posts = array_map('format_post', $posts);
        }

        return $posts;
    }
    return array();
}

// vráti jeden článok podľa id
function get_post($id, $format = true){
    // we have no id
    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
        return array();
    }

    global $db;

    try {

        $query = $db->prepare("
            SELECT p.id, p.user_id, p.title, p.text, p.created_at, p.has_image,
                   u.email AS user_email,
                   u.name AS user_name,
                   array_to_string(array_agg(t.tag), '|') AS tags
            FROM posts p
            LEFT JOIN posts_tags pt ON (p.id = pt.post_id)
            LEFT JOIN tags t ON (t.id = pt.tag_id)
            LEFT JOIN users u ON (u.id = p.user_id)
            WHERE p.id = :id
            GROUP BY p.id, p.user_id, p.title, p.text, p.created_at, p.has_image, u.email, u.name
            ORDER BY p.created_at DESC
        ");
        $query->execute(array('id' => $id));
    } catch (PDOException $e){
        return array();
    }

    if ($query->rowCount() === 1 ){
        $post = $query->fetch(PDO::FETCH_ASSOC);

        if ($format){
            $post = format_post($post);
        }

        return $post;
    }

    return array();
}

// pridá nový článok
function add_post(){
    global $db;

    // validácia post poľa pre pridanie
    if (!$data = validate_post()) {
        return false;
    }

    try {
        $query = $db->prepare("INSERT INTO posts (user_id, title, text) VALUES (:user_id, :title, :text)");
        $insert = $query->execute(array(
            'user_id' => get_user_id(),
            'title'   => $data["title"],
            'text'    => $data["text"]
        ));
    } catch (PDOException $e){
        return false;
    }


    if (!$insert){
        add_message("Niečo sa pokazilo.");
        return false;
    }

    // toto je trochu hlúposť, ale postgresql vyžaduje zadanie sekvencie kde sa uchováva posledné id tabulky
    $post_id = $db->lastInsertId("posts_id_seq");

    add_tags_to_post($post_id, $data["tags"]);

    add_message("Článok bol úspešne pridaný.");

    // vrátime posledné pridané ID článku, aby sme mohli presmerovať na nový článok
    return $post_id;
}

// upraví existujúci článok
function edit_post($post_id){

    // validácia post poľa pre pridanie
    if (!$data = validate_post($post_id)) {
        return false;
    }

    global $db;

    try {
        $query = $db->prepare("UPDATE posts SET title = :title, text = :text WHERE id = :id");
        $query->execute(array(
            "id"    => $post_id,
            "title" => $data["title"],
            "text"  => $data["text"],
        ));
    } catch (PDOException $e){
        return false;
    }

    delete_tags_from_post($post_id);

    add_tags_to_post($post_id, $data["tags"]);

    add_message("Článok bol aktualizovaný.");

    return true;
}

function delete_post($post_id){
    delete_tags_from_post($post_id);

    deleteImage($post_id);

    global $db;

    // vymažeme článok z tabuľky článkov
    $query = $db->prepare("DELETE FROM posts WHERE id = :id");
    $delete = $query->execute(array("id" => $post_id));

    if (!$delete){
        add_message("Nepodarilo sa vymazať článok.");
        return false;
    }

    add_message("Článok bol vymazaný.");

    return true;
}

function format_post(array $post){

    // odstráni všetky biele znaky
    $post = array_map("trim", $post);

    // escapuje názov a obsah článku pre výpis, ochrana proti XSS útoku
    $post["title"] = plain($post["title"]);
    $post["text"]  = plain($post["text"]);

    // vytvorí užitočné linky
    $post["link"] = filter_var(url() . "/clanok/" . $post["id"], FILTER_SANITIZE_URL);
    $post["link_edit"] = filter_var(url() . "/upravit-clanok/" . $post["id"], FILTER_SANITIZE_URL);
    $post["link_delete"] = filter_var(url() . "/vymazat-clanok/" . $post["id"], FILTER_SANITIZE_URL);

    // vytvorí linky na užívateľa
    $post["user_email"] = filter_var($post["user_email"], FILTER_SANITIZE_EMAIL);
    $post["user_link"] = filter_var(url() . "/autor/" . $post["user_id"], FILTER_SANITIZE_URL);

    // vytvorí náhľad článku pre úvodnú stránku so 40 slovami
    $post["teaser"] = word_limiter($post["text"], 40);

    // upraví text pre zobranenie článku
    $post['text'] = add_paragraphs($post['text']);

    // spracujeme dátum vytvorenia článku do krajšieho tvaru
    $post["created_at"] = str_replace(' ', '&nbsp;', date('j.n.Y \o G:i', strtotime($post['created_at'])));

    // získame tagy
    $tags = $post['tags'] ? explode('|', $post['tags']) : array();
    $post["tags"] = array();
    foreach($tags as $tag){
        $post["tags"][$tag] = filter_var(url() . "/tag/" . urlencode($tag), FILTER_SANITIZE_URL);
    }

    return $post;
}

function validate_post($post_id = 0){

    // získame dáta z poslaného $_POSTu
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $text  = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $tags  = filter_input(INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

    // ak nebolo zadané ID článku a nie je to integer
    if (!$post_id || !filter_var($post_id, FILTER_VALIDATE_INT)){
        $post_id = false;
    }

    // title is required
    if (!$title = trim($title)){
        add_message("Zabudli ste zadať názov článku.");
    }

    // text is required
    if (!$text = trim($text)){
        add_message("Zabudli ste zadať text článku.");
    }

    // if we have error messages, validation didn't go well
    if (has_messages()){
        return false;
    }

    return array(
        "id"    => $post_id,
        "title" => $title,
        "text"  => $text,
        "tags"  => $tags
    );
}

// pridá článku tagy - vazobná tabuľka
function add_tags_to_post($post_id, $tags = array()){
    global $db;

    if (!empty($tags) && $tags = array_filter($tags)){
        foreach ($tags as $tag_id){
            $insert_tags = $db->prepare("INSERT INTO posts_tags VALUES (:post_id, :tag_id)");
            $insert_tags->execute(array("post_id" => $post_id, "tag_id"  => $tag_id));
        }
    }
}

// vymaže priradené tagy k článku - vazobná tabuľka
function delete_tags_from_post($post_id){
    global $db;
    $query = $db->prepare("DELETE FROM posts_tags WHERE post_id = :post_id");
    $query->execute(array("post_id" => $post_id));
}

// vráti tagy
function get_tags($post_id = 0){
    global $db;

    $query = $db->query("SELECT * FROM tags ORDER BY tag ASC");

    if ($query->rowCount()){
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $results = array();
    }

    if ($post_id){

        $query = $db->prepare("SELECT t.id FROM tags t JOIN posts_tags pt ON t.id = pt.tag_id WHERE pt.post_id = :post_id");

        $query->execute(array("post_id" => $post_id));

        if ($query->rowCount()){
            $tags_for_post = $query->fetchAll(PDO::FETCH_COLUMN);
            foreach ($results as $key => $tag) {
                if (in_array($tag["id"], $tags_for_post)){
                    $results[$key]["checked"] = true;
                }
            }
        }
    }

    return $results;
}

function addImage($post_id){

    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE){
        return false;
    }

    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK){
        add_message("Počas nahrávania obrázku nastala chyba.");
        return false;
    }

    $image_info = getimagesize($_FILES["image"]["tmp_name"]);

    if ("image/jpeg" != $image_info["mime"]){
        add_message("Povolené sú len jpg obrázky.");
        return false;
    }

    // vymažeme prípadný predchádzajúci obrázok
    deleteImageFile($post_id);

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], image_name($post_id))){
        add_message("Nepodarilo sa uložiť nový obrázok.");
        return false;
    }

    try {
        global $db;
        $query = $db->prepare("UPDATE posts SET has_image = TRUE WHERE id = :id");
        $query->execute(array("id" => $post_id));
    } catch (Exception $e){
        deleteImageFile($post_id);
        add_message("Niečo sa nepodarilo počas aktualizácie databázy.");
        return false;
    }

    return true;
}

function deleteImageFile($post_id){
    if (file_exists(image_name($post_id))) {
        unlink(image_name($post_id));
        return true;
    }
    return false;
}

function deleteImage($post_id){
    try {
        global $db;
        $query = $db->prepare("UPDATE posts SET has_image = FALSE WHERE id = :id");
        $query->execute(array("id" => $post_id));
    } catch (Exception $e){
        add_message("Niečo sa nepodarilo počas aktualizácie databázy.");
        return false;
    }

    deleteImageFile($post_id);

    return true;
}
