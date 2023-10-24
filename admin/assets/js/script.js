function openImageModal(imageUrl) {
  var modal = document.getElementById("imageModal");
  var modalImg = document.getElementById("imageContent");
  modal.style.display = "block";
  modalImg.src = imageUrl;
}

function openDocumentModal(docUrl) {
  var modal = document.getElementById("documentModal");
  var modalDoc = document.getElementById("documentContent");
  modal.style.display = "block";
  modalDoc.src = docUrl; // Set the src attribute to the PDF file URL

  // Open the modal with an iframe displaying the PDF
}

// Close the modal when the "x" is clicked
document.getElementById("documentClose").addEventListener("click", function () {
  document.getElementById("documentModal").style.display = "none";
});

// Close the modal when the "x" is clicked
document.getElementById("imageClose").addEventListener("click", function () {
  document.getElementById("imageModal").style.display = "none";
});

// document.getElementById("documentClose").addEventListener("click", function () {
//   document.getElementById("documentModal").style.display = "none";
// });

// NOTIFICATIONS -------------------------%%%%%%%%%%%%%%%%%%%%
{
  /* <script> */
}
// Function to hide notifications after 3 seconds
function hideNotifications() {
  setTimeout(function () {
    var notifications = document.querySelector(".alert");
    if (notifications) {
      notifications.style.display = "none";
    }
  }, 3000); // 3 seconds (3000 milliseconds)
}

// Call the hideNotifications function when the page loads
window.onload = function () {
  hideNotifications();
};
// </script>
