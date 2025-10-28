let slider = document.querySelector(".slider .list");
let items = document.querySelectorAll(".slider .list .item");
let dots = document.querySelectorAll(".slider .dots li");

let lengthItems = items.length - 1;
let active = 0;

let refreshInterval = setInterval(() => {
  active = active + 1 <= lengthItems ? active + 1 : 0;
  reloadSlider();
}, 3000);

function reloadSlider() {
  slider.style.left = -items[active].offsetLeft + "px";
  let last_active_dot = document.querySelector(".slider .dots li.active");
  if (last_active_dot) {
    last_active_dot.classList.remove("active");
  }
  dots[active].classList.add("active");

  clearInterval(refreshInterval);
  refreshInterval = setInterval(() => {
    active = active + 1 <= lengthItems ? active + 1 : 0;
    reloadSlider();
  }, 3000);
}

dots.forEach((li, key) => {
  li.addEventListener("click", () => {
    active = key;
    reloadSlider();
  });
});

window.onresize = function (event) {
  reloadSlider();
};

reloadSlider();
