<?php
$conn=mysql_connect("localhost",'root','')or die(mysql_error());
mysql_select_db("forexbuerau",$conn)or die(mysql_error());
?>