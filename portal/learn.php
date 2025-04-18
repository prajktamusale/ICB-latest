<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
    header("Location: ./index.php");
  }
  if (!isset($_SESSION['type'])){
    header("Location: ../index.html");
  }
  $searchOn = ''
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Learn: Care For Bharat</title>
    <link rel="stylesheet" href="./css/donate.css" />
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
        <?php include "./css/search.css" ?>
        <?php include "./css/course_card.css" ?>
        <?php include "./css/learn.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/coursesSlider.js" defer></script>
</head>
  <body>
    <!-- Navigation Bar -->
    <?php
      include './header.php';
    ?>

    <section class="course_banner">
      <div class="course_banner__top">
        <div class="course_banner__top__btn course_banner__top__btn_prev" id="course_banner__top__btn_prev">
          <img class="course_banner__top__btn__image btn-rotate-180" src='./images/next-btn.png' alt='slider_before' />
        </div>        
        <div class="course_banner__top__display" id="course_banner__top__display">
          <div class="course_banner__top__images" id="course_banner__top__images">
          <?php 
            // Course array
            $courses_banner = array(["Course1", "./images/DummyCourseBanner 1.jpg", "course_details.php?cid=121"],["Course2", "./images/DummyCourseBanner 2.jpg", "course_details.php?cid=122"], ["Course3", "./images/DummyCourseBanner 3.jpg", "course_details.php?cid=123"]);
            for($i = 0; $i<count($courses_banner); $i++){
          ?>
          <!-- <a href="<?php echo $courses_banner[$i][2];?>"> -->
            <img class="course_banner__top__course_image" src="<?php echo $courses_banner[$i][1]; ?>" alt=<?php echo $courses_banner[$i][0];?> />
          <?php } ?>
            <!-- </a> -->
        </div>
        </div>
        <div class="course_banner__top__btn course_banner__top__btn_next" id="course_banner__top__btn_next">
        <img class="course_banner__top__btn__image" src='./images/next-btn.png' alt='slider_before' />
        </div> 

      </div>
      <div class="course_banner__pagination">
        <ul class="course_banner__pagination__ul">
          <?php for($i = 0; $i<count($courses_banner); $i++){
            echo "<li class='course_banner__pagination__ul__li'></li>";
          }
          ?>
        </ul>
      </div>
    </section>

    <section class="display_courses">
      <h2 class="display_courses__title">Learn New Skills</h2>
      <div class="display_courses__slider">
        <div class='slider_btn slider_prev' id="learn_new_skills_prev"><img src='./images/next-btn.png' alt='slider_before' /></div>
        <div class="display_courses__slider__course_display" id="learn_new_skills_display">
          <div class="display_courses__slider__course" id="learn_new_skills_slider">
              <?php for($i=0;$i<9;$i++){ 
                if($i%2 == 0){
                 $image_url = "./images/undefined6.png";
                }else{
                  $image_url = "./images/pexels-alex-andrews-821651.jpg";
                }
                $course_card_class_name = "learn_new_skills_card";
                ?>
              <?php include "./components/course_card.php"?>
              <?php }; ?>
          </div>
        </div>
        <div class='slider_btn' id="learn_new_skills_next"><img src='./images/next-btn.png' alt='slider_before' /></div>
      </div>
    </section>


    <section class="display_courses">
      <h2 class="display_courses__title">My Skill Backpack</h2>
      <div class="display_courses__slider">
        <div class='slider_btn slider_prev' id="my_skill_backpack_prev"><img src='./images/next-btn.png' alt='slider_before' /></div>
        <div class="display_courses__slider__course_display" id="my_skill_backpack_display">
          <div class="display_courses__slider__course" id="my_skill_backpack_slider">
              <?php for($i=0;$i<9;$i++){ 
                if($i%2 != 0){
                 $image_url = "./images/undefined6.png";
                }else{
                  $image_url = "./images/pexels-alex-andrews-821651.jpg";
                }
                $course_action = "Continue";
                $course_card_class_name = "my_skill_backpack_card";
                // $course_card_id = 
                ?>
              <?php include "./components/course_card.php"?>
              <?php }; ?>
          </div>
        </div>
        <div class='slider_btn' id="my_skill_backpack_next"><img src='./images/next-btn.png' alt='slider_before' /></div>
      </div>
    </section>

<?php include "./components/bottomNav.php";?>
  </body>
</html>
