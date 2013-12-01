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
    
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>

	<script>
		$(document).ready(function() {
			$('#balance').load('/account/getBalance', function() { console.log('ajax call success') });
		});
	</script>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">SDBFCU</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/account">My Account</a></li>
              <li class="active"><a href="/transfer">Transfer Funds</a></li>
            </ul>
            <ul class="nav pull-right">
            	<li><a href="/user/logout">Log out</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">      
      <div class="well">
		<h1>Hello, Welcome to SDBFCU</h1>
		<p>Current account balance:</p>
			
	      <div id="balance"></div>
		<?php
		 if($manager){
			echo "<a href='/manager'>Manager</a>";	
		}
		?>
      </div>
      <?php
	     	if(isset($Success)){
		echo "<font color='red'>Transfer succeeded </font> </br></br>"; 
	}
      ?>  
      <div>
     	<?php echo $table; ?>
      </div>
      
      
    </div> <!-- /container -->

  </body>
</html>
