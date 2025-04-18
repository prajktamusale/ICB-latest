<!-- <?php 
if(!isset($image_url)){
    $image_url = "./images/pexels-alex-andrews-821651.jpg";
}
if(!isset($course_action)){
    $course_action = "Manage";
}
if(!isset($course_card_class_name)){
    $course_card_class_name = "$course_card_101";
}
?> -->

<div class="course_card <?php echo $course_card_class_name; ?>">
    <img src="<?php echo $image_url;?>" alt="<?php echo "course_image_alt" ?>" class="course_card_image">
    <div class="course_card__details">
        <div class="course_card__details__name"><?php echo "Course Name";?></div>
        <div class="course_card__details__statistics">
            <div class="course_card__details__statistics__enrolled"><?php echo "200+";?></div>
            <div class="course_card__details__statistics__rating"><?php echo "4.0";?></div>
        </div>
        <div class="course_card__details__btns">
            <!-- <button class="course_card__details__btns__enroll">Enroll</button> -->
            <button class="<?php echo $course_action=="Manage"? "course_card__details__btns__details":"course_card__details__btns__enroll";?>"><?php echo $course_action; ?></button>
        </div>
    </div>
</div>