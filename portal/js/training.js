const enrolledTrainingsHead = document.querySelector('.enrolledTrainingsHead');
const completedTrainingsHead = document.querySelector('.completedTrainingsHead');
const enrollments = document.querySelector('.enrollments');
const completedTrainings = document.querySelector('.completedTrainings');

enrolledTrainingsHead.addEventListener('click', e=>{
    completedTrainings.classList.add('d-none');
    enrollments.classList.remove('d-none');
    enrollments.classList.add('d-block');
    enrolledTrainingsHead.classList.remove('active');
    completedTrainingsHead.classList.add('active');
})

completedTrainingsHead.addEventListener('click', e=>{
    enrollments.classList.add('d-none');
    completedTrainings.classList.remove('d-none');
    completedTrainings.classList.add('d-block');
    completedTrainingsHead.classList.remove('active');
    enrolledTrainingsHead.classList.add('active');
})