<!-- Delete-Function -->
<?php
//CSV
$csv = array_map('str_getcsv', file('../textFiles/websites.csv'));
$fpCSV = fopen('../textFiles/websites.csv', 'w');
foreach ($csv as $fields) {
  if (!str_contains($fields[2], strval($_POST['link']))) {
    fputcsv($fpCSV, $fields);
  }
}
fclose($fpCSV);
//reload page
header('Location: index.php');
?>
