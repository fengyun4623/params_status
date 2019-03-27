

//-------------function for generetae Table--------------
function generetaeTable(activeindex,resourceData){
     activeindex=activeindex;
    link=resourceData[activeindex]['link'];
    if(link!=""){
        hidePageLoader();
    var ifr = document.createElement('iframe');
        ifr.id='frameid';
        ifr.src =link;
        $(".tabContent").html(ifr);
        }else{

    var text="<span class='col-md-12 text-green' style='text-align: center;font-size: 23px;margin-top: 20px;'>Work In Progress<span>";
    $(".tabContent").html(text);
  }
}

// });

 url = 'json/script.json?'+Math.floor(1000 + Math.random() * 9000);
        showPageLoader();

            var request = $.ajax({
              url: url,
              type: "GET",
              dataType: "JSON"
            });

            request.success(function(resourceData) {
                hidePageLoader();
                // console.log(resourceData);
                 // -----------Loop, create and append Tabs-------------------
              var tabs='';
                $.each(resourceData, function(index, element){
                  title=element['title'];
                  tabs+='<button type="button" class=" btn tab-btn mr10" data-tab="'+index+'">'+title+'</button>';
                });
                tabs+='<div class="tab-content tabContent h100" id="tabContent"></div>';
                $('#script-wrap').append(tabs);

                $('#script-wrap .tab-btn:first').addClass('activebtn');
                activeindex=$('#script-wrap .tab-btn.activebtn').attr('data-tab');
                $("#script-wrap .tab-btn").click(function(e){
                    showPageLoader();
                     var $this = $(this);
                      activeindex=$(this).attr('data-tab');
                      $this.closest('div').find('>.activebtn').removeClass('activebtn').addClass('inactivebtn');
                      $this.addClass('activebtn');
                    generetaeTable(activeindex,resourceData);
                });
                 generetaeTable(activeindex,resourceData);
                 $('#tabContent').height(calcDataTableHeight());
            });

            request.fail(function(jqXHR, textStatus) {
              alert( "Request failed: " + textStatus );
            });

