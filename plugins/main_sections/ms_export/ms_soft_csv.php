<?php 
//====================================================================================
// OCS INVENTORY REPORTS
// Copyleft Pierre LEMMET 2005
// Web: http://ocsinventory.sourceforge.net
//
// This code is open source and may be copied and modified as long as the source
// code is always made freely available.
// Please refer to the General Public Licence http://www.gnu.org/ or Licence.txt
//====================================================================================
//Modified on $Date: 2007/02/08 16:59:15 $$Author: plemmet $($Revision: 1.9 $)

if( isset($_SESSION["forcedRequest"] )) {
	$lareq = $_SESSION["forcedRequest"];
}
else
	die();//$lareq = $_SESSION["storedRequest"]->getFullRequest();

$lareq = str_replace("h.id AS \"h.id\",","",$lareq);
$lareq = str_replace("deviceid AS \"deviceid\",","",$lareq);
$lareq = str_replace("h.n.ipmask","n.ipmask",$lareq);

//echo $lareq;die();
$result=mysql_query($lareq, $_SESSION["readServer"]) or die(mysql_error($_SESSION["readServer"]));
//echo "requete:".$lareq;
// iexplorer problem
if( ini_get("zlib.output-compression"))
	ini_set("zlib.output-compression","Off");
	
header("Pragma: public");
header("Expires: 0");
header("Cache-control: must-revalidate, post-check=0, pre-check=0");
header("Cache-control: private", false);
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=\"export.csv\"");
header("Content-Transfer-Encoding: binary");

$toBeWritten = "";

while( $colname = mysql_fetch_field($result) ) {
	$toBeWritten .=$colname->name.";";
	$cols[] = $colname->name;
}
$toBeWritten .="\r\n";
//writeTab($fp,$cols);

while( $cont = mysql_fetch_array($result,MYSQL_ASSOC) ) {
	foreach ($cols as $k=>$v){
		$toBeWritten.=$cont[$v].";";
	}
	$toBeWritten .="\r\n";
	//writeTab($fp,$cont,$nameIndex);
}

header("Content-Length: ".strlen($toBeWritten));
echo $toBeWritten;

?>