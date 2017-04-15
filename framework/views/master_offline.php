<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root.'/framework/includes/db_connect.php';
include_once $root.'/framework/includes/functions.php';
sec_session_start();
$loggedIn = false;

//$manifest =  'manifest="/cache.manifest"';

?>


<!DOCTYPE html>
<html lang="en" <?php echo $manifest ?> >
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php 
            $numRows = count($jsArray);
            for ($x = 0; $x < $numRows; $x++){
              echo '<script src='.$jsArray[$x].'></script>';
            }
        ?>

        <script src="/js/ui/user.js"></script>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            
            $(document).ready(function () {                


                userCookie = localStorage.getItem('myUser');
                console.log(userCookie);



            });


        </script>


        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/custom.css" rel="stylesheet">

        <title>HTML5 demo with localStorage and saving images and files in it</title>
    </head>

    <body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" runat="server" href="/">Camlogg</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav"">
                  <?php if (login_check($mysqli) == true) : ?>                                        
                        <li><a href="/project/" class="navbar-link" >Mina projekt</a></li>                      
                        <li><a href="/project/workflow/" class="navbar-link" >Mina arbetsflöden</a></li>                      
                        <li><a href="/profile/" class="navbar-link" >Min profil</a></li>                      
                        <li><a href="/profile/preferences/" class="navbar-link" >Inställningar</a></li>
                        <li><a href="/attachments.php" class="navbar-link" >Attachments(experimentally)</a></li>
                    <?php else : ?>
                    
                    <?php endif; ?>   

                </ul>
                <ul class="nav navbar-nav navbar-right"  id="headerBar">
                 <?php if (login_check($mysqli) == true) : ?>                
                    <li><a href="#" onclick="logout()" class="navbar-link" >Logga ut</a></li>                      


                <?php else : ?>
                    
                <?php endif; ?>   


                </ul>
            </div>
        </div>
    </nav>
    <body>