<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?= base_url(); ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>2056 Partner System</title>
	<meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="index,follow" />
	<!--[if IE]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
	<link rel="Stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.1.custom.css"  />	
	<!--[if IE]>
		<style type="text/css">
		  .clearfix {
		    zoom: 1;     /* triggers hasLayout */
		    display: block;     /* resets display for IE/Win */
		    }  /* Only IE can see inside the conditional comment
		    and read this CSS rule. Don't ever use a normal HTML
		    comment inside the CC or it will close prematurely. */
		</style>
	<![endif]-->
	<!-- JavaScript -->
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
  <script type="text/javascript" src="http://inetrek.com/core/regAction.js">
  </script>
               
  <script type="text/javascript">
  Landing();
  </script>
	</head>
	 <!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.pack.js"></script><![endif]-->
</head>
<body>
<div  id="login_container">
    <div  id="header">
   
		<div id="logo" style="float: none;"><h1><a href="" style="margin: 0 auto;"></a></h1></div>
    </div><!-- end header -->
	   
	    <div id="register" class="section">
	    	<?if(validation_errors()):?><div id="fail" class="info_div"><span class="ico_cancel"></span><?= validation_errors(); ?></div><?endif;?>
	    	<form name="loginform" id="loginform" action="register" method="post">
			
			<label><strong>Логин</strong></label><input type="text" name="username" value="<?= set_value('username'); ?>" id="user_login"  size="28" class="input"/>
			<br />
			<label><strong>Имя</strong></label><input type="text" name="full_name" value="<?= set_value('full_name'); ?>" id="user_login"  size="28" class="input"/>
			<br />
			<label><strong>Пароль</strong></label><input type="password" name="password" id="user_pass"  size="28" class="input"/>
			<br />
			<label><strong>Пароль еще раз</strong></label><input type="password" name="passconf" id="user_pass_conf"  size="28" class="input"/>
			<br />
			<label><strong>Email</strong></label><input type="text" name="email" id="user_email" value="<?= set_value('email'); ?>"  size="28" class="input"/>
			<br />
		
			<input id="save" class="loginbutton" type="submit" class="submit" value="Регистрация" />
			
			</form>

	    </div>
	
	    
		    


</div><!-- end container -->

</body>
</html>
