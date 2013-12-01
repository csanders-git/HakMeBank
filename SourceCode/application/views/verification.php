<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Secure DB Federal Credit Union</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
    <div class="container">
	<div class="form-signin">
		<?php if( isset($success) ){ echo '<font color="red">Bad verify Code</font>'; } ?>
		<h2 class="form-signin-heading">Verification</h2>
		<?php $attributes = array('class'=>'form-signin', 'name'=>'login'); ?>
		<?php echo form_open('user/verification',$attributes); ?>
			Enter your verification code that was sent to your email (Check your spam folder):
			<div class="input-block-level">
				<?php $verification = array('name'=>'verification','id'=>'verification','placeholder'=>'Verification Code'); ?>
				<?php echo form_input($verification); ?>
			</div>

        		<?php echo (form_error('verification')) ? '<span class="help-inline">' . form_error('verification') . '</span>' : ''; ?>
		
			</br>
			<?php $attributes = array('class'=>'btn btn-primary','name'=>'submit','value'=>'Verify'); ?>
			<?php echo form_submit($attributes); ?>

			<?php echo form_fieldset_close(); ?>
		<?php echo form_close(); ?>
		</br>
	</div>
    </div> <!-- /container -->

  </body>
</html>
