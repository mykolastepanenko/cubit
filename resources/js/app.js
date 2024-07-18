import './bootstrap';

const header = document.querySelector(".header");
const burgerIcon = document.querySelector(".burger-icon");
const mobileHeader = document.querySelector(".header-collapse-mobile-wrp");
const body = document.querySelector("body");
const loaderWrp = document.getElementById("loader-wrp");
const realModal = document.querySelector(".real-modal");
burgerIcon.onclick = () => {
    burgerIcon.classList.toggle("active");
    mobileHeader.classList.toggle("d-none");
    body.classList.toggle("overflow-hidden");
}

const links = document.querySelectorAll(".header-collapse-mobile-wrp .link");

for (let link of links) {
    link.onclick = (event) => {
        burgerIcon.classList.remove("active");
        body.classList.remove("overflow-hidden");
        mobileHeader.classList.add("d-none");
    }
}

/* --- close modal --- */
const modal = document.querySelector(".modal");
const closeModalButton = document.querySelector(".modal .close-button");

closeModalButton.onclick = () => {
    body.classList.remove("overflow-hidden");
    modal.classList.add("d-none");
    loaderWrp.classList.add("d-none")
    realModal.classList.add("d-none")
}


/* --- header background on scroll --- */
document.addEventListener('scroll', function() {
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
/* --- end header background on scroll --- */

/* --- phone mask  --- */
const phoneInput = document.getElementById('phone');
phoneInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // Видаляємо всі нечислові символи
    if (value.length > 12) {
        value = value.slice(0, 12); // Обмежуємо довжину до 12 цифр
    }

    let formattedValue = '+380-';
    if (value.length > 3) {
        formattedValue += value.slice(3, 5) + '-';
    }
    if (value.length > 5) {
        formattedValue += value.slice(5, 8) + '-';
    }
    if (value.length > 8) {
        formattedValue += value.slice(8, 10) + '-';
    }
    if (value.length > 10) {
        formattedValue += value.slice(10, 12);
    }

    e.target.value = formattedValue.trim();
});
/* --- end phone mask --- */

/* --- form sending  --- */
const form = document.querySelector(".form");
const submitButton = document.querySelector(".submit-button");
form.onsubmit = (event) => {
    event.preventDefault();
    modal.classList.remove("d-none");
    loaderWrp.classList.remove("d-none")
    fetch("/api/register", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
                phone: phone.value,
        }),
    })
        .then(res => res.json())
        .then((data)=>{
            const modalDescription = document.querySelector("#modal-description");
            modalDescription.innerText = data.message;

            body.classList.add("overflow-hidden");
            modal.classList.remove("d-none");
        })
        .catch(data => {
            const modalDescription = document.querySelector("#modal-description");
            modalDescription.innerText = data.message;

            body.classList.add("overflow-hidden");
            modal.classList.remove("d-none");
        })
        .finally(()=>{
            phone.value = "";
            loaderWrp.classList.add("d-none")
            realModal.classList.remove("d-none")
        });
}
/* --- end form sending  --- */
