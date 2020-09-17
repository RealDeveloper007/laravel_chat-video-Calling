<!--
 * @fileName comm.php
 * @author Real Developer
 * @date 22-Dec-2020
 */
-->
<?php
require('Variable.php');

$Variable = new Variable();

$Base =  $Variable->url();

     session_start();


     if(count($_POST)>0) 
     {
            //open connection
            $ch = curl_init();

            $url = $Base."check-url";
                
            $data = array('secret_token' => $_POST['secret_token'], 'secret_id' =>  $_POST['secret_id'],'logout'=>'no');

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
            //return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);

            $Rdata = json_decode($result, true);



        if(isset($Rdata))
        {

            if($Rdata['status'])
            {
                $_SESSION["other_user_name"] = $Rdata['data']['full_name'];
                $_SESSION["room"]            = $_GET['token'];
                $_SESSION["secret_token"]    = $_POST['secret_token'];
                $_SESSION["secret_id"]       = $_POST['secret_id'];

                header("Location:comm.php?room=".$_GET['token']."&token=".$_POST['secret_token']);

            } else {


                $_SESSION["message"]         = $Rdata['message'];
                header("Location:https://www.google.com");

            }
        }

    }


   // print_r($Rdata); die;


?>
<!DOCTYPE html>
<html lang="en">
    <head>    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chat App</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico">
        <!-- favicon ends -->
        
        <!--- LOAD FILES -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.8/font-awesome-animation.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>



        <!-- Custom styles -->
        <link rel="stylesheet" href="css/comm.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            body
            {
                overflow-x: hidden;
                background-color: #292929;
            }
            .video--box__main
            {
               width: 100%;
                max-width: 450px;
                background-color: #393939;
                border: 1px solid #353535;
                padding: 30px 20px;
                color: #fff;
                border-radius: 2px;
            }

            .video--box 
            {
                display: flex;
                height: 100vh;
                align-items: center;
                justify-content: center;
            }

            .video--box h1 
            {
                margin: 0;
                margin-bottom: 15px;
                text-align: center;
            }

            .video--box input
            {
                border-radius: 0;
                    background-color: transparent;
                 border-color: #292929;

            }

            .video--box .btn 
            {
                border-radius: 4px !important;
                background-color: #ea4956;
                border: 2px solid #ea4956;
                color: #fff;
                text-transform: uppercase;
                padding: 8px 30px;
                font-size: 14px;
                transition: all ease 0.3s;
            }

            .video--box .btn:hover
            {
                opacity: 0.7;
            }

            #message {
                color: #ea4956;
                font-size: 14px;
                font-weight: bold;
                text-align: center;

            }

        </style>

    </head>
    
    
    <body style="height: 100%">

        <div class="video--box">
            <div class="video--box__main">
                <h1> Video Calling </h1>
                <?php if(isset($_SESSION["message"])) : ?>
                     <div id="message" ><?= $_SESSION["message"] ?></div>
                <?php endif; ?>
                <form method="post" id="submit_form">
                <div class="formBox">
                    <div class="form-group">
                       <label for="password">Secret Room Key</label>
                       <label for="password"> : <?= $_GET['token'] ?></label>
                       <input type="hidden" name="secret_token" class="form-control" value="<?= $_GET['token'] ?>">
                    </div>
                    <div class="form-group">
                       <label for="password">Secret ID</label>
                       <input type="password" name="secret_id" class="form-control" value="<?= $_GET['secretid'] ?>">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-info">GO</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </body>
</html>

<script type="text/javascript">

  var Secret = "<?= $_GET['secretid'] ?>";

  $.LoadingOverlay("show") 

  if(Secret)
  {
	
    $('#submit_form').submit();

  }
    setTimeout(function() {
    $('#message').fadeOut('fast');
}, 3000); 
</script>