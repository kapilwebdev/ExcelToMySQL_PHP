<?php
  include('csvFileImport.php');
  $csv = new CSV();
  $fetch = false;

  $existingTable = $csv->fetchTableName();
  if(isset($_POST['submit'])){
    $name = $_FILES['file']["name"];
    $table = explode(".",$name);
    $csv->createTable($table[0],$_FILES['file']['tmp_name']);
    $csv->import($_FILES['file']['tmp_name'],$table[0]);
    $existingTable = $csv->fetchTableName();
  }

  if(isset($_POST['table'])){
    $data = null;
    $tableName = $_POST['tableName'];
    $data = $csv->fetchData($tableName);
    $col = mysqli_num_rows($data[0]);
    $fetch = true;
  }
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP Excel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>

    <h1 class="text-center">Import Excel files to MySql</h1>
    <hr>

    <div class="row">
      <div class="col col-sm-12 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="import">
              <form method="post" enctype="multipart/form-data"  class="text-primary">
                <input type="file" name="file">
                <br><br>
                <input type="submit" name="submit" value="Import" class="btn btn-primary">
                <p class="text-muted">Please select a .csv file</p>
              </form>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="col col-sm-12 col-md-6">
        <div class="card">
          <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <select class="custom-select" name="tableName">
                  <option selected>Select Table Name</option>
                  <?php while($row = mysqli_fetch_row($existingTable)) : ?>
                  <option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
                  <?php endwhile; ?>
                </select>
              <br><br>
              <input type="submit" name="table" value="Fetch" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-lg">
          </form>
          </div>
        </div>
      </div>

    </div>


    <?php if($fetch):?>
      <div class="output">
        <table class="table table-stripped">
          <thead>
              <tr>
            <?php while($row = mysqli_fetch_row($data[0])) : ?>
                <?php $i=0; while($i < 1) : ?>
                <th><?php print_r($row[0]); $i++; ?></th>
                <?php endwhile; ?>
            <?php endwhile; ?>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_row($data[1])) : ?>
              <tr>
                <?php $i=0; while($i < $col) : ?>
                <td><?php echo $row[$i]; $i++; ?></td>
                <?php endwhile; ?>
              </tr>
            <?php endwhile; ?>
          </tbody>
      </table>
    </div>
  <?php endif; ?>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
  </body>
</html>
