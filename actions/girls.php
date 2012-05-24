<?php
require '../bootstrap.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($_POST['act'] == 'up') {
    mysql_query("UPDATE `girls` SET `up`=`up`+1 WHERE `id`=".$_POST['id']);
  } else {
    mysql_query("UPDATE `girls` SET `down`=`down`+1 WHERE `id`=".$_POST['id']);
  }
  
  exit;
}

$id = rand(1,412);

$result = mysql_query("SELECT * FROM `girls` WHERE `id`=".$id." LIMIT 1");
$girl = mysql_fetch_assoc($result);

$votes = $girl['up'] + $girl['down'];

if($votes == 0) {
  $score = 0;
} else {
  $score = round(($girl['up'] / $votes) * 100);
}
?>
<div style="text-align: center">
<img src="assets/girls/girl-<?php echo $girl['id'] ?>.jpg" height="600"><br>
<img src="assets/images/buttons/upvote.png" width="64" height="64" id="upvote" align="absmiddle">
<span style="font-size: 32pt"><?php echo $score . '%' ?></span>
<img src="assets/images/buttons/downvote.png" width="64" height="64" id="downvote" align="absmiddle">
</div>
<script type="text/javascript">
$('#upvote').click(function() {
  $.post('actions/girls.php', { id: <?php echo $id ?>, act: 'up' });
  switchPage('girls');
});

$('#downvote').click(function() {
  $.post('actions/girls.php', { id: <?php echo $id ?>, act: 'down' });
  switchPage('girls');
});
</script>
