<?php
require("dbinfo.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

$connection=mysql_connect ($host, $username, $password);
if (!$connection) {  die('Not connected : ' . mysql_error());}

// Set the active MySQL database

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table

$query = "SELECT * FROM IRP_report_table WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while ($row = @mysql_fetch_assoc($result)){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("ID",$row['ID']);
  $newnode->setAttribute("time", $row['time']);
  $newnode->setAttribute("location", $row['location']);
  $newnode->setAttribute("event", $row['event']);
  $newnode->setAttribute("Lat", $row['Lat']);
  $newnode->setAttribute("Lng", $row['Lng']);
  $newnode->setAttribute("URL",$row['URL']);
  $newnode->setAttribute("turn_in", $row['turn_in']);
}

echo $dom->saveXML();

?>