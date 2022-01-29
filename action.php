<?php
    session_start();
    include 'config.php';
    $update = false;
    // to clear the text boxes
        $id = "";
        $name = "";
        $email = "";
        $phone = "";
        $photo = "";


    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $photo = $_FILES['image']['name']; 
        $upload = "uploads/".$photo;

        $query = "Insert into tblcrud(name,email,phone,photo)values(?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss",$name,$email,$phone,$upload);
        $stmt->execute();
        move_uploaded_file($_FILES['image']['tmp_name'], $upload);
        header('location:index.php');  // 14:49 Video 2
        $_SESSION['response']="Successfully Inserted to the database!";
        $_SESSION['res_type']="success";
    } // Run the program.
    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        echo $id;
        $sql = "Select photo from tblcrud where id = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("i", $id); // i means integer
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row = $result2->fetch_assoc();

        $imagepath = $row['photo'];
        unlink($imagepath);

        $query = "Delete from tblcrud where id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i",$id); // i = is integer value, use to accept id
        $stmt->execute();

        header('location:index.php');
        $_SESSION['response'] = "Successfully Deleted!";
        $_SESSION['res_type'] = "danger";
    } // Run the program.
    if(isset($_GET['edit'])){
        $id = $_GET['edit'];
        $query = "Select * from tblcrud where id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $photo = $row['photo'];

        $update = true;
    }
    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email']; 
        $phone = $_POST['phone'];
        $oldimage = $_POST['oldimage'];

        if(isset($_FILES['image']['name']) && ($_FILES['image']['name'] != "" )){
            $newimage = "uploads/" .$_FILES['image']['name'];
            unlink($oldimage); // remove old image   
            move_uploaded_file($_FILES['image']['tmp_name'], $newimage);
        } else{
            $newimage = $oldimage;
        }
        $query = "Update tblcrud set name = ?, email = ?, phone = ?, photo = ? where id = ? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi",$name, $email, $phone, $newimage, $id);
        $stmt->execute();

        $_SESSION['response'] = "Updated Successfully";
        $_SESSION['res_type'] = "primary";
        header('location: index.php'); // take note of the spacing location: 
  }
  if(isset($_GET['details'])){
    $id = $_GET['details'];
    $query = "Select * from tblcrud where id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $vid = $row['id'];
    $vname = $row['name'];
    $vemail = $row['email'];
    $vphone = $row['phone'];
    $vphoto = $row['photo'];

}

?>                 
