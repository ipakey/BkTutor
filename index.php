<?php
function build_calendar($month, $year)
{
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
        $year = $_GET['year'];
    }

    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("SELECT *from bookings where MONTH(date)=? AND YEAR(date)=?");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['date'];
            }
            $stmt->close();
        }
    }

    //First create an array of days of the week
    $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

    //get the first day of the month
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

    //get number of days in month
    $numberOfDays = date('t', $firstDayOfMonth);

    //get other information about the first day of this month
    $dateComponents = getDate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $dateToday = date('Y-m-d');


    // <div class="date"></div>
    // 



    //create html table

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center class='table-head'><h6 class='date'>$monthName $year</h6></tr><tr><center class='table-head align-items-center'>";
    $calendar .= "<button class='btn date arrow'<a class='date' href='?month=" . date("m", mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date("Y", mktime(0, 0, 0, $month - 1, 1, $year)) . "'><img src='./includes/images/navigateLeftBlue.png' alt='left arrow'> Previous Month</a></button>";

    $calendar .= "<a class='btn date ' href='?month=" . date("m") . "&year=" . date("Y") . "'>Current Month</a>";

    $calendar .= "<a class='btn date arrow ' href='?month=" . date("m", mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date("Y", mktime(0, 0, 0, $month + 1, 1, $year)) . "'> Next Month <img class='next' src='./includes/images/navigateRightBlueIcon.png' alt='right arrow'></a></center>";

    $calendar .= "<tr class='header'>";
    //calendar headers
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='table-header'>$day</th>";
    }
    $calendar .= "</tr>";
    $calendar .= "<tr>";
    //the variable $daysOfWeek will ensure 7 columns in table
    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td></td>";
        }
    }

    //initiate day counter
    $currentDay = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);


    while ($currentDay <= $numberOfDays) {

        // if 7th coumn reachedthen start a new row
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('I', strtotime($date)));
        $eventNum = 0;

        $today = $date == date('Y-m-d') ? "today" : "";
        if ($date < date('Y-m-d')) {
            $calendar .= "<td><button class='btn-na'><h6>$currentDay</h6>N/A</button></td>";
        } else {
            $calendar .= "<td class='" . $today . "'><button id='submit-bkg' type='submit'class='btn-bk'><a class='date-book' href='./assets/pages/book.php?date=" . $date . "'><h6>$currentDay</h6>Book</a></button></td>";
        }

        //increment counters
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    echo $calendar;
}

include('./includes/pages/header.php');

?>



<container class="container row-cols-2">
    <div class="left">
        <div class="calendar">
            <?php
            $dateComponents = getDate();
            $month = $dateComponents['mon'];
            $year = $dateComponents['year'];
            echo build_calendar($month, $year);
            ?>
        </div>
    </div>
    <div class="right">
        <div class="events">
            <p>Events Data</p>
        </div>

    </div>
</container>
<footer>
    <p>
        <?php include('./includes/pages/footer.php'); ?>
    </p>
</footer>
</body>

</html>