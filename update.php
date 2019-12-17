<?php
include "config/db_connect.php";
$feedback='';

if(isset($_GET['id'])){
    $id=$_GET['id'];
        
    if(isset($_POST['id'])){
        $tit=$_POST["tit"];
        $ing=$_POST["ing"];
        $em=$_POST["em"];
        $id=$_POST['id'];

        if (empty($_POST['em'])) {
            $errors['em'] = "An email is required <br/>";
        } else {
            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $errors['em'] = "Email must be valid email address <br/>";
            }
        }
        if (empty($_POST['tit'])) {
            $errors['tit'] = "A title is required <br/>";
        } else {
            if (!preg_match('/^[a-zA-Z\s]+$/', $tit)) {
                $errors['tit'] = "Title must be letters and spaces only";
            }
        }
        if (empty($_POST['ing'])) {
            $errors['ing'] = "At least one Ingredient is required <br/>";
        } else {
            if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ing)) {
                $errors['ing'] = "Ingredients must be comma seperated list";
            }
        }
        //array_filter
        if (array_filter($errors)) {
            //do nothing since there is something handling it already.
        } else {
            //just like htmlspecialchar .. we have something to prepare us for sql injection
            $email = mysqli_real_escape_string($conn, $_POST['em']);
            $title = mysqli_real_escape_string($conn, $_POST['tit']);
            $ingredients = mysqli_real_escape_string($conn, $_POST['ing']);

            $sql2 = "UPDATE pizzas SET title = '$title', ingredients = '$ingredients', email = '$email' WHERE id= '$id'";
            $result2 = mysqli_query($conn, $sql2);
            
            if($result2){
                $feedback="Updated successfully !";
            }
        }
    }
}


$sql = "SELECT title,ingredients,id,email FROM pizzas WHERE id = $id";
$result = mysqli_query($conn, $sql);
$pizzas = mysqli_fetch_assoc($result);
$tit=$pizzas["title"];
$ing=$pizzas["ingredients"];
$em=$pizzas["email"];



mysqli_free_result($result);
mysqli_close($conn);
include "templates/header.php";


?>



<h4 class="center green-text">Pizzas from Naija Pizzas!!!</h4>
<div class="container">
    <div class="row">

        
        <form action="#" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id);?>" />
            <input type="text" name="tit" value="<?php echo htmlspecialchars($tit);?>" placeholder="Enter Title"/>
            <input type="text" name="ing" value="<?php echo htmlspecialchars($ing);?>" placeholder="Enter Ingredient"/>
            <input type="email" name="em" value="<?php echo htmlspecialchars($em);?>" placeholder="Enter Your Email"/>
            <input type="submit" name="sub" value="Update"/>
        </form>
        <?php echo $feedback?>

    </div>
</div>



<?php
require "templates/footer.php";
