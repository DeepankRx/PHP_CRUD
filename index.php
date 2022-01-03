<?php
//Note Application
//function for console log
function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
//connecting parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
//creating a connection 
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    console_log("Connection failed " . mysqli_connect_error() . "\n");
} else {
    console_log("Connection Successful\n");
}
//Query for creating a database
$myDatabase = "CREATE DATABASE notes";
//Checking whether database is created or not
$result = mysqli_query($conn, $myDatabase);
if ($result) {
    console_log("Database creating successfully\n");
} else {
    console_log("Database creation failed " . mysqli_error($conn) . "\n");
}

//Query for Creating A Table
$myTable = "CREATE TABLE `notes` (`sno` INT(5) NOT NULL AUTO_INCREMENT, `title` VARCHAR(100) NOT NULL, `description` VARCHAR(255) NOT NULL , PRIMARY KEY (`sno`))";
//checking table creatied dor not
$resultOfTable  = mysqli_query($conn, $myTable);
if (!$resultOfTable) {
    console_log("Table Creation Failed " . mysqli_error($conn) . "\n");
} else {
    console_log("\nTable Creation Successful\n");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["titleEdit"])) {
        $sno = $_POST["snoEdit"];
        $titleEdit = $_POST["titleEdit"];
        $descriptionEdit = $_POST["descriptionEdit"];
        $updatedEdit = "UPDATE `notes` SET `title`='$titleEdit' , `description`='$descriptionEdit' WHERE `notes`.`sno`=$sno";
        $result = mysqli_query($conn, $updatedEdit);
        if (!$result) {
            console_log(mysqli_error($conn));
        } else {
            console_log("Updated Successfully");
        }
    } else {
        $title = $_POST["title"];
        $description = $_POST["description"];
        if (empty($title) || empty($description)) {
            console_log("Please Insert Title and description");
        }
        //inserting into a table
        $insertingData = "INSERT INTO `notes` ( `title`, `description`) VALUES ('$title', '$description');";
        $insertStatus = mysqli_query($conn, $insertingData);
        if (!$insertStatus) {
            console_log("Data Insertion Failed " . mysqli_error($conn));
        } else {
            console_log("Data Insertion Successful ");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Application</title>
</head>

<body>
    <div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="Title" class="align-text-top" text-align="center">Title</label>
                <input type="text" class="form-control  align-middle align-bottom" name="title" id="title" placeholder="Enter Title" required>
            </div>
            <div class="form-group">
                <label for="Description">Description</label>
                <input type="text" class="form-control" name="description" id="desc" placeholder="Enter Description" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php
        $detail = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $detail);
        $num = mysqli_num_rows($result);
        if ($num > 0) {

            echo '<div class="container my-4">' .
                ' <table class="table" id="myTable">' .
                '   <thead>' .
                ' <tr>' .
                '     <th scope="col">S.No</th>   ' .
                '     <th scope="col">Title</th>' .
                '     <th scope="col">Description</th>' .
                '   </tr>';
        }

        ?>
        <?php
        $detail = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $detail);
        if (!$result) {
            console_log(mysqli_error($conn));
        }
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $sno = $sno + 1;
            echo "<tr>
                <th scope='row'>" . $sno . "</th>
                <td>" . $row['title'] . "</td>
                <td>" . $row['description'] . "</td>
                <td> <button onclick='hideDiv()' class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button>  </td>
              </tr>";
        }
        ?>
        </thead>
    </div>
    <div style="display:none;" id="editBtn">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="modal-body">
                <input type="hidden" name="snoEdit" id="snoEdit">
                <div class="form-group">
                    <label for="title">Note Title</label>
                    <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                </div>

                <div class="form-group">
                    <label for="desc">Note Description</label>
                    <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer d-block mr-auto">
                <button type="button" class="btn btn-secondary" onclick=hideDiv() data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

        });
    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id)
                $('#editModal').modal('toggle');
            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this note!")) {
                    console.log("yes");
                    window.location = `<?php echo $_SERVER['PHP_SELF']; ?>`;
                    // TODO: Create a form and use post request to submit a form
                } else {
                    console.log("no");
                }
            })
        })

        function hideDiv() {
            var x = document.getElementById("editBtn");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</body>

</html>