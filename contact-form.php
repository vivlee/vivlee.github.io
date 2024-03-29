<?php

    /*-------------------------------------------------------------------------------------------*/
    /* This script takes values from the Universe Contact Form and send it to a specified email 
    /* TAKE CARE WHEN EDIT IT - If you need further assistance on how to setup it get in touch
     * through our profile on Theme Forest
    /*-------------------------------------------------------------------------------------------*/
    
    if($_POST)
    {
        $to_Email       = "sincerelyvivianlee@gmail.com"; // Replace with recipient email address
        $subject        = 'Contact Sent By Portfolio Contact Form'; //Subject line for emails, you can alter it
        
        
        //check if its an ajax request, exit if not
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
            //exit script outputting json data
            $output = json_encode(
            array(
                'type'=>'error', 
                'text' => 'Request must come from Ajax'
            ));
            
            die($output);
        } 
        
        //check $_POST vars are set, exit if any missing
        if(!isset($_POST["firstName"]) || !isset($_POST["lastName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userMessage"]))
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
            die($output);
        }
    
        //Sanitize input data using PHP filter_var().
        $first_Name        = filter_var($_POST["firstName"], FILTER_SANITIZE_STRING);
        $last_Name       = filter_var($_POST["lastName"], FILTER_SANITIZE_EMAIL);
        $user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_STRING);
        $user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
        
        //additional php validation
        if(strlen($first_Name)<4) // If length is less than 4 it will throw an HTTP error.
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
            die($output);
        }
        if(strlen($last_Name)<4) // If length is less than 4 it will throw an HTTP error.
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Surname is too short or empty!'));
            die($output);
        }
        if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
            die($output);
        }
        
        if(strlen($user_Message)<5) //check emtpy message
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
            die($output);
        }
        
        //proceed with PHP email.
        $headers = 'From: '.$user_Email.'' . "\r\n" .
        'Reply-To: '.$user_Email.'' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
            // send mail
        $sentMail = @mail($to_Email, $subject, $user_Message .'  -  Email sent by: '.$first_Name. ' '.$last_Name, $headers);
        
        if(!$sentMail)
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
            die($output);
        }else{
            $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$first_Name .'. Your email has been sent.'));
            die($output);
        }
    }
?>