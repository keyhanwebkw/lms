document.addEventListener("DOMContentLoaded", function () {
    const element = document.querySelectorAll(".control-label");
    element.forEach(myFunction);

    function myFunction(item) {
        item.innerHTML = item.innerHTML.replace("*", `<span class="text-danger text-bold">*</span>`);
    }
});

function formatNumber(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    input.value = value;
}
