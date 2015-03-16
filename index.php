<?php 

#start seesion:

session_start();

if(!isset($_SESSION['username'])) {
    header('Location: login.php');
    
}

?>

<?php include('config/setup.php'); ?>

<!DOCTYPE html>

<html lang="en">
<head>

<title> <?php echo $page['title'].' | '.$site_title; ?> </title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php include('config/css.php'); ?>

<?php include('config/js.php'); ?>

    


</head>

    <body>
    

        <?php include(D_TEMPLATE.'/navigation.php'); ?>
        
        <h1> Admin Dashboard </h1>
        
        
        <div class="row">
        
                <div class="col-md-3">
                
                
                
                                        <?php 
                    
                    if(isset($_POST['submitted']) == 1){
                        $title = mysqli_real_escape_string($dbc, $_POST['title']);
                        $label = mysqli_real_escape_string($dbc, $_POST['label']);
                        $header = mysqli_real_escape_string($dbc, $_POST['header']);
                        $body = mysqli_real_escape_string($dbc, $_POST['body']);
                        
                        if(isset($_POST['id']) != '') {
                            
                            $q = "UPDATE pages SET user = $_POST[user], 'slug = $_POST[slug]', title = '$title', label= '$label', header = '$header', body = '$body' WHERE id = $_GET[id]";
                            
                        } else{
                        
                        $q = "INSERT INTO pages (user, slug, title, label, header, body) VALUES ($_POST[user], '$_POST[slug]', '$title', '$label', '$header', '$body')";    
                            
                        }
                        
                        
                        $r = mysqli_query($dbc, $q);
                        
                    if($r){
                        
                        $message = '<p>Page was added!</p>';                 
                        
    } else  {
        
        $message = '<p>Page Could not be added because: '.mysqli_error($dbc);
        
        $message .= '<p>'.$p.'</p>';
        
    }  
                    }
                    
                    ?>
                    
                <div class="list-group">
                
                <a class="list-group-item" href="index.php">
                
                    <h4 class="list-group-item-heading"><i class="fa fa-plus"></i> Add New Page</h4>
                    
                </a>    
                
                <?php
                
                $q ="SELECT * FROM pages ORDER BY title ASC";
                $r = mysqli_query($dbc, $q);
                
                while($page_list = mysqli_fetch_assoc($r)){ 
                
                $blurb = substr(strip_tags($page_list['body']), 0, 160);
                
                ?>
                
                <a class="list-group-item" href="index.php?id=<?php echo $page_list['id']; ?>"> 
                
                <h4 class="list-group-item-heading"> <?php echo $page_list['title']; ?></h4>
                
                <p class="list-group-item-text"> <?php echo $blurb;  ?> </p>
                
                </a>
                
                
                <?php } ?>
                
                </div>
                    
                </div>
                    
                    
                    <div class="col-md-9">
                    
                    <?php 
                    
                        if(isset($message)) { echo $message;} ?> 
                        
                    <?php
                    
                    if(isset($_GET['id'])) {
                        
                        $q = "SELECT * FROM pages WHERE id = $_GET[id]";
                        $r = mysqli_query($dbc, $q);
                        $opened = mysqli_fetch_assoc($r);
                        
                    }
                    
                    ?>
                    
                    <form action="index.php" method="post">
                    
                        <div class="form-group">
                    
                            <label for="title">Title:</label>
                            <input class="form-control" type="text" name="title" id="title" value="<?php echo $opened['title']; ?>" placeholder="Page Title">
                    
                        </div>
                        
                        <div class="form-group">
                    
                            <label for="user">User:</label>
                            <select class="form-control" name="user" id="user">
                            
                            <option value="0">No user</option>
                            <?php
                            
                            $q = "SELECT email FROM users ORDER BY first ASC";
                            $r = mysqli_query($dbc, $q);
                            
                            while($user_list = mysqli_fetch_assoc($r)) {
                            
                            $user_data = data_user($dbc, $user_list['email']);
                            ?>
                            
                            <option value="0"> <?php echo $user_data['fullname']; ?></option>
                            
                            <?php } ?>
                            
                            </select>
                    
                        </div>
                        
                           <div class="form-group">
                    
                            <label for="slug">Page URL</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="<?php echo $opened['slug']; ?>" placeholder="Page URL">
                    
                        </div>
                        
                        <div class="form-group">
                    
                            <label for="label">Label:</label>
                            <input class="form-control" type="text" name="label" id="label" value="<?php echo $opened['label']; ?>" placeholder="Page Label">
                    
                        </div>
                        
                        <div class="form-group">
                    
                            <label for="header"> Header:</label>
                            <input class="form-control" type="text" name="header" id="header" value="<?php echo $opened['header']; ?>" placeholder="Page Header">
                    
                        </div>
                        
                        <div class="form-group">
                    
                            <label for="body">Body:</label>
                            <textarea class="form-control editor" name="body" id="body" rows="8" placeholder="Body"><?php echo $opened['body']; ?> </textarea>
                    
                        </div>
                        
                        <button type="submit" class="btn btn-default"> Save</button>
                        <input type="hidden" name="submitted" value="1">
                        <input type="hidden" name="id" value="<?php echo $opened['id']; ?>">
                    
                    
                    </form>
                
                
                    </div>
        
        </div>
        
        

    <?php include(D_TEMPLATE.'/footer.php'); ?>
    
   
   <?php if($debug == 1) {include ('widgets/debug.php'); } ?>
    </body>
    
</html>  