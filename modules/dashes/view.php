<div id="menu-person" class="sidebar">
  <ul>
    <li id="ping">
      <img src="<?= asset_path('ping.png') ?>" width="64" height="64">
      <div>Ping!</div>
    </li>
    <li id="snelstrepen">
      <img src="<?= asset_path('snelstrepen.png') ?>" width="64" height="64">
      Snelstrepen<br>
      <span style="font-size: 12pt"><?= $most_popular->display_name ?></span>
    </li>
    <li id="strepen">
      <img src="<?= asset_path('strepen.png') ?>" width="64" height="64">
      <div>Strepen</div>
    </li>
  </ul>
</div>
<div id="menu-product" class="sidebar">
  <ul>
    <li id="product-back">
      <img src="<?= asset_path('back.png') ?>" width="64" height="64">
      <div>Terug</div>
    </li>
  </ul>
</div>
<table id="persons">
  <tr>
<?php
$i = 0;

if(isset($_SESSION['checked_in'])) {
  foreach($persons as $person) {
    if(!in_array($person->id, $_SESSION['checked_in'])) continue;

    if($i % 4 == 0) {
      echo "  </tr>";
      echo "  <tr>";
    }

    echo "    <td>";
    echo "      <div id=\"persoon-".$person->id."\" class=\"person block\">";
    echo "        <img src=\"assets/images/faces/".$person->id.".jpg\" width=\"128\" height=\"128\"><br>";
    echo "        ".$person->display_name;
    if($person->id == $top_drinker->id) echo "        <div class=\"topdrinker\"><img src=\"assets/images/topdrinker.png\" width=\"32\" height=\"32\" alt=\"Bierkoning\"></div>";
    if($dashes[$person->id]) { echo "        <div class=\"counter\">".$dashes[$person->id]."</div>"; } else { echo "        <div class=\"counter hidden\">0</div>"; }
    echo "      </div>";
    echo "    </td>";
    
    $i++;
  }
}
?>
  </tr>
</table>
<table id="products">
  <tr>
<?php
$i = 0;

foreach($products as $product) {
  if($i % 4 == 0) {
    echo "  </tr>";
    echo "  <tr>";
  }

  echo "    <td>";
  echo "      <div id=\"product-".$product->id."\" class=\"product block\">";
  echo "        <img src=\"assets/products/".$product->id.".png\" width=\"128\" height=\"128\"><br>";
  echo "        ".$product->display_name;
  echo "      </div>";
  echo "    </td>";
  
  $i++;
  
  if($i == 12) break;
}
?>
  </tr>
</table>
