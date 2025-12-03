<?php
$token = "8447762437:AAE9XII6D1vug67Xw-D1gmyfay7JlHvzaec"; // ← عوض کن!
$api = "https://api.telegram.org/bot$token";
$update = json_decode(file_get_contents("php://input"), true);
if (!$update) exit;

$chat_id = $update["message"]["chat"]["id"] ?? "";
$text = trim($update["message"]["text"] ?? "");

function send($id, $msg) {
    global $api;
    file_get_contents($api."/sendMessage?chat_id=$id&text=".urlencode($msg)."&disable_web_page_preview=true");
}

if ($text == "/start") {
    send($chat_id, "سلام من آوام ! لینک یوتیوبتو بفرست تا برات دانلود رو شروع کنم کنم و لینک مستقیم بدم 🚀");
    exit;
}

if (filter_var($text, FILTER_VALIDATE_URL)) {
    send($chat_id, "یکم صبر کن الان آماده میشه!⏳");

    // اسم فایل اصلی سورست رو اینجا عوض کن (مثلاً index.php یا download.php)
    $script = "/index.php"; // ← عوض کن

    $ch = curl_init("https://" . $_SERVER['HTTP_HOST'] . $script);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $text]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
    $result = curl_exec($ch);
    curl_close($ch);

    preg_match_all('#https?://[^\s<>"\']+#i', $result, $links);
    $direct = array_filter($links[0], fn($l) => strlen($l) > 30 && strpos($l, 'render.com') !== false);

    if ($direct) {
        send($chat_id, "آماده شد! 🚀\n\n" . implode("\n\n", array_slice($direct, 0, 3)));
    } else {
        send($chat_id, "عام.. یه مشکلی پیش اومد دوباره تلاش کن");
    }
}
?>
