let postButton = document.querySelector(".postButton");
let postBox = document.querySelector(".post-something");
postBox.style.display = "none";

postButton.addEventListener("click", function () {
    postBox.style.display = "block";
});