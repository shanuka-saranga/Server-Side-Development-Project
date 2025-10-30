let currentStep = 1;

        function showStep(step) {
            document.querySelectorAll(".form-step").forEach((el, i) => {
                el.classList.toggle("active", i === step - 1);
            });
            document.querySelectorAll(".step").forEach((el, i) => {
                el.classList.toggle("active", i < step);
            });
            updateSummary();
        }

        function nextStep() {
            if (currentStep < 3) currentStep++;
            showStep(currentStep);
        }

        function prevStep() {
            if (currentStep > 1) currentStep--;
            showStep(currentStep);
        }

        function updateSummary() {
            document.getElementById("sumName").textContent = document.getElementById("userName").value;
            document.getElementById("sumEmail").textContent = document.getElementById("userEmail").value;
            document.getElementById("sumPhone").textContent = document.getElementById("userTel").value;
            document.getElementById("sumEvent").textContent = document.getElementById("userEvent").value;
            document.getElementById("sumGuests").textContent = document.getElementById("guestCount").value;
            document.getElementById("sumStart").textContent = document.getElementById("eventStart").value.replace("T", " ");
            document.getElementById("sumEnd").textContent = document.getElementById("eventEnd").value.replace("T", " ");
            document.getElementById("sumDesc").textContent = document.getElementById("eventDesc").value;
        }