
<html lang="es">
<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="UiuzJblUxq58OYBfS2W6Kh22wuke3odzr4aLaERe">
    <title>Nanaimo PMS | PMS Online para hoteles</title>

    <link rel="stylesheet" type="text/css" href="http://hotelpms.cloud/pms/css/style.css">
  	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <style>
      #logologin{
        height:150px;
        margin-bottom: 30px;
        align-self: center;
      }
      .containerright{
        width:60%;
        float:right;
        right:0;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 10%;
      }
      .containerleft{
        width:40%;
        height:100%;
        float: left;
        border-right: 6px solid #c31e24;
        background-image:url('/Images/login-bg01.jpeg');
        background-size: cover;
        background-repeat: no-repeat;

      }
      .btn {
        background-image: linear-gradient(to right, #dc0c0c, #960b00);
      }


      /*Responsive*/
      @media only screen and (max-width: 800px) {
  div.containerleft {
    display: none;
  }
  .containerright{
    width:100%;
    float:right;
    right:0;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 10%;
    padding-left: 10%;
    padding-right: 10%;
  }
  #logologin{
    width: 80%;
    margin-bottom: 30px;
    align-self: center;
  }
}
   </style>
 </head>
 <body>
 <div class="containerleft">

 </div>
   <div class="containerright">
    <div class="login-content">
    @yield('content')
  </div>
</div>

<script type="text/javascript" src="http://hotelpms.cloud/pms/js/main.js"></script>
</body>
</html>