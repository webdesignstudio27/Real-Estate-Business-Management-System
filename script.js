
function toggleCloseIcon() {
    let searchBox = document.getElementById("search-box");
    let closeIcon = document.getElementById("close-icon");
  
    if (searchBox.value.length > 0) {
        closeIcon.style.display = "block"; // Show close icon when typing
    } else {
        closeIcon.style.display = "none"; // Hide if empty
    }
  }
  
  function clearSearch() {
    document.getElementById("search-box").value = ""; // Clear input
    document.getElementById("close-icon").style.display = "none"; // Hide close icon
  }