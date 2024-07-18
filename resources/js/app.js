import './bootstrap';

const header = document.querySelector(".header");
const burgerIcon = document.querySelector(".burger-icon");
const mobileHeader = document.querySelector(".header-collapse-mobile-wrp");
const body = document.querySelector("body");
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
}


/* --- --- */
document.addEventListener('scroll', function() {
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
