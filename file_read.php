<?php
// ディレクトリ内のすべてのCSVファイルを取得
$files = glob("data/*.csv");
$fileimg = glob("data/img/*.png");

// var_dump($fileimg);
// exit();

$str = '';
$array = [];

foreach ($files as $filename) {
  if (file_exists($filename)) {
    // ファイルを開く
    $file = fopen($filename, 'r');
    
    if ($file) {
      // ファイルをロックする
      flock($file, LOCK_EX);

      // ファイルの内容を読み込む
      while ($line = fgets($file)) {
        // $str .= "<tr><td>{$line}</td></tr>";
        $lineData = explode("," , $line);
        $array[] = [
          "nom" => str_replace(" ", "", $lineData[0]),
          "name" => str_replace(" ", "", $lineData[1]),
          "class" => str_replace(" ", "", $lineData[2]),
          "job" => str_replace(" ", "", str_replace("\n", "", $lineData[3]))
        ];
      };

      // ファイルのロックを解除する
      flock($file, LOCK_UN);

      // ファイルを閉じる
      fclose($file);
    };
  };
};
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>社員名簿（一覧画面）</title>
  <link rel="stylesheet" type="text/css" href="style.css" />

  <!--Load the AJAX API-->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  
  // phpからデータ取得
  const array = <?= json_encode($array) ?>;
  console.log(array);

  const readimg = <?= json_encode($fileimg) ?>;
  console.log(readimg);

  // 職能別の人数をカウント
  const selectmecha = "機構";
  const selectelec = "電気";
  const selectsoft = "ソフト";
  const countmecha = selectmecha ? array.filter(array => array.class === selectmecha) : array;
  const countelec = selectelec ? array.filter(array => array.class === selectelec) : array;
  const countsoft = selectsoft ? array.filter(array => array.class === selectsoft) : array;
  console.log(countmecha.length , countelec.length , countsoft.length);

  // Load the Visualization API and the corechart package.
  google.charts.load('current', { 'packages': ['corechart'] });

  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {
    const mecha = countmecha.length;
    const elec = countelec.length;
    const soft = countsoft.length;
    console.log(mecha , elec , soft);

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'kinds');
    data.addColumn('number', 'peoplenom');
    data.addRows([
    ['機構', mecha],
    ['電機', elec],
    ['ソフト', soft]
    ]);

  // Set chart options
  let options = {
    'title': '職能別割合',
    'width': 600,
    'height': 400
  };

  // Instantiate and draw our chart, passing in some options.
  let chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  };
  </script>
</head>

<body>
  <!-- 社員名簿作成 -->
  <fieldset class="list">
    <legend>社員名簿（一覧画面）</legend>
    <a href="file_create.php">入力画面</a>
    <div class="table" id="menberlist"></div>
    <div>
      <p id="allmenber">社員数</p>
    </div>
  </fieldset>
  
  <!--Div that will hold the pie chart-->
  <div id="chart_div"></div>

<!-- jQueryライブラリの読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
function displaymenber(array, readimg) {
    // テーブルの開始タグとヘッダーを追加
    let memberTable = `<table border="1">
    <thead>
      <tr>
        <th>社員番号</th>
        <th>氏名</th>
        <th>職能</th>
        <th>業務</th>
        <th>画像</th>
      </tr>
    </thead>
    <tbody>`;

    // データ行を追加
    for (let i = 0; i < array.length; i++) {
        memberTable += `<tr>
            <td>${array[i].nom}</td>
            <td>${array[i].name}</td>
            <td>${array[i].class}</td>
            <td>${array[i].job}</td>
            <td><img src="${readimg[i]}" style="max-width: 100px; max-height: 100px;"></td>
        </tr>`;
    };


// <td><img class="readimg" src="${readimg[i]}" style="max-width: 100px; max-height: 100px;"></td>

    // テーブルの終了タグを追加
    memberTable += `</tbody></table>`;

  // 職能別の人数をカウント
  const selectmecha = "機構";
  const selectelec = "電気";
  const selectsoft = "ソフト";
  const countmecha = selectmecha ? array.filter(array => array.class === selectmecha) : array;
  const countelec = selectelec ? array.filter(array => array.class === selectelec) : array;
  const countsoft = selectsoft ? array.filter(array => array.class === selectsoft) : array;

    // HTMLにテーブルを挿入
    $("#menberlist").html(memberTable);

    const totalmenber = "総数:" + array.length + "人"+ " "+"内訳)" + "機構" + countmecha.length + "人" + " , " + "電気" + countelec.length + "人" + " , " + "ソフト" + countsoft.length + "人";
    console.log(totalmenber);
    $('#allmenber').text(totalmenber);
  };
      // 関数呼び出し
  displaymenber(array, readimg);

</script>
</body>
</html>