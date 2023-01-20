<?php session_start();
include_once('config.php');
 
//Code for Signup
if(isset($_POST['submit'])){
//Getting Post values
$name=$_POST['username'];	
$email=$_POST['email'];	
$cnumber=$_POST['contactnumber'];	
$loginpass=md5($_POST['password']); // Password is encrypted using MD5
//Generating 6 Digit Random OTP
$otp= mt_rand(100000, 999999);	
// Query for validation of  email-id
$ret="SELECT id FROM  tblusers where (emailId=:uemail)";
$queryt = $dbh -> prepare($ret);
$queryt->bindParam(':uemail',$email,PDO::PARAM_STR);
$queryt -> execute();
$results = $queryt -> fetchAll(PDO::FETCH_OBJ);
if($queryt -> rowCount() == 0)
{
//Query for Insert  user data if email not registered 
$emailverifiy=0;
$sql="INSERT INTO tblusers(userName,emailId,ContactNumber,userPassword,emailOtp,isEmailVerify) VALUES(:fname,:emaill,:cnumber,:hashedpass,:otp,:isactive)";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':fname',$name,PDO::PARAM_STR);
$query->bindParam(':emaill',$email,PDO::PARAM_STR);
$query->bindParam(':cnumber',$cnumber,PDO::PARAM_STR);
$query->bindParam(':hashedpass',$loginpass,PDO::PARAM_STR);
$query->bindParam(':otp',$otp,PDO::PARAM_STR);
$query->bindParam(':isactive',$emailverifiy,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$_SESSION['emailid']=$email;	
//Code for Sending Email
$subject="OTP Verification";
$headers .= "MIME-Version: 1.0"."\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
$headers .= 'From:User Signup<yourname@yourdomain.com>'."\r\n";                          
$ms.="<html></body><div><div>Dear $name,</div></br></br>";
$ms.="<div style='padding-top:8px;'>Thank you for registering with us. OTP for for Account Verification is $vericationcode</div><div></div></body></html>";
mail($email,$subject,$ms,$headers); 
echo "<script>window.location.href='verify-otp.php'</script>";
}else {
echo "<script>alert('Something went wrong.Please try again');</script>";	
}} else{
echo "<script>alert('Email id already assicated with another account.');</script>";
}
}?>