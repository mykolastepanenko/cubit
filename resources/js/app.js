import './bootstrap';

const header = document.querySelector(".header");
const burgerIcon = document.querySelector(".burger-icon");
const mobileHeader = document.querySelector(".header-collapse-mobile-wrp");
const body = document.querySelector("body");
burgerIcon.onclick = () => {
    burgerIcon.classList.toggle("open");
    mobileHeader.classList.toggle("d-none");
    body.classList.toggle("overflow-hidden");
}

const links = document.querySelectorAll(".header-collapse-mobile-wrp .link");

for (let link of links) {
    link.onclick = (event) => {
        burgerIcon.classList.remove("open");
        body.classList.remove("overflow-hidden");
        mobileHeader.classList.add("d-none");
    }
}
