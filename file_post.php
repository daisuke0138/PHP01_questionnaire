<?php
// データの受け取り
$nom = $_POST['nom'];
$name = $_POST['name'];
$class = $_POST['class'];
$job = $_POST['job'];
$img = $_FILES['img'];

// 画像ファイルの情報を取得
$filename = $img['name'];
$filetype = $img['type'];
$filetmpname = $img['tmp_name'];
$fileerror = $img['error'];
$filesize = $img['size'];

// デバッグ用の出力
print($filename);
print($filetype);
print($filetmpname);
print($fileerror);
print($filesize);

// 名前をURLエンコードする
$encodedName = urlencode($nom);
$csvname = "data/list_{$encodedName}.csv";

// csvデータをUTF-8に変換
$nom = mb_convert_encoding($nom, "UTF-8", "auto");
$name = mb_convert_encoding($name, "UTF-8", "auto");
$class = mb_convert_encoding($class, "UTF-8", "auto");
$job = mb_convert_encoding($job, "UTF-8", "auto");

// 画像保存先のパスを設定
$imgname = "data/img/img_{$encodedName}.png";

// ファイルのアップロード処理
if ($fileerror === UPLOAD_ERR_OK) {
    $success = move_uploaded_file($filetmpname, $imgname);
    if ($success) {
        echo "ファイルが正常にアップロードされました。";
    } else {
        echo "ファイルのアップロードに失敗しました。";
    }
} else {
    echo "ファイルのアップロード中にエラーが発生しました。エラーコード: " . $fileerror;
}

// csvデータ1件を1行にまとめる（最後に改行を入れる）
$arry = "{$nom},{$name},{$class},{$job},{$imgname}\n";

// ファイルの存在確認と新規作成時にBOMを追加
$file_path = "data/list_{$encodedName}.csv";
$file_exists = file_exists($file_path);

// ファイルを開く
$file = fopen($file_path , 'w');

// ファイルをロックする
flock($file, LOCK_EX);

// 新規作成時にBOMを書き込む
if (!$file_exists) {
    fwrite($file, "\xEF\xBB\xBF");
}

// 指定したファイルに指定したデータを書き込む
fwrite($file, $arry);

// ファイルのロックを解除する
flock($file, LOCK_UN);

// ファイルを閉じる
fclose($file);

// データ入力画面に移動する
header("Location: file_create.php");
exit;
?>


// var_dump($fileimg);
// exit();