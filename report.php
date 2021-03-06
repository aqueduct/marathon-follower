<?php
include 'setup.php';
include 'opendb.php';

$runName = trim($_GET['name']);
$startDate = trim($_GET['date']);

if (!empty($runName)) {

	// Performing SQL query

	
	$query = sprintf("SELECT Id, Lat, Lon, Speed, Heading, Created FROM Locations WHERE RunName = '%s'", mysql_real_escape_string($runName));
	if (!empty($startDate))
	{
		$query = $query . sprintf(" AND Created > '%s' AND Created < '%s'", 
					    mysql_real_escape_string(date("Y-m-d", strtotime($startDate))),
					     mysql_real_escape_string(date("Y-m-d", strtotime($startDate . " +1 day"))));
	
	}
	$query = $query . ' ORDER BY Id DESC';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	echo "<h1>Locations for " . htmlentities($runName) . "</h1>";
	// Printing results in HTML
	echo "<table>\n";
	echo "<tr><th>Id</th><th>Lat</th><th>Lon</th><th>Speed</th><th>Heading</th><th>Created</th></tr>";
	$values = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	    echo "\t<tr>\n";
	    foreach ($line as $col_value) {
		echo "\t\t<td>$col_value</td>\n";
	    }
	    echo "\t</tr>\n";
		$values[] = $line;
	}
	echo "</table>\n";
	echo "<br/><a href='?'>Back</a>\n";
	// Free resultset
	mysql_free_result($result);
}
else
{
 	// Performing SQL query
	$query = "SELECT DISTINCT RunName FROM Locations";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	echo "<h1>Available runs</h1>";
	
	// Printing results in HTML
	echo "<ul>\n";
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$name = $line['RunName'];
		echo "\t\t<li><a href='?name=$name'>$name</a></li>\n";
	}
	echo "</ul>\n";

	// Free resultset
	mysql_free_result($result);
}
include 'closedb.php';
?>
