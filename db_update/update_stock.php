<?php 
$con=mysql_connect("localhost","root","");
mysql_select_db("power_db",$con);
mysql_query("SET CHARACTER SET utf8");

$qry=mysql_query("SELECT * from collateral_tbl");
while($row=mysql_fetch_array($qry))
{
	echo  $amt=$row["loan_amt"];
	echo $voucher=$row["voucher"];

	mysql_query("UPDATE collateral_stock_tbl SET loan_amt='$amt' WHERE voucher='$voucher'");
}
?>