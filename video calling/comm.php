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
/*
ini_set('display_errors', -1);
ini_set('display_startup_errors', -1);
error_reporting(-1);
*/


     if(!isset($_SESSION['other_user_name']))
     {

        header("Location:https://www.google.com");

     } else {


         $ch = curl_init();

            $url = $Base."check-url";

            $data = array('secret_token' => $_SESSION['secret_token'], 'secret_id' =>  $_SESSION['secret_id'],'logout'=>'no');

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
            if(!$Rdata['status'])
            {
                header("Location:https://www.google.com");
            }
        } else {
            
                header("Location:https://www.google.com");

        }

     }


      if($_POST['logout'])
    {


            $ch = curl_init();

            $url = "http://domain.com/api_blu/api/check-url";

            $data = array('secret_token' => $_SESSION['secret_token'], 'secret_id' =>  $_SESSION['secret_id'],'logout'=>'yes');

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


            unset($_SESSION["other_user_name"]);
            unset($_SESSION["room"]);
            unset($_SESSION["secret_token"]);
            unset($_SESSION["secret_id"]);

            header("Location:https://www.google.com");

    }

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
              <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" rel="stylesheet" id="theme" />


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

           <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>


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
                position: relative;
            }

            .video__back .progress1
            {
                margin: 12px 0 10px;
            }

            .video__back .progress1 progress
            {
                    width: 100%;
                padding: 10px;
                opacity: 0.6;
            }

            .video__back .progress1
            {
                margin-top: 0px;
            }

            .video__back .counter__video
            {
                font-size: 15px;
                color:#fff;
                margin-top: 4px;
            }

            #callBtns form
            {
                    margin-top: 6px;
                position: absolute;
                right: 15px;
                top: 0;
            }

            #rcivModal .modal-content img
            {
                height: 100px;
                object-fit: cover;
                margin: 10px auto 0;
                display: block;
            }
        </style>
    </head>
    
    
    <body onbeforeunload=" return 'Are you really want to perform the action?'">
        <div class="container-fluid video__back">
            <div class="row">
                <!-- Remote Video -->
                <video id="peerVid" poster="img/vidbg.png" playsinline autoplay></video>
                <!-- Remote Video -->
            </div>
            
            <div class="row margin-top-20">  
             <!-- Timer -->
                <div class="col-sm-4 col-md-offset-4 text-center margin-top-5" style="color:#fff">
                <?php if($Rdata['data']['user_type_id']==2) { ?>

   
                <div class="progress1">
                        <progress value="0" max="<?= $Rdata['data']['minute_balance'] ?>" id="progressBar"></progress>
                </div>

                   
                                    <?php } ?>
                   
                </div>
                <!-- Timer -->

                <!-- Call Buttons -->
                <div class="col-sm-12 text-center" id="callBtns">
                    <!-- <button class="btn btn-success btn-sm initCall" id="initAudio"><i class="fa fa-phone"></i></button> -->
                    <?php if($Rdata['data']['user_type_id']==1) { ?>
                    <button class="btn btn-info btn-sm initCall" id="initVideo"><i class="fa fa-video-camera"></i></button>
                    <button class="btn btn-danger btn-sm" id="terminateCall" disabled><i class="fa fa-phone-square"></i></button>
                    <?php } ?>
                      <form method="post">
                       <input type="hidden" name="logout" value="logout">
                         <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure logout this session?')"><i class="fa fa-sign-in"></i> Logout</button>
                          <div class="counter__video">
                      <i class="fa fa-clock-o"></i>
                      <span id="countHr"></span>h:
                      <span id="countMin"></span>m:
                      <span id="countSec"></span>s
                    </div>
                    </form>
                </div>
                <!-- Call Buttons -->
                
               
            </div>
            
            
            <!-- Local Video -->
            <div class="row">
                <div class="col-sm-12">
                    <video id="myVid" poster="img/vidbg.png" muted autoplay></video>
                </div>
            </div>
            <!-- Local Video -->
        </div>

        <div class="container-fluid chat-pane">
            <!-- CHAT PANEL-->
            <div class="row chat-window col-xs-12 col-md-4">
                <div class="">
                    <div class="panel panel-default chat-pane-panel">
                        <div class="panel-heading chat-pane-top-bar">
                            <div class="col-xs-10" style="margin-left:-20px">
                                <i class="fa fa-comment" id="remoteStatus"></i> <?= $_SESSION["other_user_name"] ?>
                                <b id="remoteStatusTxt">(Offline)</b>
                            </div>
                            <div class="col-xs-2 pull-right">
                                <span id="minim_chat_window" class="panel-collapsed fa fa-plus icon_minim pointer"></span>
                            </div>
                        </div>
                        
                        <div class="panel-body msg_container_base" id="chats"></div>
                        
                        <div class="panel-footer">
                            <span id="typingInfo"></span>
                            <div class="input-group">
                                <input id="chatInput" type="text" class="form-control input-sm chat_input" placeholder="Type message here...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" id="chatSendBtn">Send</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CHAT PANEL -->
        </div>


         <!--Modal to show that we are calling-->
        <div id="callModal" class="modal">
            <div class="modal-content text-center">
                <div class="modal-header" id="callerInfo">Video Call to <?= ucfirst($Rdata['data']['full_name']) ?></div>

                <img src="https://www.euresodb.it/blu_calling/img/<?= $Rdata['data']['avatar']=='' ? 'default.png' : $Rdata['data']['avatar'] ?>" class="img-circle" style="width: 100px;"/>

                <div class="modal-body">
                    <button type="button" class="btn btn-danger btn-sm" id='endCall'>
                        <i class="fa fa-times-circle"></i> End Call
                    </button>
                </div>
            </div>
        </div>
        <!--Modal end-->


        <!--Modal to give options to receive call-->
        <div id="rcivModal" class="modal">
            <div class="modal-content">
                <div class="modal-header" id="calleeInfo">Video Call from <?= ucfirst($Rdata['data']['full_name']) ?></div>
                    <img src="https://www.euresodb.it/blu_calling/img/<?= $Rdata['data']['avatar']=='' ? 'default.png' : $Rdata['data']['avatar'] ?>" class="img-circle" style="width: 100px;"/>
                    <div class="clearfix"></div>

                <div class="modal-body text-center">
                    <!-- <button type="button" class="btn btn-success btn-sm answerCall" id='startAudio'>
                        <i class="fa fa-phone"></i> Audio Call
                    </button> -->
                    <button type="button" class="btn btn-success btn-sm answerCall" id='startVideo'>
                        <i class="fa fa-video-camera"></i> Video Call
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id='rejectCall'>
                        <i class="fa fa-times-circle"></i> Reject Call
                    </button>
                </div>
            </div>
        </div>
        <!--Modal end-->
        
        <!--Snackbar -->
        <div id="snackbar"></div>
        <!-- Snackbar -->

        <!-- custom js -->
        <script>
        var Remoteusername = "<?= $Rdata['data']['full_name'] ?>";
        var secret_token = "<?= $_SESSION['secret_token'] ?>";
        var secret_id = "<?= $_SESSION['secret_id'] ?>";
        var token_balance = "<?= $Rdata['data']['token_balance'] ?>";
        var minute_balance = "<?= $Rdata['data']['minute_balance'] ?>";
        </script>
        <script src="js/config.js"></script>
        <script src="js/adapter.js"></script>
        <script src="js/comm.js"></script>
        <audio id="callerTone" src="media/callertone.mp3" loop preload="auto"></audio>
        <audio id="msgTone" src="media/msgtone.mp3" preload="auto"></audio>
    </body>
</html>




        <script type="text/javascript">
            
             var hour = $('#countHr').text();
                var min  = $('#countMin').text();
                var sec  = $('#countSec').text();

        var totaltime = parseInt(hour) + parseInt(min) + parseInt(sec);

         if(totaltime>0)
         {

            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: { 'time' : hour+':'+min+':'+sec,'token':secret_token,'sid':secret_id },
                success: function(result) { },
                async: true // <-- make the call synchronous 
              });
        }
        </script>