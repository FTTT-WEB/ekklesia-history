<?php
//connect to the db
$hostname = "localhost";
$username = "fttt";
$password = "twfttt1000";
    $mysqlid = mysqli_connect("localhost:3306", $username, $password);
	mysqli_query("set names big5", $mysqlid);
    $query = "SELECT * FROM ekklesiahistory where status=\"true\" order by date DESC";
    $result = mysqli_query($query, $mysqlid);
    $num=mysql_num_rows($result);
    mysql_close($mysqlid);
?>
<html>
<head>
<title>�[�ݯd��</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<style type="text/css">
a:link{text-decoration: none; color: #003399}
a:active{text-decoration: none; color: #003399}
a:visited{text-decoration: none; color: #003399}
a:hover{text-decoration: underline; color: #FF0000}
</style></head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="780" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td background="../images/page/b5_2.gif" height="26" width="156">
      <div align="center"><font face="�з���">�[�ݯd��</font></div>
    </td>
    <td background="../images/page/b5_1.gif" height="26" width="156">
      <div align="center"><font face="�з���"><a href="guestbook.php">�d��</a></font></div>
    </td>

    <td height="26" width="156">&nbsp;</td>

    <td height="26" width="156">&nbsp;</td>
			    <td  height="26" width="156">&nbsp;</td>
  </tr>
</table>
<table width="780" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td background="../images/page/b1.gif" height="13"></td>
  </tr>
</table>
<table width="780" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td background="../images/page/b.gif" >
      <table width="740" border="4" align="center" bordercolor="#FFCC66" bgcolor="#FFFFFF">
        <tr>
          <td>
<div align="center">
<p>&nbsp;</p>
<?php
  //dividing into pages
  $msg_per_page = 10;
  $no_of_page = ceil($num / $msg_per_page);
  $remain = $num % $msg_per_page;
?>
<table width=500 border=0 cellspacing=0 cellpadding=0 align=center><tr><td colspan=2 align=center >
</td></tr><tr bgcolor=247488 height=30>
                  <td bgcolor="#FFCC66"><font size=2 color=#FFFFFF>�@��<?php echo $num?> �g�d��</font></td>
                  <td align=right bgcolor="#FFCC66">&nbsp;
<?php
//prev page , page no, next page
if ($no > 1)
{
    $prev = $no-1;
    echo "<a href=index.php?no=".$prev.">";
    echo "<font size=2 color=#FFFFFF>�W�@��</font></a><";
}
else
{
    echo "<font size=2 color=#FFFFFF>�W�@��</font><";
}

 for ($j=1; $j<=$no_of_page; $j++)
   {
      echo "<a href=index.php?no=".$j.">".$j."</a>"." ";
   }

if ($no < $no_of_page)
{
    $next = $no+1;
    echo "><a href=index.php?no=".$next.">";
    echo "<font size=2 color=#FFFFFF>�U�@��</font></a>";
}
else
{
    echo "><font size=2 color=#FFFFFF>�U�@��</font>";
}
?>
&nbsp;&nbsp;</td>
                </tr>

				<tr height=2><td colspan=2></td></tr><tr  height=5><td colspan=2></td></tr>
</table>

<?php
//default $i is zero
if ($no>1)
  $i= ($no-1)*$msg_per_page;
else
  $i = 0;
for ($k=0; $k<$msg_per_page; $k++)
{
  if ($i<$num)
  {
   $name=mysql_result($result,$i,"name");
   $date=mysql_result($result,$i,"date");
   $email=mysql_result($result,$i,"email");
   $comment=mysql_result($result,$i,"comment");
   $response=mysql_result($result,$i,"response");
//echo "<tr><td>Date : ".$date."</td></tr>";
//echo "<tr><td>Name : ".$name."</td></tr>";
//echo "<tr><td>Command : ".$command."</td></tr>";
//echo "<tr><td><hr></td></tr>";
?>
<table bgcolor=#B9DCFF width=500 align=center border=0 cellpadding=0 cellspacing=3>
<?php  if ($email!="")
    {
?>
                <tr>
                  <td><b>[<?php echo $i+1?>]</b>�m�W�G<a href="mailto:<?php echo $email?>"><?php echo $name?></a></td>
<?php  }
    else
    {
?>
                <tr>
                  <td><b>[<?php echo $i+1?>]</b>�m�W�G<?php echo $name?></td>
<?php  }?>

                <tr>
                  <td><?php echo $comment?></td>
<?php
    if ($response!="")
    {
?>
                <tr>
                  <td>
                  ------------------------------<br>
                  <font color="#FF0000">
                  <?php echo $response?>
                  </font>
                  </td>
<?php   }?>
                <tr>
                  <td align=right><font face=arial size=-2><?php echo $date?></font></td>
				  <tr height=2><td colspan=2></td></tr><tr  height=5><td colspan=2></td></tr>
</table>
<?php
   ++$i;
  }
}
?>
<table width=500 border=0 align=center><tr height=5><td></td></tr><tr  height=30>
<td align=right bgcolor="#FFCC66">
<?php
if ($no > 1)
{
    $prev = $no-1;
    echo "<a href=index.php?no=".$prev.">";
    echo "<font size=2 color=#FFFFFF>�W�@��</font></a><";
}
else
{
    echo "<font size=2 color=#FFFFFF>�W�@��</font><";
}

 for ($j=1; $j<=$no_of_page; $j++)
   {
      echo "<a href=index.php?no=".$j.">".$j."</a>"." ";
   }

if ($no < $no_of_page)
{
    $next = $no+1;
    echo "><a href=index.php?no=".$next.">";
    echo "<font size=2 color=#FFFFFF>�U�@��</font></a>";
}
else
{
    echo "><font size=2 color=#FFFFFF>�U�@��</font>";
}
?>

&nbsp;&nbsp;</td>
</table>
              <p align="center"><a href="../index.html" target="_parent">�^����</a> </p>
</div>
        </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="780" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td background="../images/page/b2.gif" height="13"></td>
  </tr>
</table>
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
