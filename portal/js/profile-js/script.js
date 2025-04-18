const btn = document.getElementById('btn-edit');


btn.addEventListener('click', () => {

  const editDiv = document.getElementById('edit');

  if (editDiv.style.display === "none") {

    editDiv.style.display = "block";

  } else {
      
    editDiv.style.display = "none";
  }


});

btn.addEventListener('click', () => {

    const form = document.getElementById('form');
    if (form.style.display === 'none') {
   
        form.style.display = 'block';
      } else {
       
        form.style.display = 'none';
      }

});

btn.addEventListener('click', () => {

    const save = document.getElementById('save');
    const cancel = document.getElementById('cancel');

    if (save.style.display === 'none') {
   
        save.style.display = 'block';
      } else {
       
        save.style.display = 'none';
      }

      if (cancel.style.display === 'none') {
   
        cancel.style.display = 'block';
      } else {
       
        cancel.style.display = 'none';
      }


});

 


