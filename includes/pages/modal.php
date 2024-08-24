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