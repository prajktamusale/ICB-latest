// Getting the elements using query selector
const enrolledEventsHead = document.querySelector('.enrolledEventsHead');
const attendedEventsHead = document.querySelector('.attendedEventsHead');
const enrollments = document.querySelector('.enrollments');
const attendedEvents = document.querySelector('.attendedEvents');

// Adding eventListener to enrolledEventsHead
enrolledEventsHead.addEventListener('click', e=>{
    attendedEvents.classList.add('d-none'); // add display None to all the attentedEvents
    enrollments.classList.remove('d-none'); // Already enrolled events are visible thus we remove class variable d-none
    enrollments.classList.add('d-block'); // Setting display to block
    enrolledEventsHead.classList.remove('active'); //enrolled Events remove any active class
    attendedEventsHead.classList.add('active'); //attented Events add active class
})

// Adding eventListener to attentedeEventsHead
attendedEventsHead.addEventListener('click', e=>{
    enrollments.classList.add('d-none'); //enrollements add display none class
    attendedEvents.classList.remove('d-none'); //attented Events remove display none
    attendedEvents.classList.add('d-block'); // attented events set to display block
    attendedEventsHead.classList.remove('active'); //attentedEventsHead remove active class
    enrolledEventsHead.classList.add('active'); //enrolledEventsHead add active
})