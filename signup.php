<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if($_SERVER["REQUEST_METHOD"]==="POST"){
  $fullname=$_POST['fullname'];
  $phone=$_POST['phone'];
  $email=$_POST['email'];
  $address=$_POST['address'];
  $aboutyourself=$_POST['aboutyourself'];
  $techskills=$_POST['techskills'];
  $abouttechskills=$_POST['abouttechskills'];
  $skill=$_POST['skill'];
  $joinmode=$_POST['joinmode'];
  $paymentmode=$_POST['paymentmode'];
  $date=time();

  if(empty($fullname) && 
  empty($phone) && 
  empty($email) && 
  empty($address) && 
  empty($aboutyourself) && 
  empty($techskills) && 
  empty($abouttechskills) 
  && empty($skill) 
  && empty($joinmode) 
  && empty($paymentmode)){
    echo '
          <div class="text-red-500 font-bold">
            <p>All Fields are Required!</p>
          </div>
      ';
  }else{

  // Uploads files
  // name of the uploaded file
  $filename = $_FILES['image']['name'];

  // destination of the file on the server
  $destination = './images/' . $filename;

  // get the file extension
  $extension = pathinfo($filename, PATHINFO_EXTENSION);

  // the physical file on a temporary uploads directory on the server
  $file = $_FILES['image']['tmp_name'];
  $size = $_FILES['image']['size'];

  if (!in_array($extension, ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'])) {
    echo '
            <div class="text-red-500 font-bold">
              <p>Your file extension must be .jpg, .jpeg or .png!</p>
            </div>
      ';
  } elseif ($_FILES['image']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
    echo '
          <div class="text-red-500 font-bold">
            <p>Error! Picture is too large!</p>
          </div>
      ';
  } else {
      // move the uploaded (temporary) file to the specified destination
      if (move_uploaded_file($file, $destination)) {
  $insert=mysqli_query($conn, "INSERT INTO user (fullname,phone,email,address,aboutyourself,techskill,abouttechskill,image,skill,joinmode,paymentmode,date)
  VALUES('$fullname','$phone','$email','$address','$aboutyourself','$techskills','$abouttechskills','$filename','$skill','$joinmode','$paymentmode','$date')");
  if($insert == true){



    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'www.stemlab.com.ng';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@stemlab.com.ng';
    $mail->Password = 'Dzw29_39d';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('admin@stemlab.com.ng');

    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Welcome to Stemlab Internship 2023";
    // $mail->addReplyTo('admin@stemlab.com.ng', 'Stemlab Kano');
    // Attach the image to the email and set the Content-ID
    // $image_path = 'assets/stem 2.png';
    // $mail->AddEmbeddedImage('assets/stem 2.png', 'logo', 'stem 2.png.jpg');
    $mail->Body = "<b style='color:orange'>Hello ".$fullname."</b>,<br>

    We are Glad you indicated interest to join our internship programme.<br>
    Our Interns are set to gain practical hands on skills and knowledge. Which can be immediately applied to both their professional workplace as well as towards their personal career development.<br>
    Our Internship program are a 6-months Career focused process designed to produce Tech giants.<br>
    At stemlab we have built a framework that helps to build, launch and Scale young tech minds while providing an enabling environment for their Ideas to thrive.<br><br>
    
    To begin your journey kindly visit our office at No. 60 Ahmadu Bello, Nassarawa GRA Kano or Call 08162224407. <br><br>
    
    Welcome on board!<br><br>
    
    Best,<br>
    Admin";

    $mail->send();
    echo "yes";

  }else{
    echo '
          <div class="text-red-500 font-bold">
            <p>Error! Unable to register!</p>
          </div>
      ';
  }

}else{
    echo '
          <div class="text-red-500 font-bold">
            <p>picture is required</p>
          </div>
      ';
}
}
}
}
?>