<div id="menu-person" class="sidebar">
  <ul>
    <li id="checkin">
      <img src="<?= asset_path('checkin.png') ?>" width="64" height="64">
      <div>Check in</div>
    </li>
    <li id="strepen">
      <img src="<?= asset_path('new.png') ?>" width="64" height="64">
      <div>Nieuwe gast</div>
    </li>
  </ul>
</div>
<select id="users" style="width: 734px; height: 664px; margin: 20px; font-size: 36pt; background-color: transparent" size="2">
<?php
foreach($users as $user) {
  echo "  <option value=\"" . $user->id . "\">" . $user->name . "</option>\n";
}
?>
</select>
