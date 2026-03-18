<?php
// Redirect to the public folder to the actual index
$query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: app/public/index.php' . $query);
exit;
