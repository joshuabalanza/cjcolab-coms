$("#notificationsModal").on("hidden.bs.modal", function () {
  $(this).find(".modal-body").html(""); // Clear the modal content when closed
});
