<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'jobdesk-management');
if ($mysqli->connect_error) {
    echo "CONNECT_ERROR:" . $mysqli->connect_error . PHP_EOL;
    exit(1);
}
$res = $mysqli->query('SHOW COLUMNS FROM trainings');
if (! $res) {
    echo "QUERY_ERROR:" . $mysqli->error . PHP_EOL;
    exit(1);
}
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . PHP_EOL;
}
$mysqli->close();
