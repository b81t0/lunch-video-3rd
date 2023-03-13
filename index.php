<?php
ini_set("memory_limit", "-1M");
function h($v){
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

$FILE = 'todo.txt';

$id = uniqid();

date_default_timezone_set('Japan');
$date = date('Y年m月d日H時i分');

$text = '';
$name = '';
$about= '';
$time='';
$DATA = [];
$BOARD = [];

if(file_exists($FILE)) {
    $BOARD = json_decode(file_get_contents($FILE));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
    if(!empty($_POST['txt']) && !empty($_POST['about']) && !empty($_POST['name']) && !empty($_POST['video_time'])){
        $text = $_POST['txt'];
				$name = $_POST['name'];
				$about = $_POST['about'];
				$time = $_POST['video_time'];
        $DATA = [$id, $date, $text, $name, $about, $time];
        $BOARD[] = $DATA;
        file_put_contents($FILE, json_encode($BOARD));
    }else if(isset($_POST['del'])){
        $NEWBOARD = [];
        foreach($BOARD as $DATA){
            if($DATA[0] !== $_POST['del']){
                $NEWBOARD[] = $DATA;
            }
        }

        file_put_contents($FILE, json_encode($NEWBOARD));
    }
    header('Location: '.$_SERVER['SCRIPT_NAME']);
    exit;
}
	$cnt = count($BOARD);
	$numbers = $BOARD;

//for ($i = 0 ; $i < count($BOARD); $i++){
  //$numbers[$BOARD[$i][0]] = (int)$BOARD[$i][5];
//}

$sequence = array();

$sum = 0;
$saidai=20;
while ($sum < $saidai) {
  $index = rand(0, count($numbers)- 1);
  $num = (int)$BOARD[$index][5];
  if ($sum + $num <= $saidai) {
    array_push($sequence,$numbers[$index][0]);
		unset($numbers[$index]);
    $sum += $num;
	}
}
?>

<!DOCTYPE html>
<html lang= "ja">
<head>
    <meta name= "viewport" content= "width=device-width, initial-scale= 1.0">
    <meta http-equiv= "content-type" charset= "utf-8">
		<link rel="icon" href = "youtube-icon3.png">		
		<link href="style.css" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@300&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation:wght@600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Edu+VIC+WA+NT+Beginner:wght@600&display=swap" rel="stylesheet">
    <title>LunchVideo</title>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
    <h1>LunchVideo_3rdGeneration</h1>
		<p class="p"><b style="color:red;">先生へ。ルーレットのところからリンクと削除ができるようになりました。</b>
			<br>みんなが楽しめる動画を客観的に考えて選びましょう。</p>
    
    <section class= "main">
			<div class="postForm">
				<h3 class ="postTitle">投稿する</h3>
        <form method= "post">
					<div class="form">
						<label>投稿者の名前</label>
						<input type= "text" name="name" class="postTextholder" id="postNameholder" placeholder="あなたの名前(15文字以下)" autocomplete="name" maxlength="15" required>
						<label>youtubeのリンク</label>
            <input type= "text" name= "txt" class="postTextholder" id="postURLholder" placeholder="youtubeのリンク" required>
						<label>動画についての説明</label>
						<input type= "text" name="about" class="postTextholder" id="postAboutholder" placeholder="動画の説明">
						<label>動画の時間の長さ<span style="font-size:13px;">(1分未満の場合1分で投稿)</span></label>
						<input type="number" name="video_time" class="postTextholder" max="25" min="1"value="1">
            <input type= "submit" class="formButton"  value= "投稿" onclick="alert();">
					</div>
				</form>
				<div id="player"></div>
				<div class="rec">
					<h3>ランダム20分パック</h3>
					<ul>
						<?php foreach($sequence as $video){
	$selected = array_search($video,array_column( $BOARD, 0)); ?>
							<form method= "post">
            <li id = "<?php echo $BOARD[(int)$selected][0];?>">
								<?php echo $BOARD[(int)$selected][1]; ?>&emsp;<?php echo $BOARD[(int)$selected][3]; ?>
                <br><?php echo $BOARD[(int)$selected][4]; ?>&emsp;<?php echo '<a href="'. $BOARD[(int)$selected][2] .'" target="_blank">'. $BOARD[(int)$selected][2] .'</a>';?>
                <input type= "hidden" name="del_sel" value= "<?php echo $BOARD[(int)$selected][0]; ?>" >
                <input type= "submit" class="button" value="削除">
				</form></a>
						</li>
						<?php } ?>
				</div>
			</div>
		<div class="select">
			<div class="selectRoulette">
				<h3 class="selectTitle">選択する</h3>
				<div class="outline">
      		<div class="roulette" id="roulette">Let's roulette!</div>
      		<div class="buttons">
      			<input type="button" class="pochitto_btn_blue" value="start!" id ="start"/>
						<input type="button" class="pochitto_btn_blue" value="stop!" id ="stop"/>
					</div>
				</div>
			</div>
			<div class="selectList">
				<ol id="list">
					<?php foreach((array)$BOARD as $DATA): ?>
					<form method="post">
            <li id = "<?php echo $DATA[0]; ?>">
								<?php echo h($DATA[3]); ?>&emsp;<?php echo $DATA[1]; ?>
                <br><?php echo h($DATA[4]); ?>&emsp;<?php echo '<a href="'. $DATA[2] .'" target="_blank">'. h($DATA[2]) .'</a>';?>
                <input type= "hidden" name= "del" value= "<?php echo $DATA[0]; ?>">
                <input type= "submit" class="button" value="削除">
						</li>
				</form>
        <?php endforeach; ?>
				</ol>
			</div>
			</div>
    </section>
    <script>
						function alert(){
				alert("リクエストを送信しました！");
			}

			
      var roulette;
			var board = <?php echo json_encode($BOARD) ?>;
			var data = <?php echo json_encode($DATA) ?>;


			
      function start() {
        roulette = setInterval(function() {
					var min = Math.ceil(1);
					var cnt = <?php echo $cnt; ?>;
      		var max = Math.floor(cnt);
          var idx = Math.floor( Math.random() * (max - min + 1) +1);
          var lists = document.getElementById('list');
					var hitTable = document.getElementById('hitTable');
					var hitList = lists[idx];
					var hit_url = board[idx][2];
					console.log(hit_url);
				document.getElementById("roulette").innerHTML =　
					"<form method='post' class='rlt'>"+ idx +"<a class='link_rlt'target='_blank' href='" + hit_url + "'style='margin-left:10px;'>" + hit_url + "</a><input type= 'hidden' name= 'del' value= " + board[idx][0] + "><input type= 'submit' class ='button' value= '削除' style='width:20px;height:40px;margin-left:10px;display:flex;'></form>";
        }, 20);
      }


      // ルーレットを停止
      function stop() {
        if(roulette) {
          clearInterval(roulette);
        }
      }

			let startBTN = document.getElementById('start');
			startBTN.addEventListener('click', start);

			let stopBTN = document.getElementById('stop');
			stopBTN.addEventListener('click',stop);
    </script>
		<script>
    $(function () {
      $('input[name="del_sel"]').on("click", function (event) {
        $.ajax({
          type: "POST",
          url: "delete.php",
          data: { "id": },
          dataType: "text"
        }).done(function (data) {
          // 通信成功時の処理
          $("#ret").html(data);
        }).fail(function (XMLHttpRequest, status, error) {
          // 津神失敗時の処理
          alert(error);
        });
      });
    });
  </script>
</body>
</html>