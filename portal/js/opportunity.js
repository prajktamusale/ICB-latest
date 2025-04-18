const containerOpportunities = document.querySelector('.opportunities'); // Selecting the opportunities class
const slides = document.querySelector('.slides');// Seleceting the slides class
const dots = document.querySelector('.dots'); //selecting the dots class
let slideIndex = 1; // Setting slider index to 1

// Next/previous controls
// Function to increment or decrement sliderIndex value.
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
// Function to set the currentSlide
function currentSlide(n) {
  showSlides(slideIndex = n);
}

//
function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1} // setting sliderIndex as 1 as next reaches n+1 or more.
  if (n < 1) {slideIndex = slides.length} // setting sliderIndex as last slide when -1 or less sliderIndex value is obtained.
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  // Updating dots beneth slider
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  // Slider style attributes
  slides[slideIndex-1].style.display = "flex";
  slides[slideIndex-1].style.justifyContent = "center";
  dots[slideIndex-1].className += " active";
}

// Calling showSlides with parameter as slideIndex
showSlides(slideIndex);

// Automatic increment of sliderIndex in 5 seconds
setInterval(()=>{
  showSlides(slideIndex += 1);
}, 5000)