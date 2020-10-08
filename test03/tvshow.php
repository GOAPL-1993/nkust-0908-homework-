<?php
require "../includes/config.php"; //比較重要一定要出現就用require, 此相較於includes指令
$pid = $_GET["pid"];
$name = $_GET["name"];
$tags = "<iframe width='784' height='441' src='https://www.youtube.com/embed/^^^^' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
session_start();
//先從Session中取出user_type
//以備後續確認瀏覽者的身份別
$user_type = $_SESSION["user_type"];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 微軟正黑體;
    }
  </style>
  <title>MY PLAYLIST</title>
</head>

<body>
  <h2><?php echo $name; ?></h2>
  <hr>
  <?php include "../includes/menu.php"; ?>
  <hr>
  <?php
  //以下建立SQL查詢指令
  $sql = "SELECT * FROM video WHERE pid='$pid'";
  //以下執行SQL查詢指令，並把結果傳回給$result變數
  $result = $conn->query($sql);
  if ($user_type != NULL) {
    //如果已經登入的話，要有可以新增影片的表單
    echo "<form method=POST action=addvideo.php>";
    echo "<input type=hidden value='$pid' name=pid>";
    echo "<input type=hidden value='$name' name=name>";
    echo "<input type=hidden value='$vid' name=vid>";
    echo "ADD NEW VIDEO<input type=text name=title size=40>";
    echo "VIDEO ID<input type=text name=vid size=15>";
    echo "<input type=submit value=ADD>";
    echo "</form>";
  }
  if ($result->num_rows > 0) { //檢查記錄的數量，看看是否有資料
    // output data of each row
    echo "<table width=800 bgcolor=white>";
    //下面這行是表格的標題列
    if ($user_type == NULL) {
      //如果未登入的話，就維持原有的標題
      echo "<tr bgcolor=#bbbbbb><td>TITLE</td></tr>";
    } else {
      //如果已經登入的話，就加上「貼文管理」欄位
      echo "<tr bgcolor=#bbbbbb><td>TITLE</td><td>MANAGE</td></tr>";
    }
    while ($row = $result->fetch_assoc()) {
      $id = $row["id"];
      echo "<tr bgcolor=pink>";
      echo "<td>" . $row["title"] . "</td>";
      // echo "<td>" . $row["vid"]. "</td>"; 
      // 如果是已登入使用者，要加上貼文管理（連結）的欄位
      if ($user_type != NULL) {
        echo "<td>";

        echo "<a href='delvideo.php?id=$id&pid=$pid&name=$name'>DELETE</a>";
        echo "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "0 results"; // 如果資料表中沒有記錄，要顯示的內容
  }
  ?>
  <hr>
  <?php
  //以下執行SQL查詢指令，並把結果傳回給$result變數
  if ($result->num_rows > 0) { //檢查記錄的數量，看看是否有資料
    echo "<form method='POST' action='tvshow.php?pid=$pid&name=$name&vid=$vid'>";
    echo "<select name=$row>";
    $sql = "SELECT * FROM video WHERE pid='$pid'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        // $row = $row["title"] . $row["vid"];
        echo "<option value='row'>";
        echo $row["title"];
        echo "</option>";
        $getvid = $row["vid"];
        // echo "<option>" . $row["title"] . "</option>";
      }
      echo "<input type=submit value=submit>";
    }
    $conn->close();
    echo "</select>";
    echo "</form>";
  } else {
    echo "0 results"; // 如果資料表中沒有記錄，要顯示的內容
  }


  $row = $_POST["row"];
  if ($row == NULL) { //如果找不到表單項目時
    $gettitle = "jojo"; //設定預設影片的標題和ID
    $getvid = "Q9o4MumeWj8";
  } else {
    $gettitle = $row["title"];
    $getvid = $row["vid"];
  }
  echo $gettitle;
  echo "<br>";
  echo str_replace("^^^^", $getvid, $tags); //取代ID來顯示影片
  ?>
  <hr>
  <?php include "../includes/footer.php"; ?>
</body>

</html>