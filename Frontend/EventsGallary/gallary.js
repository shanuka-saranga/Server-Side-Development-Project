document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".filter-btn");
    const items = document.querySelectorAll(".gallery-item");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const filter = button.getAttribute("data-filter");

            items.forEach(item => {
                if (filter === "all" || item.classList.contains(filter)) {
                    item.classList.add("show");
                } else {
                    item.classList.remove("show");
                }
            });
        });
    });

    // Show all items on page load
    document.querySelector('[data-filter="all"]').click();
});

// Select all filter buttons
const filterButtons = document.querySelectorAll('.filter-btn');

// Add click event listener to each button
filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove 'active' class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));

        // Add 'active' class to the clicked button
        button.classList.add('active');
    });
});


function newclick() {
    alert("this is party");
}