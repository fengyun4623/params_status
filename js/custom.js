agGrid.LicenseManager.setLicenseKey("Juniper_Networks_Site_1Devs_31_October_2017__MTUwOTQwODAwMDAwMA==038f1a6883b4b4741e1d091fcf55b194");


var calcDataTableHeight = function() {
    var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    h = h-200;
    return h;
};



function loadjscssfile(filename, filetype){
    // alert(filetype);
    if (filetype=="js"){ //if filename is a external JavaScript file
        document.addEventListener('DOMContentLoaded', function() {
            $.getScript(filename);
        });
    }
    else if (filetype=="css"){ //if filename is an external CSS file

        $("<link/>", {
             rel: "stylesheet",
            type: "text/css",
            href: filename
        }).appendTo("head");
    }
    
}

$(window).resize(function(){
    $('#tabContent').height(calcDataTableHeight()-10);
    $('#frameid').height(calcDataTableHeight());
    $('.testbedcontent').height(calcDataTableHeight()-70);

})


$(function () {
// debugger;
// console.log(calcDataTableHeight());

$('#tabContent').height(calcDataTableHeight()-10);
$('#frameid').height(calcDataTableHeight());
$('.testbedcontent').height(calcDataTableHeight()-70);


// $('.navbar-nav li').click(function(){ 
//   $(this).addClass('active').siblings().removeClass('active');
// }); 

    //Add active class to submenu
    $('ul.nav-menu li:first-child').not( "#dropdownmenu li:first-child").addClass('active');
    var url = window.location;
    $('ul.nav-menu a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');

    $('ul.nav-menu a').filter(function() {
        return this.href == url;
    }).parent().prevAll('li').removeClass('active');

    $('ul#dropdownmenu a').filter(function() {
        // $('li:has(ul.dropdown-menu)').toggleClass('active');
        return this.href == url;
    }).parent().parent().parent().prevAll('li').not( ".dropdown").removeClass('active');

    // dropdown parent active
    $('li.dropdown a').filter(function(){
        return this.href== url;
    }).closest('ul').parent().addClass('active');
  

  // Show hide popover
   // $(".dropdown > a").click(function(e){
   //      e.preventDefault();
   //      $(this).parent('li').find(".dropdown-menu").slideToggle("fast");
   //  });

    //Datepicker Function
    $('#datetimepicker6').datetimepicker({
                format: "YYYY-MM-DD" 
    });
    $('#datetimepicker7').datetimepicker({
                format: "YYYY-MM-DD" ,
                useCurrent: false, //Important! See issue #1075
                // daysOfWeekDisabled: [0],
                ignoreReadonly: true,
                maxDate: 'now'
            });
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });

});



$(document).ready(function() {
      $("#submitFeed").click(function() {
        var url = window.location.href;
        var message = $("#messagefeed").val();
        message = message.replace(/\r?\n/g, '<br />');
        var email = uid;
        console.log(email);
        email += '@juniper.net' ;
        $("#returnmessage").empty();
        // Checking for blank fields.
        if (message ==='') {
         $("#messagefeed").addClass('emptyfeed');
        } 
        else {
        // Returns successful data submission message when the entered information is stored in database.
        $.post("contact_form.php", {
          url1: url,
          email1: email,
          message1: message,
        }, function(data){ 
          $("#returnmessage").append(data);
          if (data == "Your Feedback has been received, We will contact you soon.") {
        $("#formfeed")[0].reset(); // To reset form fields on success.
        }
        });
        } 
        });
      $(".modal").on("hidden.bs.modal", function(){
        $("#returnmessage").html("");
        $("#messagefeed").removeClass('emptyfeed');
      });
      $("#feedhover").attr('title', 'Click to Give feedback on current active page');
    });
 


