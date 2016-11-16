function showMenu() {
    $("#menu").toggleClass("show");
}

// Close the dropdown menu if the user clicks outside of it
$(window).click(function(event) {
  if (!$(event.target).is('.dropbtn')) {

    var dropdowns = $(".dropdown-content");

    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = $(dropdowns[i]);
      if (openDropdown.hasClass('show')) {
        openDropdown.removeClass('show');
      }
    }
  }
});

$(function (){
  toastr.options.positionClass = "toast-top-center";
  toastr.options.progressBar = true;

  /*$.ajax({
    method: "GET",
    url: "api/src/index.php/config"
  }).done(function(response){
    console.log(response);
  });*/
});

function padLeft(nr, n, str) {
    return Array(n - String(nr).length + 1).join(str || '0') + nr;
}