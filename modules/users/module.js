$('#checkin').click(function() {
  user = $('#users').val();
  
  if(user) {
    $('#checkinplayer').get(0).play();
    $.post('index.php?view=users', { user: $('#users').val() }, function(data) {
      switchPage('dashes');
    }, 'json');
  }
});
