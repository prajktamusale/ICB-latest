const searchInput = document.getElementById('search_input');
searchInput.addEventListener('keydown', (e)=>{
    $search_item = document.getElementById('search_input').value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let elements = "";
      let products = JSON.parse(this.responseText);
      for(const key in products){
        elements+=`<option value='${key}'>${key}</option>`;
      }
      document.getElementById("product_id").innerHTML = elements;
    }
  };
  xmlhttp.open("GET","database/getProductList.php?id=10000003",true);
  xmlhttp.send();
})