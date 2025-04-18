const accordians = document.getElementsByClassName('accordian');
// console.log(typeof((accordians)));
for(let i = 1; i<accordians.length;i++){
    accordians[i].addEventListener('click', ()=>{
        accordians[i].classList.toggle("active");
        let panel =accordians[i].nextElementSibling;
        if(panel.style.maxHeight){
            // panel.style.display = "none";
            panel.style.maxHeight = null;

        }else{
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}