<?php 
error_reporting(E_ALL ^ E_NOTICE);  

$clever_ids['client_id']     = '9be13256fbeb63283a82';
$clever_ids['client_secret'] = '3e31cc32293aed1a0ed1105540a80b157d92bf58';
$clever_ids['district_id']   = '60d37f072c9e45a642d1e39b';
// $clever_ids['redirect_url']  = 'https://scps.ucertify.com/login.php?func=clever';
// https://scps.ucertify.com/login.php?func=clever
$clever_ids['redirect_url']  = 'http://localhost/clever/login.php?func=clever';

?>

<?php if($_REQUEST['func'] != 'clever' ){ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clever</title>
</head>
<style>
    div button a{
        color: black;
        text-decoration: none;
        font-size: 20px;
    }
    .modal_box{
        display: inline-block;
        /* border: 1px solid black; */
        display: flex;
        justify-content: center;
        margin-top: 20%;        
    }
</style>
<body>
    <div class="modal_box">
        <button>
            <a href="https://clever.com/oauth/authorize?response_type=code&redirect_uri=<?php echo $clever_ids['redirect_url'];?>&client_id=<?php echo $clever_ids['client_id']; ?>&district_id=<?php echo $clever_ids['district_id']; ?>"> Login with Clever</a>
        </button>
    </div>
</body>
</html>

<?php }?>
<!-- <a href="https://clever.com/oauth/authorize?response_type=code&redirect_uri=<{$redirect_uri}>&client_id=<{$client_id}>&district_id=<{$district_id}>" class="ml p-0 outline0 d-flex social_media"> -->
                                