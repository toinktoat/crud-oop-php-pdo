<?php
require_once 'class.crud.php';
$crud = new CRUD();

if (isset($_POST['btn-del'])) {
    $id = $_GET['delete_id'];
    $crud->delete($id);
    header('Location: delete.php?deleted');
}

?>

<?php include_once 'header.php'; ?>

<div class="clearfix"></div>

<div class="container">

	<?php
    if (isset($_GET['deleted'])) {
        ?>
        <div class="alert alert-success">
    	<strong>Berhasil</strong> data telah terhapus ...
		</div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger">
    	<strong>Yakin ?</strong> untuk menghapus data ini ?
		</div>
        <?php
    }
    ?>
</div>

<div class="clearfix"></div>

<div class="container">

	 <?php
     if (isset($_GET['delete_id'])) {
         ?>
         <table class='table table-bordered'>
         <tr>
         <th>No</th>
         <th>First Name</th>
         <th>Last Name</th>
         <th>E - mail ID</th>
         <th>Contact No</th>
         </tr>
         <?php
         $stmt = $crud->runQuery('SELECT * FROM tbl_users WHERE id=:id');
         $stmt->execute([':id'=>$_GET['delete_id']]);
         while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
             ?>
             <tr>
             <td><?php echo $row['id']; ?></td>
             <td><?php echo $row['first_name']; ?></td>
             <td><?php echo $row['last_name']; ?></td>
             <td><?php echo $row['email_id']; ?></td>
         	 <td><?php echo $row['contact_no']; ?></td>
             </tr>
             <?php
         } ?>
         </table>
         <?php
     }
     ?>
</div>

<div class="container">
<p>
<?php
if (isset($_GET['delete_id'])) {
         ?>
  	<form method="post">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
    <button class="btn btn-large btn-primary" type="submit" name="btn-del"><i class="glyphicon glyphicon-trash"></i> &nbsp; YA</button>
    <a href="index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; TIDAK</a>
    </form>
	<?php
     } else {
    ?>
    <a href="index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Kembali Ke Index</a>
    <?php
}
?>
</p>
</div>
<?php include_once 'footer.php'; ?>
