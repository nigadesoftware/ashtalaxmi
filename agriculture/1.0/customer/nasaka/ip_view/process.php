<?php
   session_start();
   if(isset($_POST))
   {
      if (empty($_POST['first_name']))
      {
		   $_SESSION['errors']['first_name'] = 'First name is missing';
      }
      else
      {
         $_SESSION['errors']['first_name'] = '';
      }
      if (empty($_POST['email']))
      {
		   $_SESSION['errors']['email'] = 'email is missing';
      }
      else
      {
         $_SESSION['errors']['email'] = '';
      }
      if(count($_SESSION['errors']) > 0)
      {
         //This is for ajax requests:
         if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
         {
            echo json_encode($_SESSION['errors']);
            exit;
         }
         //This is when Javascript is turned off:
         echo '<ul>';
         foreach($_SESSION['errors'] as $key => $value)
         {
            echo '<li>' . $value . '</li>';
         }
         echo '</ul>';
         exit;
      }
      else
      {
         //form validation successful - process data here!!!!
      }
   }
?>