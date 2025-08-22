// Get the modal
var primr_x_countryModal = document.getElementById("primr_x_countryModal");

// Get the button that opens the modal
var primr_x_openModalBtn = document.getElementById("primr_x_openCountryModalBtn");

// Get the <span> element that closes the modal
var primr_x_closeModalBtn = document.getElementsByClassName("primr_x_modal-close")[0];

// When the user clicks the button, open the modal
primr_x_openModalBtn.onclick = function (e) {
    e.preventDefault();
    primr_x_countryModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
primr_x_closeModalBtn.onclick = function () {
    primr_x_countryModal.style.display = "none";
}

// When the user clicks outside the modal, close it
window.onclick = function (event) {
    if (event.target === primr_x_countryModal) {
        primr_x_countryModal.style.display = "none";
    }
}

// Search filter function
function primr_x_filterCountryList() {
    var input = document.getElementById('primr_x_countrySearchInput');
    var filter = input.value.toUpperCase();
    var ul = document.getElementById("primr_x_countryList");
    var li = ul.getElementsByTagName('li');

    for (var i = 0; i < li.length; i++) {
        var a = li[i].getElementsByTagName("a")[0];
        var txtValue = a.textContent || a.innerText;
        li[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
}



const boxes = document.querySelectorAll('.bt_sst_country_select');

boxes.forEach(box => {
    box.addEventListener('click', function handleClick(e) {
        e.preventDefault();
        primr_x_countryModal.style.display = "none";
        var base_country = '<?php echo esc_js($shop_country) ?>';
        var selected_country_code = this.dataset.country;
        var selected_country = this.textContent;
        document.getElementById("bt_sst_delivery_estimate_country").value = selected_country_code;
        if (base_country != selected_country_code) {

            document.getElementById("pin_input_box").value = selected_country;
            document.getElementById("bt_sst_delivery_base_country").textContent = selected_country;
        } else {
            document.getElementById("pin_input_box").value = "";
            document.getElementById("bt_sst_delivery_base_country").textContent = base_country;
        }
    });
});


