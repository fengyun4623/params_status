        <script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
        
	<!script type="text/javascript" src="js/jquery.min.js"/>
        <script type="text/javascript" src="js/angular.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="js/common.js"></script>
        <!-- Ag-grid js -->
        
	<script src="js/agrid/ag-grid-enterprise.min.js"></script>
        <!-- Pages Js -->
        <script type="text/javascript" src="js/inc.js"></script>
        <!-- datepicker js -->
	  <script src="js/jquery.min.js.1"></script>
  <script src="js/bootstrap.min.js.1"></script>
	<script type="text/javascript" src="js/datepicker/moment.min.js"></script>
        <!script type="text/javascript" src="js/datepicker/moment-with-locales.js"/>
        <!script type="text/javascript" src="js/datepicker/bootstrap-datetimepicker.js"/>
	<script type="text/javascript" src="js/datepicker/daterangepicker.min.js"></script>
        
	<script type="text/javascript">
            // Required to pass the user for "refresh-btn"
            uid = '<?php echo $uid; ?>';
            $(document).ready(function(){
                var uid = '<?php echo $uid; ?>';
                setCookie('uid', uid);
            });
        </script>

        <?php
        if( isset($pageJS) ){
            foreach ($pageJS as $key => $value) {
                echo '<script type="text/javascript" src="js/'.$value.'?'.filemtime('js/'.$value).'"></script>';
            }
        }
        ?>
    </body>
    </html>
