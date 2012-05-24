<div style="margin: 0 10px">
<h1>Huize Entree Streeplijst</h1>
<h2>Versie X</h2>
<p><input type="button" value="gebruikersavatars vernieuwen" style="font-size: 36px" id="avarefresh"></p>
</div>
<script type="text/javascript">
$('#avarefresh').click(function() {
  $.getJSON('actions/avatar_refresh.php', function(data) {
    if(data.online) {
      $('#offline').fadeOut();
    } else {
      $('#offline').fadeIn();
    }
  });
});
</script>
