<?php

if(isset($_POST['submit']))  {
	
	$email_to = "hsr.inf@gmail.com";    // Change This
	$email_subject = "Journal Submission!";
	$thankyou = "submit.html?Submitted Successfully.";
	$email_sender = $_REQUEST['email'];  // Change This
	
	// boundary
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
	
	function died($error) {
		echo "Sorry, but there were error(s) found with the form you submitted. ";
		echo "These errors appear below.<br /><br />";
		echo $error."<br /><br />";
		echo "Please go back and fix these errors.<br /><br />";
		die();
	}
	
	
	//$Name = $_POST['Name']; // Sender Name
	//$Email = $_POST['Email']; // Sender Email
	//$Phone = $_POST['Phone']; // Sender Phone
	//$Comment = $_POST['Comment']; // Sender Comments
    $title=$_REQUEST['title'];
	$author=$_REQUEST['author'];
	$corresponding_author=$_REQUEST['corresponding_author'];
	$classification=$_REQUEST['classification'];
	$email=$_REQUEST['email'];
	
			
	
	
	function clean_string($string) {
	  $bad = array("content-type","bcc:","to:","cc:","href");
	  return str_replace($bad,"",$string);
	}
	
	$email_message = "Journal Submission! \n".clean_string($classification);
	
	$email_message .= "------------------------\n\n\n";
	

	
	$email_message .= "Title of the paper : ".clean_string($title)."\n\n"; // Sender Name
	$email_message .= "Authors Name : ".clean_string($author)."\n\n"; // Sender Email
	$email_message .= "Corresponding Author : ".clean_string($corresponding_author)."\n\n"; // Sender Phone
	$email_message .= "Journal Name : ".clean_string($classification)."\n\n\n";  // Sender Comments
	$email_message .= "Email : ".clean_string($email)."\n\n\n";  // Sender Comments
	
	// multipart boundary
$email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_message . "\n\n";
 
foreach($_FILES as $userfile){
      // store the file information to variables for easier access
      $tmp_name = $userfile['tmp_name'];
      $type = $userfile['type'];
      $name = $userfile['name'];
      $size = $userfile['size'];

      // if the upload succeded, the file will exist
      if (file_exists($tmp_name)){

         // check to make sure that it is an uploaded file and not a system file
         if(is_uploaded_file($tmp_name)){
 	
            // Open the file for a binary read
            $file = fopen($tmp_name,'rb');
 	
            // Read the file content into a variable
            $data = fread($file,filesize($tmp_name));

            // Close the file
            fclose($file);
 	
   
            $data = chunk_split(base64_encode($data));
         }
 	
       
         $email_message .= "--{$mime_boundary}\n" .
            "Content-Type: {$type};\n" .
            " name=\"{$name}\"\n" .
            "Content-Disposition: attachment;\n" .
            " filename=\"{$fileatt_name}\"\n" .
            "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n";
      }
   }
			

$headers = 'From: '.$email_sender."\r\n".   // Mail will be sent from your Admin ID
'Reply-To: '.$Email."\r\n" .                // Reply to Sender Email
'X-Mailer: PHP/' . phpversion();
// headers for attachment
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
@mail($email_to, $email_subject, $email_message, $headers);
header("Location: $thankyou");
?>

<script>location.replace('<?php echo $thankyou;?>')</script>
<?php
}
die();
?>