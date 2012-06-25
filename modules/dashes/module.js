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
  $.post('index.php?view=dashes', { users: users, product_id: <?php echo $most_popular->id ?> }, function(data){
    if(data.online) {
      $('#offline').fadeOut();
    } else {
      $('#offline').fadeIn();
    }
  }, 'json');
  
  if(usersAreSelected()) {
    if(users.length == 1) {
      $('#remyplayer').get(0).play();
    } else {
      $('#kachingplayer').get(0).play();
    }
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

  if(users.length == 1) {
    $('#remyplayer').get(0).play();
  } else {
    $('#kachingplayer').get(0).play();
  }
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

$('#ping').click(function() {
  $('#pingplayer').get(0).play();
});
