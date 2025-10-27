document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll('.counter');
    const options = { threshold: 0.6 }; 
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute("data-target");
                let count = 0;
                const speed = target / 180; 
                const updateCount = () => {
                    if (count < target) {
                        count += speed;
                        counter.innerText = Math.floor(count);
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
                observer.unobserve(counter); 
            }
        });
    }, options);

    counters.forEach(counter => observer.observe(counter));
});









