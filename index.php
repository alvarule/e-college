<!DOCTYPE html>
<html lang="en">
<head>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Marcellus+SC&family=Mitr:wght@300&family=Outfit:wght@300;500&display=swap" rel="stylesheet"> 

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="icon" type="image/png" href="/e-college/partials/logo.png">
  
  <title>E-College</title>
  <style>
    
      *{
          transition: 0.2s;
      }
      .body{
          background-color: #D3E0EA;
          margin-top: 20px;
          font-family:'Marcellus SC', serif;
      }
      .container{
        top: 50%;
        left: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
      }
      h1{
        font-size: 32pt;
        color: #000;
        margin: 40px 0;
      }
      img{
        width: 25%;
        display: block;
        margin-bottom: 40px;
        border-radius: 35%;
      }
      .btn-normal{
          margin: 10px;
          padding: 5px 24px;
          background-color: #005E85;
          border: none;
          border-radius: 10px;
          font-family: "Mitr";
          font-size: 16pt;
          color: #fff;
          width: 20%;
          height: 70px;
      }
      .btn-normal:hover{
          background-color: #0079ad;
      }
      .btn-normal:active{
          background-color: #005E85;
      }

      
  </style>
</head>
<body class="body">
  <div class="container">
    <center>
      <div>
          <h1>Welcome to E-College</h1>
          <img src="partials/logo.png">
          
          <button class="btn-normal" onclick="window.location.href='studentLogin';">Student Login</button>
          <button class="btn-normal" onclick="window.location.href='staffLogin';">Staff Login</button>
          <button class="btn-normal" onclick="window.location.href='adminLogin';">Admin Login</button>
          <br><br>
          <button class="btn-normal" onclick="window.location.href='help';">Help</button>
      </div>
    </center>
  </div>

</body>
</html>