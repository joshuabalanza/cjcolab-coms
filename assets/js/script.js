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

$(document).ready(function () {
  // Handle clicking on the "Read" notification link
  $(".read-notification").click(function (event) {
    event.preventDefault();
    var notificationId = $(this).data("notification-id");
    var notificationContainer = $(event.target).closest("tr");
    var isBold = notificationContainer.hasClass("bold");
    $.ajax({
      type: "GET",
      url: "notifications.php",
      data: { action: "read", notification_id: notificationId },
      success: function (data) {
        // Update the content of the notification without a page reload
        notificationContainer.find("td:first").html(data.notification_type);
        notificationContainer.find("td:nth-child(2)").html(data.message);

        // Toggle the "bold" class
        if (isBold) {
          notificationContainer.removeClass("bold");
        } else {
          notificationContainer.addClass("bold");
        }

        // Toggle the icon class based on the "active" class
        var icon = notificationContainer.find(".read-notification i");
        icon.toggleClass("fa-envelope fa-envelope-open");
      },
    });
  });

  // Handle clicking on the "Delete" notification link
  $(".delete-notification").click(function (event) {
    event.preventDefault();
    var notificationId = $(this).data("notification-id");
    var notificationContainer = $(event.target).closest("tr");
    $.ajax({
      type: "GET",
      url: "notifications.php",
      data: { action: "delete", notification_id: notificationId },
      success: function () {
        // Remove the notification row from the table without a page reload
        notificationContainer.remove();
      },
    });
  });
});
