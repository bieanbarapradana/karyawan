<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/simple_1.1/dark/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Jan 2017 05:14:31 GMT -->
<head>
        <meta charset="utf-8" />
        <title>SimpleAdmin - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="<?=base_url('assets/back-end/images/favicon.ico');?>">

        <!-- Bootstrap core CSS -->
        <link href="<?=base_url('assets/back-end/css/bootstrap.min.css');?>" rel="stylesheet">
        <!-- MetisMenu CSS -->
        <link href="<?=base_url('assets/back-end/css/metisMenu.min.css');?>" rel="stylesheet">
        <!-- Icons CSS -->
        <link href="<?=base_url('assets/back-end/css/icons.css');?>" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?=base_url('assets/back-end/css/style.css');?>" rel="stylesheet">

        <style>
      #loader{
        background: url('assets/back-end/img/rainbow.gif') 100%;
        height: 2px;
      }
    </style>

    </head>


    <body>
      <!-- LOADER -->
<div id="loader"></div>
        <!-- HOME -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="m-t-40 card-box">
                                <div class="text-center">
                                    <h2 class="text-uppercase m-t-0 m-b-30">
                                        <a href="index.html" class="text-success">
                                            <span><img src="<?=base_url('assets/back-end/images/logo_dark.png');?>" alt="" height="30"></span>
                                        </a>
                                    </h2>
                                    <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                </div>
                                <div class="account-content">
                                    <form class="form-horizontal" action="#">

                                        <div class="form-group m-b-20">
                                            <div class="col-xs-12">
                                                <label for="emailaddress">Username</label>
                                                <input class="form-control" type="text" id="username" required="" name="username" placeholder="john@deo.com">
                                            </div>
                                        </div>

                                        <div class="form-group m-b-20">
                                            <div class="col-xs-12">
                                                <a href="pages-forget-password.html" class="text-muted pull-right font-14">Forgot your password?</a>
                                                <label for="password">Password</label>
                                                <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                                            </div>
                                        </div>



                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-xs-12">
                                                <button class="btn btn-lg btn-primary btn-block" type="submit" onclick="executeAjax()">Sign In</button>
                                            </div>
                                        </div>

                                    </form>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->


                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Don't have an account? <a href="pages-register.html" class="text-dark m-l-5">Sign Up</a></p>
                                </div>
                            </div>

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
        </section>
        <!-- END HOME -->



        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?=base_url('assets/back-end/js/jquery-2.1.4.min.js');?>"></script>
        <script src="<?=base_url('assets/back-end/js/bootstrap.min.js');?>"></script>
        <script src="<?=base_url('assets/back-end/js/metisMenu.min.js');?>"></script>
        <script src="<?=base_url('assets/back-end/js/jquery.slimscroll.min.js');?>"></script>

        <!-- App Js -->
        <script src="<?=base_url('assets/back-end/js/jquery.app.js');?>"></script>

    </body>

<!-- Mirrored from coderthemes.com/simple_1.1/dark/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Jan 2017 05:14:32 GMT -->
</html>

<script type="text/javascript">
var base_url = '<?php echo base_url();?>';
$(document).ready(function() {
function executeAjax(time) {

     // Start progress bar initialization


     $(".loader").animate({ width: width + "100%" }, time);

     // Execute ajax call
     $.ajax({
       url: "<?php echo site_url('karyawan/Auth/login')?>",
       method: 'POST',
       dataType: 'JSON',
       beforeSend: function()
        { // Stop previous animation and start a new one with 0.5 sec duration
          $('#loader').stop().animate({ width: width + "100%" }, 500)
                    .queue(function() {
                       // Handle the response data here
                       $(this).dequeue();
                    });
        },
       complete: function()
        { $('#loading').hide();
        },
       success: function(data)
       {
          window.location.href='<?php echo base_url() ?>Karyawan/';
       },
       error: function(jqXHR, errorType) {
          alert('Error: ' + errorType);
       },
       timeout: time
     });
}
});

// Call the function passing a timeout of 10 seconds

</script>
