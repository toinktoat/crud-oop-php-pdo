<?php

require_once 'dbconfig.php';

class crud
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);

        return $stmt;
    }

    public function create($fname, $lname, $email, $contact)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO tbl_users(first_name,last_name,email_id,contact_no) VALUES(:fname, :lname, :email, :contact)');
            $stmt->bindparam(':fname', $fname);
            $stmt->bindparam(':lname', $lname);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':contact', $contact);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
    }

    public function getID($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM tbl_users WHERE id=:id');
        $stmt->execute([':id'=>$id]);
        $editRow = $stmt->fetch(PDO::FETCH_ASSOC);

        return $editRow;
    }

    public function update($id, $fname, $lname, $email, $contact)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE tbl_users SET first_name=:fname, 
		                                               last_name=:lname, 
													   email_id=:email, 
													   contact_no=:contact
													WHERE id=:id ');
            $stmt->bindparam(':fname', $fname);
            $stmt->bindparam(':lname', $lname);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':contact', $contact);
            $stmt->bindparam(':id', $id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare('DELETE FROM tbl_users WHERE id=:id');
        $stmt->bindparam(':id', $id);
        $stmt->execute();

        return true;
    }

    /* paging */

    public function dataview($query)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['email_id']; ?></td>
                <td><?php echo $row['contact_no']; ?></td>
                <td align="center">
                <a href="edit-data.php?edit_id=<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                </td>
                <td align="center">
                <a href="delete.php?delete_id=<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
                </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
            <td>Data Kosong</td>
            </tr>
            <?php
        }
    }

    public function paging($query, $records_per_page)
    {
        $starting_position = 0;
        if (isset($_GET['page_no'])) {
            $starting_position = ($_GET['page_no'] - 1) * $records_per_page;
        }
        $query2 = $query." limit $starting_position,$records_per_page";

        return $query2;
    }

    public function paginglink($query, $records_per_page)
    {
        $self = $_SERVER['PHP_SELF'];

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $total_no_of_records = $stmt->rowCount();

        if ($total_no_of_records > 0) {
            ?><ul class="pagination"><?php
            $total_no_of_pages = ceil($total_no_of_records / $records_per_page);
            $current_page = 1;
            if (isset($_GET['page_no'])) {
                $current_page = $_GET['page_no'];
            }
            if ($current_page != 1) {
                $previous = $current_page - 1;
                echo "<li><a href='".$self."?page_no=1'>First</a></li>";
                echo "<li><a href='".$self.'?page_no='.$previous."'>Previous</a></li>";
            }
            for ($i = 1; $i <= $total_no_of_pages; $i++) {
                if ($i == $current_page) {
                    echo "<li><a href='".$self.'?page_no='.$i."' style='color:red;'>".$i.'</a></li>';
                } else {
                    echo "<li><a href='".$self.'?page_no='.$i."'>".$i.'</a></li>';
                }
            }
            if ($current_page != $total_no_of_pages) {
                $next = $current_page + 1;
                echo "<li><a href='".$self.'?page_no='.$next."'>Next</a></li>";
                echo "<li><a href='".$self.'?page_no='.$total_no_of_pages."'>Last</a></li>";
            } ?></ul><?php
        }
    }

    /* paging */
}
