<?php

foreach ($_POST['BX_weekday'] as $a => $b) {
								
								echo '<tr>
										<td>
											<input type="checkbox" name="chk[]" />';
									?>
										<input type="hidden" name="hdfTimeTableId[]" value="<?php echo $_POST['hdfTimeTableId'][$a];?>">
								<?php										
								echo '	</td>';
										
								
								echo '	<td>
											<select id="BX_weekday" name="BX_weekday[]" required>';
											if ($_POST['BX_weekday'][$a] == 'Monday') {
												echo '<option selected="selected" value="Monday">Monday</option>';
											}
											else {
												echo '<option value="Monday">Monday</option>';
											}
											if ($_POST['BX_weekday'][$a] == 'Tuesday') {
												echo '<option selected="selected" value="Tuesday">Tuesday</option>';
											}
											else {
												echo '<option value="Tuesday">Tuesday</option>';
											}
											if ($_POST['BX_weekday'][$a] == 'Wednesday') {
												echo '<option selected="selected" value="Wednesday">Wednesday</option>';
											}
											else {
												echo '<option value="Wednesday">Wednesday</option>';
											}
											if ($_POST['BX_weekday'][$a] == 'Thursday') {
												echo '<option selected="selected" value="Thursday">Thursday</option>';
											}
											else {
												echo '<option value="Thursday">Thursday</option>';
											}
											if ($_POST['BX_weekday'][$a] == 'Friday') {
												echo '<option selected="selected" value="Friday">Friday</option>';
											}
											else {
												echo '<option value="Friday">Friday</option>';
											}
											echo '</select>
										</td>';
								  echo '<td>
											<select id="BX_start_time_hour" name="BX_start_time_hour[]">';
											if ($_POST['BX_start_time_hour'][$a] == '09') {
												echo '<option selected="selected" value="09">09</option>';
											}
											else {
												echo '<option value="09">09</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '10') {
												echo '<option selected="selected" value="10">10</option>';
											}
											else {
												echo '<option value="10">10</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '11') {
												echo '<option selected="selected" value="11">11</option>';
											}
											else {
												echo '<option value="11">11</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '12') {
												echo '<option selected="selected" value="12">12</option>';
											}
											else {
												echo '<option value="12">12</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '13') {
												echo '<option selected="selected" value="13">13</option>';
											}
											else {
												echo '<option value="13">13</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '14') {
												echo '<option selected="selected" value="14">14</option>';
											}
											else {
												echo '<option value="14">14</option>';
											}
											if ($_POST['BX_start_time_hour'][$a] == '15') {
												echo '<option selected="selected" value="15">15</option>';
											}
											else {
												echo '<option value="15">15</option>';
											}
									  echo '</select>';
									  echo '<select id="BX_start_time_minute" name="BX_start_time_minute[]">';
										    if ($_POST['BX_start_time_minute'][$a] == '00') {
												echo '<option selected="selected" value="00">00</option>';
											}
											else {
												echo '<option value="00">00</option>';
											}
											if ($_POST['BX_start_time_minute'][$a] == '30') {
												echo '<option selected="selected" value="30">30</option>';
											}
											else {
												echo '<option value="30">30</option>';
											}
									  echo '</select>
									    </td>';
									  
								    echo '<td>
											<select id="BX_finish_time_hour" name="BX_finish_time_hour[]">';
											if ($_POST['BX_finish_time_hour'][$a] == '09') {
												echo '<option selected="selected" value="09">09</option>';
											}
											else {
												echo '<option value="09" selected>09</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '10') {
												echo '<option selected="selected" value="10">10</option>';
											}
											else {
												echo '<option value="10">10</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '11') {
												echo '<option selected="selected" value="11">11</option>';
											}
											else {
												echo '<option value="11">11</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '12') {
												echo '<option selected="selected" value="12">12</option>';
											}
											else {
												echo '<option value="12">12</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '13') {
												echo '<option selected="selected" value="13">13</option>';
											}
											else {
												echo '<option value="13">13</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '14') {
												echo '<option selected="selected" value="14">14</option>';
											}
											else {
												echo '<option value="14">14</option>';
											}
											if ($_POST['BX_finish_time_hour'][$a] == '15') {
												echo '<option selected="selected" value="15">15</option>';
											}
											else {
												echo '<option value="15">15</option>';
											}
									  echo '</select>';
									  echo '<select id="BX_finish_time_minute" name="BX_finish_time_minute[]">';
									  		if ($_POST['BX_finish_time_minute'][$a] == '00') {
												echo '<option selected="selected" value="00">00</option>';
											}
											else {
												echo '<option value="00">00</option>';
											}
											if ($_POST['BX_finish_time_minute'][$a] == '30') {
												echo '<option selected="selected" value="30">30</option>';
											}
											else {
												echo '<option value="30">30</option>';
											}
									  echo '</select>
									  </td>';
									  
										
								echo '</tr>';								
								
							}
							
?>							