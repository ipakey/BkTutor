<?php
if (isset($_GET['date'])) {
    $date = $_GET['date'];
}

if (isset($_POST['submit-booking'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $student = $_POST['student'];
    $type = $_POST['type'];
    $timeslot = $_POST['timeslot'];
    $uid = $_POST['uid'];
    $location = $_POST['location'];
    $paid = "N";
    $completed = "N";

    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("INSERT INTO bookings(email, uid, student, location , subject , grpind, date, timeslot, paid, completed ) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssssss', $email, $uid, $student, $location, $subject, $type, $date, $timeslot, $paid, $completed);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Booking Successful</div>";
    $stmt->close();
    $mysqli->close();
}

$duration = 25;
$cleanup = 5;
$start = "15:00";
$end = "21:00";

function timeslots($duration, $cleanup, $start, $end)
{
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT" . $duration . "M");
    $cleanupinterval = new DateInterval("PT" . $cleanup . "M");
    $slots = array();
    for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupinterval)) {
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if ($endPeriod > $end) {
            break;
        }

        $slots[] = $intStart->format("H:iA") . "-" . $endPeriod->format("H:iA");
    }
    return $slots;
}

include('..includes/pages/header.php');

?>

<!-- Modal -->
<div class="modal fade" id="bookingData" tabindex="-1" role="dialog" aria-labelledby="insertDataLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="insertDataLabel">To Book please fill in all the details:
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" href='./assets/pages/book.php?date=" . $date ."?ts=".<?php echo $ts; ?>."'>>&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body mt-5">
                <div class="form-group">
                    <label for="name">Slot</label>
                    <input type="text" class="form-control mb-3" placeholder="<?php echo $ts; ?>">
                </div>
                <div class="form-group"> <label for="name">Name</label>
                    <input type="text" class="form-control mb-3" placeholder="name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label><input type="email" class="form-control mb-3" placeholder="email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label><input type="number" class="form-control mb-3" placeholder="phone">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Book</button>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <h1 class="center-text">Booking for <?php echo date('d F Y', strtotime($date)); ?></h1>
    <div class="row justify-content-center">
        <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
        foreach ($timeslots as $ts) {
        ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="header-text">
                            Booking Button
                        </div>
                        <button type="button" class="btn btn-book float-end" data-bs-toggle="modal" data-bs-target="#bookingData"
                            id="openModal">
                            <?php echo $ts; ?>
                        </button>
                    </div>
                    <div class="card-body"></div>
                    <div class="card-footer"></div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<footer>
    <?php include('/includes/pages/footer.php'); ?>
    <script>
        // Get the modal
        var modal = document.getElementById("bookingData");

        // Get the button that opens the modal
        var btn = document.getElementById("openModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>


</footer>
</body>

</html>