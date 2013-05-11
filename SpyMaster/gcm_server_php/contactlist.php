<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Spy Phone Contact List</title>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
        <script>
            var tableToExcel = (function() {
                var uri = 'data:application/vnd.ms-excel;base64,'
                        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                        , base64 = function(s) {
                    return window.btoa(unescape(encodeURIComponent(s)))
                }
                , format = function(s, c) {
                    return s.replace(/{(\w+)}/g, function(m, p) {
                        return c[p];
                    })
                }
                return function(table, name) {
                    if (!table.nodeType)
                        table = document.getElementById(table)
                    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                    window.location.href = uri + base64(format(template, ctx))
                }
            })()
        </script>
        <style>
            body {background-image:url('bootstrap/img/Spymaster.jpg');background-repeat: no-repeat;background-position:right top;}
            .center{
                position: relative;
                height: 200px;
                width: 400px;
                margin: -100px 0 0 -200px;
                top: 50%;
                left: 50%;

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
        <a href="index1.php"><button class="btn btn-primary ">Home</button></a>
        <a href="phonelogs.php"><button class="btn btn-primary ">View Phone Logs </button></a>
        <a href="smslogs.php"><button class="btn btn-primary ">View SMS Logs </button></a>
        <input type="button " class="btn btn-primary " onclick="tableToExcel('testTable', 'Spy Contact List')" value="Export Contact list to Excel">
         <a href="Login.html"><button class="btn btn-warning ">Logout </button></a>
        <br/>
        <?php
        /*
         * Make a connection to the database to reterieve Contact for display
         */

        include_once 'db_functions_p.php';
        $db = new DB_Functions_p();
        $result = $db->getAllContactRecords($_SESSION['phone']);
        if ($result != false)
            $no_of_records = mysql_num_rows($result);
        else
            $no_of_records = 0;
        ?>
        <br/>
        <span class="label label-important">Total Contact records found <?php echo $no_of_records ?></span>
        <fieldset>

            <?php
            if ($no_of_records > 0) {
                $i = 0;
                ?>
                <table id ="testTable" class="table table-bordered " style="display: none;" >
                    <tr class="success">
                        <th>Phone Number</th>
                        <th>Contact Name </th>
                        <th>Contact Email</th>

                        <?php
                        while ($row = mysql_fetch_array($result)) {
                            $i++;
                            ?>
                        <dl class="dl-horizontal">
                            <dt><span class="label label-warning">Record Number </span></dt>
                            <dd><?php echo $i ?></dd>
                            <dt><span class="label label-warning">Phone Number </span></dt>
                            <dd><?php echo $row["phonenumber"] ?></dd>
                            <dt><span class="label label-warning">Contact Name</span></dt>
                            <dd><?php echo $row["contactname"] ?></dd>
                            <dt><span class="label label-warning">Call Type </span></dt>
                            <dd><?php echo $row["contactemail"] ?></dd>
                        </dl>
                        <tr>
                            <td><?php echo $row["phonenumber"] ?></td>
                            <td><?php echo $row["contactname"] ?></td>
                            <td><?php echo $row["contactemail"] ?></td>
                        </tr>

                    <?php }
                    ?>
                </table>
            <?php } else {
                ?>
                <li>
                    No records found as yet ! Try sending our Spy message to your phone !
                </li>
            <?php } ?>
        </fieldset>

    </body>
</html>