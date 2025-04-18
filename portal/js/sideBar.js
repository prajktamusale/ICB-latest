// Getting the elements using query Selector
const sideBar = document.querySelector('.sideBar'); // getting sideBar Element
const hamBurger = document.querySelector('.hamBurger'); 
const cross = document.querySelector('.cross');
const body = document.getElementsByTagName('body')[0];
// const accordians = document.getElementsByClassName('accordian');

//Adding hidSideBar class to hide the naviagtion bar
sideBar.classList.add('hideSideBar');

//Adding EventListener to
hamBurger.addEventListener('click', e=>{
    sideBar.classList.remove('hideSideBar'); // remove hideSideBar
    sideBar.classList.add('displaySideBar'); // add displaySideBar
    body.classList.add('fixed-position'); // add fixed-position
    setTimeout(()=>{    // This is to delay the change in hamburger sign into cross by 500 milli second
        cross.style.display = 'flex';
    }, 500);
})

// Adding EventListener to Cross
cross.addEventListener('click', e=>{
    sideBar.classList.add('hideSideBar'); // add hideSideBar
    sideBar.classList.remove('displaySideBar'); // remove displaySideBar
    body.classList.remove('fixed-position'); // remove fixed-position
    cross.style.display = 'none'; // Set cross display style as flex
});

