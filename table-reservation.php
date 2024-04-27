
<?php
    //Set page title
    $pageTitle = 'Table Reservation';

    include "connect.php";
    include 'Includes/functions/functions.php';
    


?>

<style type="text/css">
        .table_reservation_section
        {
            max-width: 850px;
            margin: 50px auto;
            min-height: 500px;
        }

        .check_availability_submit
        {
            background: #ffc851;
            color: white;
            border-color: #ffc851;
            font-family: work sans,sans-serif;
        }
        .client_details_tab  .form-control
        {
            background-color: #fff;
            border-radius: 0;
            padding: 25px 10px;
            box-shadow: none;
            border: 2px solid #eee;
        }

        .client_details_tab  .form-control:focus 
        {
            border-color: #ffc851;
            box-shadow: none;
            outline: none;
        }
        .text_header
        {
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.5;
            margin-top: 22px;
            text-transform: capitalize;
        }
        .layer
        {
            height: 100%;
        background: -moz-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
    background: -webkit-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
    background: linear-gradient(to bottom, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
        }

    </style>

    
<!DOCTYPE HTML>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Marmelad&display=swap" rel="stylesheet">
    <title>Ресторан</title>
    <meta name="description" content="Описание страницы" />
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    
   <div class="container">
    
    <div class="row">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="index.html"> <img src="img/logo.png" style="border: 0;"> </a >
          <a class="navbar-brand fs-1" href="#">Aluna Taste</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fs-4 ">
              
              <li class="nav-item">
                <a class="nav-link" href="menu.html">МЕНЮ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about_us.html">О НАС</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contacts.html">КОНТАКТЫ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="table-reservation.php">БРОНИРОВАТЬ СТОЛИК</a>
              </li>
            
            
            </ul>
           
          </div>
        </div>
      </nav>
      </div>
    </div>
   

    
      



   
   
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-white bg-booking">
      <div class="container">
        
      
        
        
          <div class="col-md-12 p-lg-5 mx-auto my-5 text-center">

            <h1 class="display-4 fw-normal "><br>Забронировать
             
              </h1>
            <p class="lead fw-normal fs-3"> <br><br>Выберите, что бы Вы хотели забронировать или о чём бы хотели узнать подробнее.
               При необходимости наши менеджеры свяжутся с Вами для уточнения всех интересующих Вас деталей.
             
            </p>
            
          </div>
          <div class="product-device shadow-sm d-none d-md-block"></div>
          <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
       
        </div>
           
          </div>



<!-- Модальное окно -->

    
<section class="table_reservation_section">

<div class="container">
    <?php

    if(isset($_POST['submit_table_reservation_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
    {
        // Selected Date and Time

        $selected_date = $_POST['selected_date'];
        $selected_time = $_POST['selected_time'];

        $desired_date = $selected_date." ".$selected_time;

        //Nbr of Guests

        $number_of_guests = $_POST['number_of_guests'];

        //Table ID

        $table_id = $_POST['table_id'];

        //Client Details

        $client_full_name = test_input($_POST['client_full_name']);
        $client_phone_number = test_input($_POST['client_phone_number']);
        $client_email = test_input($_POST['client_email']);

        $con->beginTransaction();
        try
        {
            $stmtgetCurrentClientID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'restaurant_website' AND TABLE_NAME = 'clients'");
    
            $stmtgetCurrentClientID->execute();
            $client_id = $stmtgetCurrentClientID->fetch();

            $stmtClient = $con->prepare("insert into clients(client_name,client_phone,client_email) 
                        values(?,?,?)");
            $stmtClient->execute(array($client_full_name,$client_phone_number,$client_email));

            
            $stmt_reservation = $con->prepare("insert into reservations(date_created, client_id, selected_time, nbr_guests, table_id) values(?, ?, ?, ?, ?)");
            $stmt_reservation->execute(array(Date("Y-m-d H:i"),$client_id[0], $desired_date, $number_of_guests, $table_id));

            
            echo "<div class = 'alert alert-success'>";
                echo "Отлично! Ваша бронь была успешно создана.";
            echo "</div>";

            $con->commit();
        }
        catch(Exception $e)
        {
            $con->rollBack();
            echo "<div class = 'alert alert-danger'>"; 
                echo $e->getMessage();
            echo "</div>";
        }
    }

?>


<div class="container">
  
    <div class="row">
        <div class="col-12 text-center">
            
                <h1>Забронируйте столик</h1><br><br>
                <div class="p fs-4">
                
Проведите вечер в уютной атмосфере нашего ресторана. Забронируйте столик заранее, чтобы гарантировать себе место.
               
            </div>
        </div>
    </div>
</div>



<br>
                        <div class="col-12">   
                                <div class="row g-0">
                                    <div class="col-8 lc-block border-3 border border-light">
                                        <img class="h-100 img-fluid" style="object-fit:cover" src="img/table1.jpg" sizes="(max-width: 1080px) 100vw, 1080px" width="1080" height="768" alt="Photo by Randall  Ruiz" loading="lazy">
                                    </div>
                                    <div class="col-4 lc-block border-3 border border-light">
                                        <img class="h-100 img-fluid" style="object-fit:cover" src="img/table2.jpg" sizes="(max-width: 1080px) 100vw, 1080px" width="1080" height="" alt="Photo by Dylan Shaw" loading="lazy">
                                    </div>
                                </div>
                                <div class="row g-0 ">
                                    <div class="col-4 lc-block border-3 border border-light">
                                        <img class="h-100 img-fluid" style="object-fit:cover" src="img/table3.jpg" sizes="(max-width: 1080px) 100vw, 1080px" width="1080" height="" alt="Photo by Lightscape" loading="lazy">
                                    </div>
                                    <div class="col-8 lc-block border-3 border border-light">
                                        <img class="h-100 img-fluid" style="object-fit:cover" src="img/table4.jpg" sizes="(max-width: 1080px) 100vw, 1080px" width="1080" height="768" alt="Photo by Hitoshi Namura" loading="lazy">
                                    </div>
                                </div>
                                </div>






<div class="container">
  
    <div class="row">
        <div class="col-12">
            <div class="h1 text-center ">
                <br><br>Как забронировать:<br><br>
                <div class="p fs-4">
                

                Заполните форму бронирования ниже, указав дату, время и количество гостей.
Наш менеджер свяжется с вами для подтверждения брони.
Забронировать столик сейчас:
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
    <div class="text_header">
        <span>
            1.Выберите дату и время
            <br>
        </span>
    </div>
    <form method="POST" action="table-reservation.php">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="reservation_date">Дата</label>
                    <input type="date" min="<?php echo (isset($_POST['reservation_date']))?$_POST['reservation_date']:date('Y-m-d',strtotime("+1day"));  ?>" 
                    value = "<?php echo (isset($_POST['reservation_date']))?$_POST['reservation_date']:date('Y-m-d',strtotime("+1day"));  ?>"
                    class="form-control" name="reservation_date">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="reservation_time">Время</label>
                    <input type="time" value="<?php echo (isset($_POST['reservation_time']))?$_POST['reservation_time']:date('H:i');  ?>" class="form-control" name="reservation_time">
                </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="number_of_guests">Сколько человек?</label>
                    <select class="form-control" name="number_of_guests">
                        <option value="1" <?php echo (isset($_POST['number_of_guests']))?"selected":"";  ?>>
                            1 человек
                        </option>
                        <option value="2" <?php echo (isset($_POST['number_of_guests']))?"selected":"";  ?>>Два человека</option>
                        <option value="3" <?php echo (isset($_POST['number_of_guests']))?"selected":"";  ?>>Три человека</option>
                        <option value="4" <?php echo (isset($_POST['number_of_guests']))?"selected":"";  ?>>Четыре человека</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="check_availability" style="visibility: hidden;">Проверьте наличие свободных номеров</label>
                    <input type="submit" class="form-control check_availability_submit" style="margin-top: -13%;" name="check_availability_submit">
                </div>
            </div>
        </div>
    </form>

    <!-- CHECKING AVAILABILITY OF TABLES -->
        
        
    <?php
        if(isset($_POST['check_availability_submit']))
        {
            $selected_date = $_POST['reservation_date'];
            $selected_time = $_POST['reservation_time'];
            $number_of_guests = $_POST['number_of_guests'];

            $stmt = $con->prepare("select table_id
                from tables

                where table_id not in (select t.table_id
                from tables t, reservations r
                where 
                t.table_id = r.table_id
                and 
                date(r.selected_time) = ?
                and liberated = 0
                and canceled = 0)
            ");

            $stmt->execute(array($selected_date));
            $rows = $stmt->fetch();
            
            if($stmt->rowCount() == 0)
            {
                ?>
                    <div class="error_div">
                        <span class="error_message" style="font-size: 16px">ВСЕ СТОЛИКИ ЗАРЕЗЕРВИРОВАНЫ</span>
                    </div>
                <?php
            }
            else
            {
                $table_id = $rows['table_id'];
                ?>
                    <div class="text_header">
                        <span>
                            2. Информация о клиенте
                            <br>
                        </span>
                    </div>
                    <form method="POST" action="table-reservation.php">
                        <input type="hidden" name="selected_date" value="<?php echo $selected_date ?>">
                        <input type="hidden" name="selected_time" value="<?php echo $selected_time ?>">
                        <input type="hidden" name="number_of_guests" value="<?php echo $number_of_guests ?>">
                        <input type="hidden" name="table_id" value="<?php echo $table_id ?>">
                        <div class="client_details_tab">
                            <div class="form-group colum-row row">
                                <div class="col-sm-12">
                                    <input type="text" name="client_full_name" id="client_full_name" oninput="document.getElementById('required_fname').style.display = 'none'" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" class="form-control" placeholder="ФИО">
                                    <div class="invalid-feedback" id="required_fname">
                                    Неверное имя!
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="email" name="client_email" id="client_email" oninput="document.getElementById('required_email').style.display = 'none'" class="form-control" placeholder="E-mail">
                                    <div class="invalid-feedback" id="required_email">
                                    Неверный адрес электронной почты!
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text"  name="client_phone_number" id="client_phone_number" oninput="document.getElementById('required_phone').style.display = 'none'" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Номер телефона">
                                    <div class="invalid-feedback" id="required_phone">
                                    Неверный номер телефона!
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit_table_reservation_form" style="margin-top: 10%;" class="btn btn-info" value="Забронировать столик">
                        </div>
                    </form>
                <?php
            }

        }

    ?>
</div>
</section>










<div class="container">

    <!-- Footer -->
    <footer class="text-center text-lg-start bg-body-tertiary text-muted">
      <!-- Section: Social media -->
      <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
        <!-- Left -->
        <div class="me-5 d-none d-lg-block">
          <span>Общайтесь с нами в социальных сетях:</span>
        </div>
        <!-- Left -->
    
        <!-- Right -->
        <div>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-google"></i>
          </a>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-linkedin"></i>
          </a>
          <a href="" class="me-4 text-reset">
            <i class="fab fa-github"></i>
          </a>
        </div>
        <!-- Right -->
      </section>
      <!-- Section: Social media -->
    
      <!-- Section: Links  -->
      <section class="">
        <div class="container text-center text-md-start mt-5">
          <!-- Grid row -->
          <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
              <!-- Content -->
              <h6 class="text-uppercase fw-bold mb-4">
                <i class="fas fa-gem me-3"></i>Aluna Taste
              </h6>
              <p>
                Ресторан рад всем гостям.У вас есть возможность посетить нас как кафе для дня рождения, крестин. Наша летняя терраса расположена на втором этаже рядом с банкетным залом и добавит атмосферы в тёплые сезоны. Звоните, узнавайте свободные даты!
              </p>
            </div>
            <!-- Grid column -->
    
            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
              <!-- Links -->
             
              <p>
                <a href="#!" class="text-reset">Рестораны</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Проекты</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Мероприятия</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Новости</a>
              </p>
            </div>
            <!-- Grid column -->
    
            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
              <!-- Links -->
             
              <p>
                <a href="#!" class="text-reset">О нас</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Контакты</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Забронировать стол</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Aluna Pay</a>
              </p>
            </div>
            <!-- Grid column -->
    
            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
              <!-- Links -->
              
              <p><i class="fas fa-home me-3"></i> +7 (812) 640-16-16</p>
              <p>
                <i class="fas fa-envelope me-3"></i>
                mogistermel@gmail.com
              </p>
              <p><i class="fas fa-phone me-3"></i>Ижевск, Красноармейская., д. 3, стр.1</p>
              
            </div>
            <!-- Grid column -->
          </div>
          <!-- Grid row -->
        </div>
      </section>
      <!-- Section: Links  -->
    
      <!-- Copyright -->
      <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2021 Copyright:
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">Aluna Taste</a>
      </div>
      <!-- Copyright -->
    </footer>
    <!-- Footer -->
    
            </div>







           











<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>







</body>

</html>