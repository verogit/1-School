<?php

foreach ($subjectBd->timeTables as $a => $b) { //$a is the index and $b is the object
								
								$day = $b->day;
								$startTimeHour = date('H',strtotime($b->startTime));
								$startTimeMinutes = date('i',strtotime($b->startTime));
								$finishTimeHour = date('H',strtotime($b->finishTime));
								$finishTimeMinutes = date('i',strtotime($b->finishTime)); 
								
								echo '<tr>
										<td>
											<input type="checkbox" name="chk[]" />';
								?>
											<input type="hidden" name="hdfTimeTableId[]" value="<?php echo $b->id;?>">
								<?php		
								echo'	</td>';
								
								echo '	<td>
											<select id="BX_weekday" name="BX_weekday[]" required>';
											if ($day == 'Monday') {
												echo '<option selected="selected" value="Monday">Monday</option>';
											}
											else {
												echo '<option value="Monday">Monday</option>';
											}
											if ($day == 'Tuesday') {
												echo '<option selected="selected" value="Tuesday">Tuesday</option>';
											}
											else {
												echo '<option value="Tuesday">Tuesday</option>';
											}
											if ($day == 'Wednesday') {
												echo '<option selected="selected" value="Wednesday">Wednesday</option>';
											}
											else {
												echo '<option value="Wednesday">Wednesday</option>';
											}
											if ($day == 'Thursday') {
												echo '<option selected="selected" value="Thursday">Thursday</option>';
											}
											else {
												echo '<option value="Thursday">Thursday</option>';
											}
											if ($day == 'Friday') {
												echo '<option selected="selected" value="Friday">Friday</option>';
											}
											else {
												echo '<option value="Friday">Friday</option>';
											}
											echo '</select>
										</td>';
								  echo '<td>
											<select id="BX_start_time_hour" name="BX_start_time_hour[]">';
											if ( $startTimeHour == '09') {
												echo '<option selected="selected" value="09">09</option>';
											}
											else {
												echo '<option value="09">09</option>';
											}
											if ($startTimeHour == '10') {
												echo '<option selected="selected" value="10">10</option>';
											}
											else {
												echo '<option value="10">10</option>';
											}
											if ($startTimeHour == '11') {
												echo '<option selected="selected" value="11">11</option>';
											}
											else {
												echo '<option value="11">11</option>';
											}
											if ($startTimeHour == '12') {
												echo '<option selected="selected" value="12">12</option>';
											}
											else {
												echo '<option value="12">12</option>';
											}
											if ($startTimeHour == '13') {
												echo '<option selected="selected" value="13">13</option>';
											}
											else {
												echo '<option value="13">13</option>';
											}
											if ($startTimeHour == '14') {
												echo '<option selected="selected" value="14">14</option>';
											}
											else {
												echo '<option value="14">14</option>';
											}
											if ($startTimeHour == '15') {
												echo '<option selected="selected" value="15">15</option>';
											}
											else {
												echo '<option value="15">15</option>';
											}
									  echo '</select>';
									  echo '<select id="BX_start_time_minute" name="BX_start_time_minute[]">';
										    if ($startTimeMinutes == '00') {
												echo '<option selected="selected" value="00">00</option>';
											}
											else {
												echo '<option value="00">00</option>';
											}
											if ($startTimeMinutes == '30') {
												echo '<option selected="selected" value="30">30</option>';
											}
											else {
												echo '<option value="30">30</option>';
											}
									  echo '</select>
									    </td>';
									  
								    echo '<td>
											<select id="BX_finish_time_hour" name="BX_finish_time_hour[]">';
											if ($finishTimeHour == '09') {
												echo '<option selected="selected" value="09">09</option>';
											}
											else {
												echo '<option value="09">09</option>';
											}
											if ($finishTimeHour == '10') {
												echo '<option selected="selected" value="10">10</option>';
											}
											else {
												echo '<option value="10">10</option>';
											}
											if ($finishTimeHour == '11') {
												echo '<option selected="selected" value="11">11</option>';
											}
											else {
												echo '<option value="11">11</option>';
											}
											if ($finishTimeHour == '12') {
												echo '<option selected="selected" value="12">12</option>';
											}
											else {
												echo '<option value="12">12</option>';
											}
											if ($finishTimeHour == '13') {
												echo '<option selected="selected" value="13">13</option>';
											}
											else {
												echo '<option value="13">13</option>';
											}
											if ($finishTimeHour == '14') {
												echo '<option selected="selected" value="14">14</option>';
											}
											else {
												echo '<option value="14">14</option>';
											}
											if ($finishTimeHour == '15') {
												echo '<option selected="selected" value="15">15</option>';
											}
											else {
												echo '<option value="15">15</option>';
											}
									  echo '</select>';
									  echo '<select id="BX_finish_time_minute" name="BX_finish_time_minute[]">';
									  		if ($finishTimeMinutes == '00') {
												echo '<option selected="selected" value="00">00</option>';
											}
											else {
												echo '<option value="00">00</option>';
											}
											if ($finishTimeMinutes == '30') {
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