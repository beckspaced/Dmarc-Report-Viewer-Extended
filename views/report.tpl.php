<table class='reportdata'>
<thead>
<tr>
<th>IP Address</th>
<th>Host Name</th>
<th>Message Count</th>
<th>Disposition</th>
<th>Reason</th>
<th>DKIM Domain</th>
<th>Raw DKIM Result</th>
<th>SPF Domain</th>
<th>Raw SPF Result</th>
</tr>
</thead>

<tbody>
<?php foreach ($this->reports as $row): //var_dump($row) ?>
<?php 
$status="";
if (($row['dkimresult'] == "fail") && ($row['spfresult'] == "fail")) {
	$status="red";
} elseif (($row['dkimresult'] == "fail") || ($row['spfresult'] == "fail")) {
	$status="orange";
} elseif (($row['dkimresult'] == "pass") && ($row['spfresult'] == "pass")) {
	$status="lime";
} else {
	$status="yellow";
};

if ( $row['ip'] ) {
	$ip = long2ip($row['ip']);
}
if ( $row['ip6'] ) {
	$ip = inet_ntop($row['ip6']);
}
?>

<tr class='<?php echo $status ;?>'>
<td><?php echo $ip; ?></td>
<td><?php echo gethostbyaddr($ip); ?></td>
<td><?php echo $row['rcount']; ?></td>
<td><?php echo $row['disposition']; ?></td>
<td><?php echo $row['reason']; ?></td>
<td><?php echo $row['dkimdomain']; ?></td>
<td><?php echo $row['dkimresult']; ?></td>
<td><?php echo $row['spfdomain']; ?></td>
<td><?php echo $row['spfresult']; ?></td>
</tr>

<?php endforeach; ?>
		
</tbody>
</table>
