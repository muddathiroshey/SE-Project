<?php
$errors      = $errors ?? ['login' => '', 'signup' => ''];
$active_form = $active_form ?? 'login-form';
function showError($e){ return !empty($e) ? '<p class="error">'.$e.'</p>' : ''; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
:root {
  
    --accent-color: #4CAF50;
    --input--color:  #e1e9e19a;
    --secondary-color: #f44336;
    --background-color: #efefef;
    --text-color: #333333;


}
*{
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial;
}
body {
    background: var(--accent-color);
    min-height: 100vh;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
}
.wrapper{
    box-sizing: border-box;
    background-color: var(--background-color);
    height: 100vh;
    width: max(40%,600px);
    padding: 10px;
    border-radius: 0 20px 20px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
h1{
    color: var(--text-color);
    font-size: 3rem;
    font-weight: 1000;
    
}
form{
    width: min(400px, 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
form div{
    display: flex;
    width: 100%;
   
}
form  label{
    flex-shrink: none;
    background-color: var(--accent-color);
    height: 50px;
    width: 50px;
    fill: var(--background-color);
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px 0px 0px 10px;
    border: 2px solid var(--accent-color);
    transition: 500ms ease;  
}
form input{
    flex-grow: 1;
    height: 50px;
    border: 2px solid var(--input--color);
    padding: 0 1em;
    font-size: 1.2rem;
    border-radius: 0px 10px 10px 0px;
    background-color: var(--input--color);
    min-width: 0;
}
form select {
    flex-grow: 1;
    height: 50px;
    border: 2px solid var(--input--color);
    padding: 0 1em;
    font-size: 1.2rem;
    border-radius: 0px 10px 10px 0px;
    background-color: var(--input--color);
    min-width: 0;
}
form button{
    width: 100%;
    height: 50px;
    background-color: var(--accent-color);
    color: var(--background-color);
    border: none;
    font-size: 1.2rem;
    border-radius: 10px;
    cursor: pointer;
    
}
form button:hover{
    background-color: #67a969 ;
}
form p{
    color: var(--text-color);
    font-size: 0.9rem;
    text-align: center;
}
form p a{
    color: var(--accent-color);
    text-decoration: none;
}
form p a:hover{
    text-decoration: underline;
}
form input:focus{
    outline: none;
    border-color: var(--accent-color);
}
form  input:hover{
    border-color: var(--accent-color);
} 
.Form{
    display: none;
}
.Form.active{
    display: flex;
}
.error{
    color: var(--secondary-color);
    font-size: 0.9rem;
    text-align: center;
}
   </style>
</head>
<body>
    <div class="wrapper">
        <form action="/login" id="login-form" class="Form <?= $active_form === 'login-form' ? 'active' : '' ?>" method="post">
            <h1>LOGIN</h1>
            <?= !empty($errors['login']) ? '<p class="error">'.$errors['login'].'</p>' : '' ?>
            <div>
                <label><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg></label>
                <input placeholder="Enter Your Email" type="email" name="email" required>
            </div>
            <div>
                <label><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></label>
                <input placeholder="Enter Your Password" type="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="#" onclick="show('signup-form')">Sign up</a></p>
        </form>

        <form action="/signup" id="signup-form" class="Form <?= $active_form === 'signup-form' ? 'active' : '' ?>" method="post">
                            <h1>SIGN UP</h1>
                <?= showError($errors['signup']) ?>
                <div>
                    <label for=""><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" f><path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm216.5-183.5Q440-527 440-560t-23.5-56.5Q393-640 360-640t-56.5 23.5Q280-593 280-560t23.5 56.5Q327-480 360-480t56.5-23.5ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/></svg></label>
                    <input placeholder="Enter Your Name" type="text" id="" name="fname" required>
                </div>
            <div>
                <label for=""><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg></label>
                <input placeholder="Enter Your Email" type="email" id="" name="email" required>
            </div>
            <div>
                <label for=""><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></label>
                <input placeholder="Enter Your Password" type="password" id="" name="password" required>  
            </div>
            <div>
                <label for=""><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></label>
                <input placeholder="Confirm Your Password" type="password" id="" name="confirm_password" required>
                
            </div>
            <div>
                <label for=""><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M40-480v-80h80v80H40Zm800 0v-80h80v80h-80ZM40-640v-80h80v80H40Zm800 0v-80h80v80h-80ZM40-800v-80h80v80H40Zm160 320v-80h80v80h-80Zm480 0v-80h80v80h-80Zm160-320v-80h80v80h-80Zm-640 0v-80h80v80h-80Zm160 0v-80h80v80h-80Zm160 0v-80h80v80h-80Zm160 0v-80h80v80h-80ZM473-40q-24 0-46-9t-39-26L184-280l33-34q14-14 34-19t40 0l69 20v-327q0-17 11.5-28.5T400-680q17 0 28.5 11.5T440-640v433l-98-28 103 103q6 6 13 9t15 3h167q33 0 56.5-23.5T720-200v-160q0-17 11.5-28.5T760-400q17 0 28.5 11.5T800-360v160q0 66-47 113T640-40H473Zm7-280v-160q0-17 11.5-28.5T520-520q17 0 28.5 11.5T560-480v160h-80Zm120 0v-120q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440v120h-80Zm40 200H445h195Z"/></svg></label>
                <select name="role" id="" required>
                    <option value="">--select role--</option>
                    <option value="Client">Client</option>
                    <option value="Freelancer">Freelancer</option>
                </select>
            </div>
            <button name="signup" type="submit">Sign up</button>
            <p>Already have an account? <a href="#" onclick="show('login-form')">Login</a></p>
        </form>
    </div>
    <script>
        function show(formId) {
            document.querySelectorAll('.Form').forEach(f => f.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
        }
    </script>
</body>
</html>