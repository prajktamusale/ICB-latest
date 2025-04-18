const notification = document.getElementsByClassName("notification")[0];
notification.addEventListener('change', () => {
    const notification = document.getElementsByClassName("notification")[0];
    notification.style.display = "block";
    console.log(notification);
    setTimeout((notification)=> {
        console.log(notification);
        notification.style.display = "none";
    }, 3000);
});
setTimeout(()=> {
    const notification = document.getElementsByClassName("notification")[0];
    // notification.classList.add("");
    notification.style.display = "none";
}, 3000);

localStorage.setItem("notification-prev", document.getElementById("notification").innerText);
if()