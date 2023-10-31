$("#notificationsModal").on("hidden.bs.modal", function () {
  $(this).find(".modal-body").html(""); // Clear the modal content when closed
});

// // $(document).ready(function () {
// // Function to update the notification count
// function updateNotificationCount() {
//   $.ajax({
//     url: "get_notification_count.php", // Replace with the actual URL to fetch the notification count
//     type: "GET",
//     success: function (data) {
//       // Update the count in the round indicator
//       if (data !== "") {
//         $("#bell-count").attr("data-value", data);
//         $("#bell-count span").text(data);
//       }
//     },
//   });
// }

// // Call the function initially
// updateNotificationCount();

// // You can also set an interval to periodically update the count
// setInterval(updateNotificationCount, 60000); // Update every 60 seconds (adjust as needed)
// function updateNotificationCount() {
//   $.ajax({
//     type: "GET",
//     url: "get_notification_count.php",
//     success: function (data) {
//       // Update the notification count in the element with id 'notification-indicator'
//       $("#notification-indicator").text(data);
//     },
//   });
// }

// // Call the function to update the count on page load
// updateNotificationCount();
