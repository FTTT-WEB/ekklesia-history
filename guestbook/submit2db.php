<?php
//input data to db
$username = "fttt";
$password = "twfttt1000";
    $mysqlid = mysqli_connect("localhost:3306", $username, $password);
	mysqli_query("set names big5", $mysqlid);
    $date = date("Y-m-d"."  "."H:i:s");
    $origin = "\n";
    $replace = "<br>";
    $message = str_replace($origin, $replace, $message);
    $ip_address = gethostbyaddr(getenv("REMOTE_ADDR"));

	$name = $_REQUEST['name'];
	$message = $_REQUEST['message'];
if (($name!="") && ($message!=""))
{
    $query = "INSERT INTO ekklesiahistory (date,name,email,comment,ip_address) VALUES(\"".$date."\",\"".$name."\",\"".$email."\",\"".$message."\",\"".$ip_address."\")";
    $result = mysqli_query($query, $mysqlid);
    mysql_close($mysqlid);

//save a backup in the text file "record.txt"

if ($fp = fopen("record.txt","a+"))
{
$datearray = getdate(time());
fwrite($fp,date("Y-m-d"." "."H:i:s")."\n");
fwrite($fp, $name."\n");
fwrite($fp, $email."\n");
fwrite($fp, $message."\n");
fwrite($fp, "eor"."\n");
fclose($fp);
}
}


?>

<html>
<head>
<title>�d������</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background="../image/theme00_main.jpg" bgproperties="fixed" bgcolor="#ffffff">
<table width="64%" border="0" align="center">
  <tr>
    <td>
      <p align="center"><font size="+3" face="�з���" color="#009966"><b>���±z���d�����ɡI</b></font></p>
    </td>
  </tr>
</table>
<div align="center"><a href="../index.php?counter=no" target="_parent">�^����</a></div>
</body>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-11132420-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</html>
