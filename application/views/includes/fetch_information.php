<table>
<?php
while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
$id = $postsfetch['id'];
$post_id = $postsfetch['post_id'];
$user_id = $postsfetch['user_id'];
$exchange = $postsfetch['exchange'];
$received_name = $postsfetch['received_name'];
$received = $postsfetch['received'];
$given_name = $postsfetch['given_name'];
$kind = $postsfetch['kin'];
$amountsd = $postsfetch['given'];
$time = $postsfetch['time'];
echo"
<div>
	<tr>
	<th style='min-width:79px';>$id</th>
	<th style='min-width:100px;'> $exchange</th>
	<th style='min-width:70px;'> $received $received_name</th>
	<th style='min-width:70px;'> $amountsd $given_name</th>
	<th style='min-width:50px;'> $kind</th>
	</tr>
</div>
";
}
?>
</table>
