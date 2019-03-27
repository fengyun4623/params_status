  <?php
    include('header.php');
  $page = "domainview";
  $pageTitle = "Domain View";
  $pageJS = [ "testbed.js" ];
  ?>
  <!-- End Header -->

<!--   <input placeholder="Filter..." type="text" 
           onpaste="onFilterChanged(this.value)"
           oninput="onFilterChanged(this.value)"
           onchange="onFilterChanged(this.value)"
           onchange="onFilterChanged(this.value)"
           onkeydown="onFilterChanged(this.value)"
           onkeyup="onFilterChanged(this.value)"/> -->
   <!--  <h3 id="page-title">Regression Testbed View</h3>
  </div> -->
  <?php
     // Date Calcuation work week today minus 7 days
        // $from_date = date('Y-m-d', strtotime("-1 week"));
        // $to_date = date('Y-m-d',strtotime("-1 days"));

        $from_date = date('Y-m-d', strtotime("-1 week"));
        $to_date = date('Y-m-d');


        // date('d.m.Y',strtotime("-1 days"));
    ?>
  <div class="content" id="testbed-div">
  <!----Start Frm and to date div- -->
        <form name="date-form" id="date-form" action=" ">
        <div class="row">
      <div class="col-md-12 col-sm-12 date-form p0">
          <div class='col-md-2 col-sm-4 col-xs-4'>
           <div class="form-group">
            <label class="mt5 mr10"> From: </label>
            <div class='input-group date' id='datetimepicker6'>
              <input type='text' class="form-control" placeholder="From" id="from-date" value="<?= $from_date; ?>"/>
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class='col-md-2 col-sm-4 col-xs-4 to-div'>
          <div class="form-group">
          <label class="mt5 mr10"> To: </label>
            <div class='input-group date' id='datetimepicker7'>
              <input type='text' class="form-control" placeholder="To" id="to-date" value="<?= $to_date;?>"/>
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class='col-md-2 col-sm-2 col-xs-2'>
          <input type="submit" class="btn btn-success" value="Go" >
        </div>
        </div>
  </div>
      </form>
    <!----End Frm and to date div- -->
    <div class='col-md-12 col-sm-12 domain-div' id="domain-div">
<!--       <div id="myGrid" style="height: 700px;" class="ag-fresh"></div>
 -->
    </div>
    <div id="recordsCount" class=""></div>
  </div>
</div>
</div>

<!-- -loader--- -->
  <div class="page-loader">
    <i class="icon close">&times;</i>
    <p class="still-working">Few more Seconds. I am working on it&hellip;</p>
  </div>
  <div class="info">
    <div class="alert alert-success" style="display:none;">
      <strong>Success!</strong> Records Saved Successfully.
    </div>
    <div class="alert alert-danger" style="display:none;">
      <strong>Failure!</strong> Please retry again.
    </div>
  </div>

<!-- Message Modal -->
<div id='messageModal' class='modal fade' role='dialog'>
          <div class='modal-dialog'>
            <!-- Modal content-->
            <div class='modal-content'>
                  <div class='modal-header' id='modal_header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title'>Message</h4>
                  </div>
                  <div class='modal-body' id='modal_body'>
                    </div>
                  <div class='modal-footer'>
                    <!-- <input type='submit' class='btn btn-outline-secondary addbutton' value='submit'> -->
                    <button type='button' class='btn btn-outline-secondary' data-dismiss='modal'>Ok</button>
                  </div>
            </div>
        </div>
</div>



  <!-- Footer -->
  <?php
  include('footer.php');
  ?>

  <script>
    // loadjscssfile("js/testbed.js", "js") //dynamically load and add this .js file
  </script>