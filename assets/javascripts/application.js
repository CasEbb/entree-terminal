function switchPage(page) {
  // menu aanpassen
  $('#menu ul li').removeClass('active');
  $('#menu ul li#menu-'+page).addClass('active');
  
  // huidige pagina weg
  $('#content').fadeOut('slow', function() { 
    // laden
    $.get('actions/'+page+'.php', function(data) {
      $('#content').html(data);
      $('#content').fadeIn();
    });
  });
}

function updateClock() {
  var now = new Date();
  var hours = now.getHours();
  var minutes = now.getMinutes();
  
  if(hours < 16) {
    minutes_left = 960 - ((hours * 60) + minutes);
    if(minutes < 10) minutes = "0" + minutes;
    $('#clock').html("nog "+minutes_left+" minuten tot biertijd, "+now.getHours()+":"+minutes);
  } else {
    if(minutes < 10) minutes = "0" + minutes;
    $('#clock').html(now.getHours()+":"+minutes);
  }
}

// Applicatie init code
$(document).ready(function(){
  // alle (nog) niet benodigde elementen hiden
  $('#offline').hide();

  // logo reload app
  $('#reload').click(function() {
    location.reload();
  });
  
  // menu werkzaam maken
  $('#menu ul li').click(function() {
    switchPage($(this).data('page'));
  });
  
  // statistieken
  $('#clock').click(function() {
    switchPage('stats');
  });
  
  switchPage('dashes');
  
  updateClock();
  setInterval("updateClock()", 45000);
  setTimeout("$('#bootscreen').fadeOut()", 5000);
});
