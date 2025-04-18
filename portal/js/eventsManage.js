let infoHidden = document.querySelector('.setInfo');
let info = document.querySelector('.hiddenText')

infoHidden.addEventListener('mouseover',()=>{
	info.style.display = "block";
	setTimeout(()=>{info.style.display = "none"},3000)
})

// Admin Event Page

let upButton = document.querySelector('.upButton');
let pastButton = document.querySelector('.pastButton');
let upBox = document.querySelector('.upcomingEvent');
let pastBox = document.querySelector('.pastEvent');

function active(act,deact){
	if(act == pastButton){
		pastBox.style.display = "block";
		upBox.style.display = "none";
	}
	else{
		upBox.style.display = "block";
		pastBox.style.display = "none";
	}
    act.style.cssText += 'border-radius:0;border-top-left-radius: 10px;border-top-right-radius: 10px;background-color: #D9D9D9;';
    deact.style.cssText += 'border-radius: 0px;background-color: #fff;';
}
active(upButton,pastButton);

upButton.addEventListener('click',()=>{
    active(upButton,pastButton);
})

pastButton.addEventListener('click',()=>{
	active(pastButton,upButton);
})
