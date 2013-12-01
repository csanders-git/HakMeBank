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
        max-width: 400px;
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
		<?php if( isset($failure) ){ echo "<font color='red'>There was a problem creating your account, please try again. Sorry for the inconvenience</font>"; } ?>
	        <h2 class="form-signin-heading">Welcome To Registration</h2>
		<?php $attributes = array('class'=>'form-signin', 'name'=>'login'); ?>
		<?php echo form_open('register/registerUser',$attributes); ?>



			<div class="input-block-level">
				<?php echo form_label('First Name', 'fname'); ?>
				<?php $fname = array('name'=>'fname', 'id'=>'fname','placeholder'=>'First Name'); ?>
				<?php echo form_input($fname); ?>
	        		<?php echo (form_error('fname')) ? '<span class="help-inline">' . form_error('fname') . '</span>' : ''; ?>
			</div>



			<div class="input-block-level">
				<?php echo form_label('Last Name', 'lname'); ?>
				<?php $lname = array('name'=>'lname', 'id'=>'lname','placeholder'=>'Last Name'); ?>

				<?php echo form_input($lname); ?>
	        		<?php echo (form_error('lname')) ? '<span class="help-inline">' . form_error('lname') . '</span>' : ''; ?>
			</div>

			
			<div class="input-block-level">
				<?php echo form_label('Username','username'); ?>
				<?php $username = array('name'=>'username','id'=>'username','placeholder'=>'Username'); ?>
				<?php echo form_input($username); ?>
	        		<?php echo (form_error('username')) ? '<span class="help-inline">' . form_error('username') . '</span>' : ''; ?>
			</div>


			<div class="input-block-level">
				
				<?php echo form_label('Password','password'); ?>
				<?php $password = array('name'=>'password','id'=>'password','placeholder'=>'Password'); ?>
				<?php echo form_password($password); ?>
				<?php echo (form_error('password')) ? '<span class="help-inline">' . form_error('password') . '</span>' : ''; ?>
			</div>

			<div class="input-block-level">	
				<?php echo form_label('Password Confirm','password_confirm'); ?>
				<?php $password_confirm = array('name'=>'password_confirm','id'=>'password_confirm','placeholder'=>'Password_confirm'); ?>
				<?php echo form_password($password_confirm); ?>
				<?php echo (form_error('password_confirm')) ? '<span class="help-inline">' . form_error('password_confirm') . '</span>' : ''; ?>			</div>


			<div class="input-block-level">
				<?php echo form_label('E-mail Address','email'); ?>
				<?php $email = array('name'=>'email','id'=>'email','placeholder'=>'Email'); ?>
				<?php echo form_input($email); ?>
        			<?php echo (form_error('email')) ? '<span class="help-inline">' . form_error('email') . '</span>' : ''; ?>
			</div>

		
			<?php $attributes = array('class'=>'btn btn-primary','name'=>'submit','value'=>'Register'); ?>
			<?php echo form_submit($attributes); ?>

			<?php echo form_fieldset_close(); ?>
		<?php echo form_close(); ?>
	</div>
    </div> <!-- /container -->

  </body>
</html>
