<html>
    <head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel = "stylesheet"   type = "text/css"   href = "styles/cssfile.css" />
<!------ Include the above in your HEAD tag ---------->
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <div class="fadeIn first">
      <img src="images/MAQSoftware.png" id="icon" alt="User Icon" />
    </div>
    <form method="post" action="?action=get" enctype="multipart/form-data">
      <input type="text" id="login" class="fadeIn second" name="login" placeholder="Login">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="Password">
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>
    <div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div>
    
  </div>
</div>
</body>
</html>
<?php
         $serverName = "nodedata.database.windows.net,1433";
         $connectionOptions = array("Database"=>"nodedata",
                                    "UID"=>getenv("siteusername"),
                                    "PWD"=>getenv("password"));
         $conn = sqlsrv_connect($serverName, $connectionOptions);
         if($conn === false)
         {
             die(print_r(sqlsrv_errors(), true));
         }
         if(isset($_GET['action']))
         {
            if($_GET['action'] == 'get')
            {
                $Userid=$_POST['login'];
                $Password=$_POST['password'];
                $sql ="DECLARE	@responseMessage nvarchar(250)
                        EXEC	dbo.uspLogin
		                @pLoginName = ?,
		                @pPassword = ?,
		                @responseMessage = @responseMessage OUTPUT
                        SELECT	@responseMessage as 'Message'";
                $params = array(&$Userid,&$Password);
                $dataStmt = sqlsrv_query($conn,$sql,$params);
                if($dataStmt === false)
                {
                     die(print_r(sqlsrv_errors(), true));
                }
                if(sqlsrv_has_rows($dataStmt))
                {
                    while($row = sqlsrv_fetch_array($dataStmt))
                    {
                        if($row['Message']=="User successfully logged in")     
                        {
                            echo "<script>alert('Successfully Logged in')</script>";
                        }
                        else
                        {
                            echo "<script>alert('Authentication unsuccessful')</script>";
                        }
                    }
                }
            }
         }
?>