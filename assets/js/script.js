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
        // call the function for the bell notification count
        // updateNotificationCount(); // Call a function to update the count

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
        // Update the notification count
        // updateNotificationCount(); // Call a function to update the count
        // Remove the notification row from the table without a page reload
        notificationContainer.remove();
      },
    });
  });
});

// function updateNotificationCount() {
//   $.ajax({
//     type: "GET",
//     url: "get_notification_count.php", // Endpoint to fetch the updated count
//     success: function (count) {
//       $("#notification-indicator").text(count);
//       // Handle the count display and any other necessary actions
//     },
//   });
// }

// function updateNotificationCount() {
//   $.ajax({
//     type: "GET",
//     url: "get_notification_count.php", // Endpoint to fetch the updated count
//     success: function (count) {
//       $("#notification-indicator").text(count);
//       // Handle the count display and any other necessary actions
//     },
//   });
// }

// modal for add concourse
// Get the modal and close button elements
var addConcourseModal = document.getElementById("addConcourseModal");
var openAddConcourseModalBtn = document.getElementById("openAddConcourseModal");
var closeAddConcourseModalBtn = document.getElementById(
  "closeAddConcourseModal"
);

// Function to open the modal
function openAddConcourseModal() {
  addConcourseModal.style.display = "block";
}
// Event listener for the open button
// openAddConcourseModalBtn.addEventListener("click", openAddConcourseModal);

// Event listener for the close button if it exists
if (openAddConcourseModalBtn) {
  openAddConcourseModalBtn.addEventListener("click", openAddConcourseModal);
}

// Function to close the modal
function closeAddConcourseModal() {
  addConcourseModal.style.display = "none";
}
// Event listener for the close button
// closeAddConcourseModalBtn.addEventListener("click", closeAddConcourseModal);

// Event listener for the close button if it exists
if (closeAddConcourseModalBtn) {
  closeAddConcourseModalBtn.addEventListener("click", closeAddConcourseModal);
}
// You can also close the modal when the user clicks anywhere outside the modal content
window.addEventListener("click", function (event) {
  if (event.target == addConcourseModal) {
    closeAddConcourseModal();
  }
});
// Get the modal and close button elements
// var modal = document.getElementById("verificationModal");
// var closeBtn = modal.querySelector(".close");

// // Function to close the modal
// function closeModal() {
//   modal.style.display = "none";
// }

// // Event listener for the close button
// closeBtn.addEventListener("click", closeModal);

// // You can also close the modal when the user clicks anywhere outside the modal content
// window.addEventListener("click", function (event) {
//   if (event.target == modal) {
//     closeModal();
//   }
// });
document.addEventListener("DOMContentLoaded", function () {
  // Get the modal and close button elements
  var modal = document.getElementById("verificationModal");
  var closeBtn = modal ? modal.querySelector(".close") : null;

  // Function to close the modal
  function closeModal() {
    if (modal) {
      modal.style.display = "none";
    }
  }

  // Event listener for the close button
  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
  }

  // You can also close the modal when the user clicks anywhere outside the modal content
  window.addEventListener("click", function (event) {
    if (modal && event.target == modal) {
      closeModal();
    }
  });
});
