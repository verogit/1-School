<div class="timetable">
<?php
    echo "<p class='day'>MONDAY </p>";
    foreach ($timetables as $timetable){
        if($timetable->day == "Monday"){
            echo'<p class="time">'. $timetable->subject.' '. $timetable->timeStart.' '.$timetable->timeFinish.'</p>';
        }
    }
    
    echo "<p class='day'>TUESDAY </p>";
    foreach ($timetables as $timetable){
        if($timetable->day == "Tuesday"){
            echo'<p class="time">' .$timetable->subject.' '. $timetable->timeStart.' '.$timetable->timeFinish.'</p>';
        }
    }
    
    echo "<p class='day'>WEDNESDAY </p>";
    foreach ($timetables as $timetable){
        if($timetable->day == "Wednesday"){
            echo'<p class="time">' .$timetable->subject.' '. $timetable->timeStart.' '.$timetable->timeFinish.'</p>';
        }
    }
    
    echo "<p class='day'>THURSDAY </p>";
    foreach ($timetables as $timetable){
        if($timetable->day == "Thursday"){
             echo'<p class="time">' .$timetable->subject.' '. $timetable->timeStart.' '.$timetable->timeFinish.'</p>';
        }
    }
    
    echo "<p class='day'>FRIDAY </p>";
    foreach ($timetables as $timetable){
        if($timetable->day == "Friday"){
              echo'<p class="time">'. $timetable->subject.' '. $timetable->timeStart.' '.$timetable->timeFinish.'</p>';
        }
    }
?>
</div>