<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員名簿</title>
    <link rel="stylesheet" type="text/css" href="stylecreate.css"/>
</head>
<body>
      <form action="file_post.php" method="POST" enctype="multipart/form-data" id=form>
    <fieldset>
      <legend>社員情報入力（入力画面）</legend>
      <a href="file_read.php">一覧画面</a>
      <div>
        社員番号: <input type="text" name="nom">
      </div>
      <div>
        氏名: <input type="text" name="name">
      </div>
      <div> 職能：<select id="class" name="class">
        <option value="">職能を選択</option>
        <option value="機構">機構</option>
        <option value="電気">電気</option>
        <option value="ソフト">ソフト</option>
      </select>
      </div>
      <div>
        業務: <input type="text" name="job">
      </div>
      顔写真:<div id="edit-img" class="menberimg">
        <img id="selectimg"  src="" alt="">
      </div>
      <div>
      写真選択:<input type="file" id="imgInput" name="img">
        <div id="imgContainer"></div>
      </div>
      <div>
        <button id="submitbt">submit</button>
      </div>
    </fieldset>
  </form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    $('#imgInput').on('change', function(event){
      let input = event.target;
      if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
        $('#selectimg').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      };
    });
  });


  $(document).ready(function() {
    $('#employeeForm').on('keypress', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
      }
    });

    $('#submitbt').on('click', function() {
      $('#employeeForm').submit();
      alert("データ保存しました!");
    });
  });
  </script>

</body>
</html>