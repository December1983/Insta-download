<?php
if (isset($_POST['link'])) {
    $url = $_POST['link'];

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        die("Неверная ссылка");
    }

    $apiURL = "https://pikapi.cam/api/ajaxSearch";
    $postFields = http_build_query(["q" => $url]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

    $result = curl_exec($ch);
    curl_close($ch);

    if (!$result) {
        die("Ошибка при обращении к API через CURL.");
    }

    $json = json_decode($result, true);

    if (isset($json['data'][0]['url'])) {
        $fileUrl = $json['data'][0]['url'];
        $filename = basename(parse_url($fileUrl, PHP_URL_PATH));

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($fileUrl);
        exit;
    } else {
        echo "Не удалось найти медиа по этой ссылке.";
    }
} else {
    echo "Ссылка не передана.";
}
?>
