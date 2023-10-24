<div class="modal fade" id="notificationsModal" tabindex="-1" role="dialog" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Notification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display notifications from the database
                        $uid = $_SESSION['uid'];
                        $notificationsQuery = "SELECT * FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";
                        $notificationsResult = mysqli_query($con, $notificationsQuery);

                        while ($notification = mysqli_fetch_assoc($notificationsResult)) {
                            echo '<tr>';
                            echo '<td>' . $notification['timestamp'] . '</td>';
                            echo '<td>' . $notification['message'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
