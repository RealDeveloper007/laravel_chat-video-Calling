<!--
 * @fileName ajax.php
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

            $url = $Base."store_video_call";
                
            $data = array('secret_token' => $_POST['token'], 'secret_id' =>  $_POST['sid'] ,'time'=>$_POST['time']);

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
                $_SESSION["room"]            = $_GET['room'];
                $_SESSION["secret_token"]    = $_POST['secret_token'];
                $_SESSION["secret_id"]       = $_POST['secret_id'];

                header("Location:comm.php?room=".$_GET['room']."&token=".$_POST['secret_token']);

            } else {


                $_SESSION["message"]         = $Rdata['message'];
                header("Refresh:0");

            }
        }

    }


?>
