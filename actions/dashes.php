<?php
require '../bootstrap.php';
$most_popular = $api->get_json('products', 'most_popular');
$products = $api->get_json('products');
$top_drinker = $api->get_json('users', 'top_drinker');
$persons = $api->get_json('users');

$dashes = unserialize($_COOKIE['dashes']);
?>
<!-- HTML -->
<div id="menu-person" class="sidebar">
  <ul>
    <li id="snelstrepen">
      <img src="assets/images/buttons/snelstrepen.png" width="64" height="64">
      Snelstrepen<br>
      <span style="font-size: 12pt"><?php echo $most_popular->display_name ?></span>
    </li>
    <li id="strepen">
      <img src="assets/images/buttons/strepen.png" width="64" height="64">
      <div>Strepen</div>
    </li>
  </ul>
</div>
<div id="menu-product" class="sidebar">
  <ul>
    <li id="product-back">
      <img src="assets/images/buttons/back.png" width="64" height="64">
      <div>Terug</div>
    </li>
  </ul>
</div>
<table id="persons">
  <tr>
<?php
$i = 0;

foreach($persons as $person) {
  if($i % 4 == 0) {
    echo "  </tr>";
    echo "  <tr>";
  }

  echo "    <td>";
  echo "      <div id=\"persoon-".$person->id."\" class=\"person block\">";
  echo "        <img src=\"assets/images/faces/".$person->id.".jpg\" width=\"64\" height=\"64\"><br>";
  echo "        ".$person->display_name;
  if($person->id == $top_drinker->id) echo "        <div class=\"topdrinker\"><img src=\"assets/images/topdrinker.png\" width=\"32\" height=\"32\" alt=\"Bierkoning\"></div>";
  if($dashes[$person->id]) { echo "        <div class=\"counter\">".$dashes[$person->id]."</div>"; } else { echo "        <div class=\"counter hidden\">0</div>"; }
  echo "      </div>";
  echo "    </td>";
  
  $i++;
  
  //if($i == 12) break;
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
<!-- ASSETS -->
<audio src="assets/sounds/ping.wav" id="pingplayer"></audio>
<audio src="assets/sounds/kaching.wav" id="kachingplayer"></audio>
<audio src="assets/sounds/cuckoo.wav" id="cuckooplayer"></audio>
<audio src="assets/sounds/gaaay.wav" id="gaaayplayer"></audio>
<!-- SCRIPT -->
<script type="text/javascript">
var users = [];

$('#products').hide();
$('#menu-product').hide();

function changeCounter(id, change) {
  elem = $('#persoon-'+id+' .counter');
  old = parseInt(elem.html());
  dashes = old + change;
  if(old == 0) elem.removeClass('hidden');
  if(dashes == 0) elem.addClass('hidden');
  elem.html(dashes);
}

function resetDashes() {
  users = [];
  $('.person').removeClass('selected');
}

function usersAreSelected() {
  if(users.length == 0) {
    $('#cuckooplayer').get(0).play(); // punishment!
    return false;
  } else {
    return true;
  }
}

$('.person').click(function() {
  if($(this).hasClass('blok')) {
    alert('Er kan niet voor deze persoon gestreept worden. De betalingstermijn voor zijn/haar factuur is verstreken en moet dus eerst betaald worden voordat hij/zij weer bier mag drinken.');
  } else {
    id = parseInt($(this).attr('id').substr(8));
    if(isNaN(id)) return;
    $(this).toggleClass('selected');

    if($(this).hasClass('selected')) {
      changeCounter(id, 1);
      users.push(id);  
    } else {
      changeCounter(id, -1);
      index = users.indexOf(id);
      if(index != -1) users.splice(index, 1);
    }
  }
});

$('#snelstrepen').click(function() {
  $.post('actions/postdashes.php', { users: users, product_id: <?php echo $most_popular->id ?> }, function(data){
    if(data.online) {
      $('#offline').fadeOut();
    } else {
      $('#offline').fadeIn();
    }
  }, 'json');
  
  if(usersAreSelected()) {
    $('#kachingplayer').get(0).play();
  }
  resetDashes();
});

$('#strepen').click(function() {
  if(usersAreSelected()) {
    $('#menu-person').hide('slow');
    $('#persons').hide('slow', function() {
      $('#menu-product').show('slow');
      $('#products').show('slow');
    });
  }
});

$('.product').click(function() {
  $.post('actions/postdashes.php', { users: users, product_id: parseInt($(this).attr('id').substr(8)) }, function(data){
    if(data.online) {
      $('#offline').fadeOut();
    } else {
      $('#offline').fadeIn();
    }
  }, 'json');

  $('#kachingplayer').get(0).play();
  resetDashes();
  $('#menu-product').hide('slow');
  $('#products').hide('slow', function() {
    $('#menu-person').show('slow');
    $('#persons').show('slow');
  });
});

$('#product-back').click(function() {
  $('#menu-product').hide('slow');
  $('#products').hide('slow', function() {
    $('#menu-person').show('slow');
    $('#persons').show('slow');
  });
});

$('#gaaay').click(function() {
  $('#gaaayplayer').get(0).play();
});

$(document).keypress(function(event) {
  if(event.which == 112) {
    $('#pingplayer').get(0).play();
  }
});
</script>

