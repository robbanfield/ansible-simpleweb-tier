<?php 
if (($_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']) && (!empty($_POST))){ extract($_POST);} 

{% for host in groups['tag_ansible_group_dbservers'] %}
$dbServer="{{ hostvars[host].ec2_ip_address }}";
$dbName="{{ dbname }}";
$dbUser="{{ dbuser}}";
$dbPassword="{{upassword}}";
{% endfor %}
?>

<html><head><title>A very hacky interface</title></head><body>
<p><font face=Arial>
Each Page hit adds an entry to the backing db table to identify which of the load balanced servers it's coming from.
In addition, you can add a fake 'server' via the form below to augment the stats.

<?php
echo 'Welcome ' . $_SERVER["REMOTE_ADDR"] . ' to ' . $_SERVER["SERVER_ADDR"]; 

$dbcnx = @mysql_connect( $dbServer,$dbUser,$dbPassword);
mysql_select_db("testingdatabase");

$query="INSERT INTO traffic(client,server) VALUES ('". $_SERVER["REMOTE_ADDR"]."','".  $_SERVER["SERVER_ADDR"] ."')";
if (mysql_query($query)) { 
	echo "<p>Your entry was logged into the database";
} else {
	echo("<P>Error adding new record. Query error below:<br>" .
	mysql_error() . "<br>"); 
}
if ($Submit): ?>
<?php
$inssql = "INSERT INTO traffic SET " .
"client='". $_SERVER['REMOTE_ADDR'] ."', " .
"server='$server';";
if (mysql_query($inssql)) { 
	echo("<P>New Record Added.<br>");
} else {
	echo("<P>Error adding new record. Query error below:<br>" .
	mysql_error() . "<br>"); 
} 
?>
<br>Care to add a<?php if ($Submit){ echo "nother";} ?> record<br>
<? else: ?>
<br>Add a record:
<? endif; ?>
<form name="insform" method="POST" action="<? echo $_SERVER['PHP_SELF'];?>">
server - Random key data.<input name="server" type="text"><br>
<input type="Submit" value="Submit" name="Submit"></form><br>
<hr>
<h1>Basic stats</h1>
<?php
echo "Client " . $_SERVER['REMOTE_ADDR'] ." Your lookup data:";
?>
<table>
        <tr>
                <th>Count</th><th>Back End server or custom field</th>
        </tr>
<?
$query="SELECT count(*) as 'cnt',server from traffic where client='". $_SERVER['REMOTE_ADDR']."' group by server  order by cnt desc";
$res=mysql_query($query);
if (!$res) {
        echo " Error looking up stats data ". mysql_error();
} else {
        while ($row=mysql_fetch_assoc($res)) {

?>
        <tr><td><?php echo $row['cnt']?></td><td><?php echo $row['server'] ?></td></tr>
<?
        } #end while

} # end res check
?>
</th>
</table>
</font>

</body>
</html>
