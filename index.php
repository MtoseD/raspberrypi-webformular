<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print(gethostname()); ?> - Websites</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/jquery-3.6.0.js"></script>
    <!-- Delete-Function -->
    <script>
        async function deleteRequest(elem) {
            $.ajax({
                type: 'POST',
                url: '/sites/deleting.php',
                dataType: 'html',
                data: {
                    'link': elem
                }
            });
            location.reload();
        };
    </script>
</head>

<!-- Delete Icon -->
<?php $delBtn = "<td><button class='delete' onclick=\"deleteRequest(this.parentNode.parentNode.getElementsByTagName('td')[2].innerText)\"><i class='material-icons'>&#xE872;</i></button></td>"; ?>

<!-- Add-Function -->
<?php
$name = $kuerzel = $website = '';
if (isset($_POST['submit'])) {
    //read data in $_POST array
    $name = $_POST['name'];
    $kuerzel = $_POST['kuerzel'];
    $website = $_POST['website'];
    //csv
    $websiteList = array(
        array("$name", "$kuerzel", "$website")
    );
    $websiteFileTable = fopen('textFiles/websites.csv', "a");
    foreach ($websiteList as $fields) {
        fputcsv($websiteFileTable, $fields);
    }
    fclose($websiteFileTable);	
    //reload page
    header('Location: index.php');
};
?>


<body>
    <div class="container">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <!-- Title -->
                            <h2>Raspberry Pi | <b><?php print(gethostname()); ?></b></h2>
                        </div>
                        <!-- Add-Button -->
                        <div class="col-xs-6">
                            <a href="#addWebsiteModal" class="btn btn-add" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Website hinzufügen</span></a>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Vor-/Nachname</th>
                            <th>Kürzel</th>
                            <th>Website</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php
                    $f = fopen("textFiles/websites.csv", "r");
                    //$flag = true; //Skip first line
                    while (($line = fgetcsv($f, 1000, ',')) !== false) {
                        // if ($flag) {
                        //     $flag = false;
                        //     continue;
                        // }
                    ?>
                        <tbody>
                        <?php
                        foreach ($line as $cell) {
                            echo "<td>" . htmlspecialchars($cell) . "</td>";
                        }
                        echo "$delBtn";
                        echo "</tr>\n";
                    }
                    fclose($f);
                        ?>
                        </tbody>
                </table>
		    <!-- Footer -->
			<div class="footer-container">
	            <!-- Reboot-Button -->
		        <script>
			    //Warning Box - JS
			    function warning(e){
			        if(!confirm('Sind Sie sicher, dass Sie das Dashboard neustarten wollen? (2 Minuten)')) {
			            e.preventDefault();
			        }
			        else{
				        //Button Command execution - Ajax
				        $.ajax({
					    method: 'get',
					    url: 'index.php',
					    data: {
					        clicked: true
					    }
				        });
			        }
			    };
		        </script>
		        <?php
		        //Execute Shell Command
		        $clicked = $_GET['clicked'];
		        if ($clicked == true){
			        exec('/sbin/shutdown -r now');
		        }
		        ?>
		        <button type="button" id="reboot" class="btn-reboot btn btn-outline-success" onclick="warning()">Neustarten</button>
                 <!-- Credits -->
	            <p class="credits">Written and Illustrated by Mike Dätwyler (DM2)</p>
		    <p class="credits">© <?php echo date("Y"); ?></p>
	        </div>
        </div>
    </div>
    <!-- Add-Modal -->
    <div id="addWebsiteModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="index.php">
                    <div class="modal-header">
                        <h4 class="modal-title">Website hinzufügen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Vor-/Nachname</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($name) ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kürzel</label>
                            <input type="text" name="kuerzel" value="<?php echo htmlspecialchars($kuerzel) ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="url" name="website" value="<?php echo htmlspecialchars($website) ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" name="cancel" class="btn btn-default" data-dismiss="modal" value="abbrechen">
                        <input type="submit" name="submit" class="btn btn-success" value="Hinzufügen">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
