// 1. Switching the tabs between prospectus and sold table
const sale_tab_1 = document.getElementById("sale-tab-1");
const sale_tab_2 = document.getElementById("sale-tab-2");

sale_tab_1.addEventListener("click", () =>{
    const sale_tab_1 = document.getElementById("sale-tab-1");
    const sale_tab_2 = document.getElementById("sale-tab-2");
    sale_tab_1.classList.add("active-option");
    sale_tab_2.classList.remove("active-option");
    const table1 = document.getElementById("table-1");
    const table2 = document.getElementById("table-2");
    table1.classList.remove("table-hide");
    table2.classList.add("table-hide");
});

sale_tab_2.addEventListener("click", () =>{
    const sale_tab_1 = document.getElementById("sale-tab-1");
    const sale_tab_2 = document.getElementById("sale-tab-2");
    sale_tab_1.classList.remove("active-option");
    sale_tab_2.classList.add("active-option");
    const table1 = document.getElementById("table-1");
    const table2 = document.getElementById("table-2");
    table2.classList.remove("table-hide");
    table1.classList.add("table-hide");
});

// 2. Fetching the product details
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
// console.log("database/getProductList.php?id="+localStorage.getItem('id'));
xmlhttp.open("GET","database/getProductList.php?id="+localStorage.getItem('id'),true); // Change the user id 
xmlhttp.send();

// 3.1 Add details : Form open
document.getElementsByClassName("add_new")[0].addEventListener('click', () => {
    document.getElementsByClassName("add-details")[0].style.display="block";
});

// 3.2 Add details: Form close
document.getElementById("close_add-details").addEventListener('click', () => {
  // console.log(document.getElementById("close_add-details"));
  document.getElementsByClassName("add-details")[0].style.display="none";
});
    // document.getElementsByClassName("close_add-details")[0].addEventListener('click', () => {
    //     document.getElementsByClassName("add-details")[0].style.display="none";
    // })

// 4.1 Edit form close
document.getElementById('edit_form_close').addEventListener('click', () => {
  document.getElementById('edit-form-details').style.display='none';
});

// 4.2 Edit form close
document.getElementById('show_form_close').addEventListener('click', () => {
  document.getElementById('show-form-details').style.display='none';
});

// 5. Prospectus edit and details functionality
let order_ids = []; // array to store order ids

// 5.1 Fetching all orders related to user id
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    let orders = JSON.parse(this.responseText);    
    // console.log(orders);
    const prospectus = document.getElementById('prospectus');
    for(let key in orders){
      const data = orders[key]; // selecting individual order
      // console.log(data);
      // <span class="prospectus_field_name">Customer Details&nbsp;</span>
      let order_status = data['status']=='Closed'?2:data['status']=="Pitching"?1:0; // Variable representing order status
      // Prospectus table order details
      prospectus.innerHTML+=`
      <div class='tr tr_propspectus_order'>
        <div class="prospectus_product_image">
          <img src="./images/pexels-alex-andrews-821651.jpg" alt="" class="prospectus_product_image__img">
        </div>
        <div class="prospectus_product_details tr">
          <div class='td prospectus_product_name'><span>${data['product_name']}</span><span>${data['product_id']}</span></div>
          <div class='td prospectus_qty'><span class="prospectus_field_name">Qty&nbsp;</span>${data['quantity']}</div>
          <div class='td prospectus_cust_id'>${data['customer_id']}</div>
          <div class='td prospectus_cust_name'>${data['customer_name']}</div>
          <div class='td prospectus_phone customer_contact'><a href="tel:+91${data['whatsapp']} target="blank"">+91&nbsp;${data['whatsapp']}</a><a href="http://wa.me/91${data['whatsapp']}" target="_blank"><img src="./images/whatsapp.png" class="whatsapp-image"/></a></div>
          <div class='td prospectus_email'><a href="mailto:${data['email']}">${data['email']}</a></div>
          <div class='td prospectus_status'>
            <select name="status" id="prospectus_status_${data['order_id']}" class="prospectus_table_select select_features_${order_status}">
              <option value="0" ${order_status==0?"selected":""} style="background:red;">Cancelled</option>
              <option value="1" ${order_status==1?"selected":""} style="background:yellow;">Pitching</option>
              <option value="2" ${order_status==2?"selected":""} style="background:lightgreen;">Closed</option>
            </select>
          </div>
          <div class='td customer_contact prospectus_options'>
            <button class="btn-prospectus btn-prospectus-editing">
              <img src="./images/editing.png" class="prospectus-icons"/>
              <span>Edit</span>
            </button>
            
            <button class="btn-prospectus btn-prospectus-expand">
              <img src="./images/expand.png" class="prospectus-icons"/>
              <span>Details</span>
            </button>
          </div>
        </div>
      </div>
      `;
      order_ids.push(data['order_id']);
    }

    // 5.2 Adding eventListners to the orders in prospectus.
    // Updating status of the order from drop down list
    for(let i=0;i<order_ids.length;i++){
      // console.log(document.getElementById(`prospectus_status_${order_ids[i]}`));
      document.getElementById(`prospectus_status_${order_ids[i]}`).addEventListener('change', () =>{
      var statusUpdate = new XMLHttpRequest();
      statusUpdate.onreadystatechange = () => {
        if(this.readyState == 4 && this.status == 200){}
      };
    
      // Getting the order element
      const order_element = document.getElementById(`prospectus_status_${order_ids[i]}`);
      // Dummy url example
      // let url = `database/updateProspectusStatus.php?orderId=${order_ids[i]}&value=${order_element.value}`;
      statusUpdate.open('post', "database/updateProspectusStatus.php", true);
      statusUpdate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      statusUpdate.send(`orderId=${order_ids[i]}&value=${order_element.value}`);
      });
    }

    // 5.3 Edit Button in Prospectus
    const edit_buttons =  document.getElementsByClassName('btn-prospectus-editing');
    // Details view
    const details_buttons = document.getElementsByClassName('btn-prospectus-expand');
    for(let i=0;i<edit_buttons.length;i++){
      const btn = edit_buttons.item(i);
      const details = details_buttons.item(i);
      btn.addEventListener('click', () => {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let editForm = document.getElementById('edit-form-details');
        editForm.style.display = "block";
        let data = JSON.parse(this.responseText);  

        // Product ID
        document.getElementById("edit_image").src="./images/pexels-alex-andrews-821651.jpg";
        document.getElementById("ordId").value=data["order_id"];
        document.getElementById("ordId").style.display="none";
        document.getElementById("edit_order_area__details__product_id__details").innerText = data["id"];
        // Product Name
        document.getElementById("edit_order_area__details__product_name").innerText = data["product_name"];
        // Quantity
        // document.getElementById("edit_order_area__details__product_qty") = data["quantity"];
        document.getElementById("edit_order_area__details__product_qty__product_qty").value = Number(data["quantity"]);
        // {"id":"101", "product_name": "itr tax", "quantity": "1", "customer_name": "Temp", "whatsapp": "2147483647" , "email": "temp@gmail.com", "state": "Mahrasthra", "city":"Pune", "address":"Karve Nagar", "pin":"411001"}
        document.getElementById("edit_customer_details__customer_name").value = data["customer_name"];
        document.getElementById("edit_customer_details__whatsapp").value = data["whatsapp"];
        document.getElementById("edit_customer_details__email").value = data["email"];
        document.getElementById("edit_customer_details__state").value = data["state"];
        document.getElementById("edit_customer_details__city").value = data["city"];
        document.getElementById("edit_customer_details__address").value = data["address"];
        document.getElementById("edit_customer_details__pin").value = data["pin"];
        document.getElementById('customer_id').value = data["cust_id"];
        document.getElementById('customer_id').style.display = "none";
      }
      };
      xmlhttp.open("GET",`database/fetcheditDetailsProspectus.php?ordId=${order_ids[i]}`,true);
      xmlhttp.send();
      });

      details.addEventListener('click', () => {
      var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let showForm = document.getElementById('show-form-details');
        showForm.style.display = "block";
        let data = JSON.parse(this.responseText);
        

        // Product ID
        // document.getElementById("ordId").value=data["order_id"];
        // document.getElementById("ordId").style.display="none";
        console.log(document.getElementById("details_image"));
        document.getElementById("details_image").src="./images/pexels-alex-andrews-821651.jpg";
        document.getElementById("show_order_area__details__product_id__details").innerText = data["id"];
        // Product Name
        document.getElementById("show_order_area__details__product_name").innerText = data["product_name"];
        // Quantity
        // document.getElementById("edit_order_area__details__product_qty") = data["quantity"];
        document.getElementById("show_order_area__details__product_qty__product_qty").innerText = Number(data["quantity"]);
        // {"id":"101", "product_name": "itr tax", "quantity": "1", "customer_name": "Temp", "whatsapp": "2147483647" , "email": "temp@gmail.com", "state": "Mahrasthra", "city":"Pune", "address":"Karve Nagar", "pin":"411001"}
        document.getElementById("show_customer_details__customer_name").innerText = data["customer_name"];
        document.getElementById("show_customer_details__whatsapp").innerText = data["whatsapp"];
        document.getElementById("show_customer_details__email").innerText = data["email"];
        document.getElementById("show_customer_details__state").innerText = data["state"];
        document.getElementById("show_customer_details__city").innerText = data["city"];
        document.getElementById("show_customer_details__address").innerText = data["address"];
        document.getElementById("show_customer_details__pin").innerText = data["pin"];
      }
    };
    xmlhttp.open("GET",`database/fetcheditDetailsProspectus.php?ordId=${order_ids[i]}`,true);
    xmlhttp.send();
          });
        }
      }
    };
    xmlhttp.open("GET","database/getProspectusData.php",true);
    xmlhttp.send();


    // console.log(document.getElementsByClassName('.btn-prospectus-editing'));
    // console.log(document.getElementById('order_edit_form').action);


    // Get Details from sold database for products
    // const fetch_sold_data = 

  document.getElementById('sale-tab-2').addEventListener('click', () => {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // console.log(this.responseText);
        const data = JSON.parse(this.responseText);
        const sold_table = document.getElementById("sold_table_data");
        sold_table.innerHTML="";
        // console.log(Object.keys(data).length);
        Object.keys(data).forEach( key => {
          const payment_message = data[key].status==0?"Pay":"Paid";
          const payment_btn_active = data[key].status==0?"":"disabled";
          const status = data[key].status==0?"Pending":"Paid";
          sold_table.innerHTML+=`<div class='tr tr_propspectus_order' id="sold_table_responsive">
          <div class="prospectus_product_image"><img src="./images/pexels-alex-andrews-821651.jpg" alt="" class="prospectus_product_image__img"></div>
          <div class='td sold_check_column'><input type='checkbox' value='${data[key].order_id}' name='${data[key].order_id}' for='${data[key].order_id}' ${payment_btn_active}/></div>
          <div class="prospectus_product_details tr">
          <div class='td sold_product_name'>${data[key].product_name}</div>
          <div class='td sold_quantity'><span class="prospectus_field_name">Qty&nbsp;</span>${data[key].quantity}</div>
          <div class='td sold_customer_id'>${data[key].customer_id}</div>
          <div class='td sold_customer_details'>
            <div class="sold_customer_details_all">
            <div class="sold_customer_details_visible">
              <div class="sold_customer_details__name">${data[key].customer_name}</div>
              <button type="button" class="form_btn customer_details_icon" id="btn_${data[key].order_id}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M6 10l6 6l6 -6h-12"></path>
                </svg>
              </button>
            </div>
            <div class="sold_customer_details_hidden" id="details_id_${data[key].order_id}">
              <div class="customer_email"><a href="mailto:${data[key].email}" link="_blank">${data[key].email}</a></div>
              <div class="customer_email"><a href="tel:+91${data[key].phone}">+91 ${data[key].phone}</a></div>
            </div>
            </div>
          </div>
          <div class='td sold_total_price'>₹ ${data[key].total_price}</div>
          <div class='td sold_pay'><input type="button" class="form_btn" value="${payment_message}" ${payment_btn_active}></div>
          <div class='td sold_status'>${status}</div>
          </div>
          </div>
          `;
        });

      
        // From here
        // document.getElementById(`btn_${data[key].order_id}`).addEventListener('click', ()=>{
          // document.getElementById(`details_id_${data[key].order_id}`).classList.toggle('detail_display');
        Object.keys(data).forEach(key => {
          document.getElementById(`btn_${data[key].order_id}`).addEventListener('click', ()=>{
          document.getElementById(`details_id_${data[key].order_id}`).classList.toggle('detail_display');
          document.getElementById(`btn_${data[key].order_id}`).classList.toggle('btn_rotate');
          });
        });
      }


    };
    xmlhttp.open("GET",`database/getSoldData.php`,true);
    xmlhttp.send();
  });