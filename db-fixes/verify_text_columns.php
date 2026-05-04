<?php
$db = new mysqli('localhost', 'root', '', 'ajab_live');
if ($db->connect_error) {
    fwrite(STDERR, "DB connect failed: " . $db->connect_error . PHP_EOL);
    exit(1);
}

$tables = ['songs', 'reflection', 'couplet', 'film_details', 'film_episode_details', 'story', 'word'];

foreach ($tables as $table) {
    echo "--- {$table} ---" . PHP_EOL;
    $result = $db->query("SHOW COLUMNS FROM `{$table}`");
    if (!$result) {
        echo "table missing or inaccessible" . PHP_EOL;
        continue;
    }

    while ($row = $result->fetch_assoc()) {
        $type = strtolower($row['Type']);
        if (strpos($type, 'text') !== false) {
            echo $row['Field'] . " => " . $row['Type'] . PHP_EOL;
        }
    }
}

$db->close();
