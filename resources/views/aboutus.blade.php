@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="form-container">
    <h1 class="display-4">About Us</h1>
    <p class="lead">Empowering senior citizens through innovative activity tracking and support solutions.</p>

    <div class="feature-section">
        <div class="feature">
            <i class="fas fa-chart-line"></i>
            <h5>Insights and Reports : </h5><br>
            <p>Access detailed reports to analyze trends in activity and well-being.</p>
        </div>

        <div class="feature">
            <i class="fas fa-calendar-check"></i>
            <h5>Appointment Scheduling</h5>
            <p>Effortlessly book and manage appointments with service providers tailored to senior citizens' needs.</p>
        </div>
        <div class="feature">
            <i class="fas fa-bell"></i>
            <h5>Reminders</h5>
            <p>Never miss important events, medication times, or scheduled appointments with our smart reminder system.</p>
        </div>
        <div class="feature">
            <i class="fas fa-hand-holding-heart"></i>
            <h5>Personalized Services</h5>
            <p>Access a range of services designed to support the unique needs of senior citizens and their caregivers.</p>
        </div>
        
    </div>
    <!-- Contact Admin -->
   <!-- Contact Section -->
   <div class="contact-us">
        <h3>Contact Us</h3><br>
        <p>If you have any questions or need assistance, please don't hesitate to reach out to us!</p>
<br>
        <div class="contact-details">
            <h4>Our Office</h4>
            <p>
                <strong>Address:</strong> 123 Elder Care Avenue, City Center, Malaysia <br>
                <strong>📞Phone:</strong> +60 123-456-789 <br>
                <strong>Email:</strong> <a href="#" id="emailLink">support@gyccplus.com</a>
            </p>
        </div>

        <!-- <div class="contact-form">
            <h5>Send Us a Message</h5>
            <form id="contactForm">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>

                <label for="message">Your Message:</label>
                <textarea id="message" name="message" class="form-control" placeholder="Write your message here" rows="4" required></textarea>

                <button type="submit" class="btn btn-primary mt-3">Send Message</button>
            </form>
        </div> -->
    </div>
</div>
<!-- JavaScript -->
<!-- Email Modal -->
<div id="emailModal"></div>

<script>
    // Dynamically create the modal
    const emailModal = document.createElement('div');
    emailModal.id = 'emailModal';
    emailModal.style.cssText = `
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        font-family: Arial, sans-serif;
    `;

    emailModal.innerHTML = `
        <div style="
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        ">
            <div style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 15px;
                border-bottom: 1px solid #ddd;
            ">
                <h2 style="
                    margin: 0;
                    font-size: 20px;
                    color: #0056b3;
                ">Contact Administrator</h2>
                <span id="closeModal" style="
                    font-size: 24px;
                    font-weight: bold;
                    color: #aaa;
                    cursor: pointer;
                ">&times;</span>
            </div>
            <div style="
                margin-top: 20px;
                text-align: center;
            ">
                <p style="
                    font-size: 16px;
                    color: #333;
                ">Choose your email provider to send a message to <strong>admin@gyccplus.com</strong>:</p>
                <button class="emailProviderBtn" data-provider="gmail" style="
                    background-color: #0056b3;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    margin: 5px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                ">Gmail</button>
                <button class="emailProviderBtn" data-provider="yahoo" style="
                    background-color: #0056b3;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    margin: 5px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                ">Yahoo Mail</button>
                <button class="emailProviderBtn" data-provider="outlook" style="
                    background-color: #0056b3;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    margin: 5px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                ">Outlook</button>
            </div>
        </div>
    `;

    document.body.appendChild(emailModal);

    // Show the modal
    document.getElementById('emailLink').addEventListener('click', function (event) {
        event.preventDefault();
        emailModal.style.display = 'block';
    });

    // Close the modal
    document.getElementById('closeModal').addEventListener('click', function () {
        emailModal.style.display = 'none';
    });

    // Handle button clicks
    document.querySelectorAll('.emailProviderBtn').forEach((btn) => {
        btn.addEventListener('click', function () {
            const email = 'ainasfea0@gmail.com';
            let url;
            switch (this.dataset.provider) {
                case 'gmail':
                    url = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}`;
                    break;
                case 'yahoo':
                    url = `http://compose.mail.yahoo.com/?to=${email}`;
                    break;
                case 'outlook':
                    url = `https://outlook.live.com/owa/?path=/mail/action/compose&to=${email}`;
                    break;
                default:
                    alert('Invalid provider selected.');
                    return;
            }
            window.open(url, '_blank');
            emailModal.style.display = 'none';
        });
    });

    // Close modal when clicking outside the content
    window.onclick = function (event) {
        if (event.target === emailModal) {
            emailModal.style.display = 'none';
        }
    };
</script>

<style>
   /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9; /* Light, calming background */
    color: #333;
    margin: 0;
    padding: 0;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Poppins', sans-serif;
}

/* Form Container */
.form-container {
    max-width: 900px;
    margin: 50px auto;
    background: linear-gradient(145deg, #d6e4ff, #edf2f7); /* Soft, inviting gradient */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), inset 0 -5px 10px rgba(255, 255, 255, 0.3);
    border: 1px solid #ccd5f0;
    animation: fadeIn 1.5s ease-in-out;
}

/* Contact Section */
.contact-us {
    margin-top: 40px;
    background: #ffffff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.contact-details, .contact-form {
    margin-bottom: 20px;
}

.contact-form input, .contact-form textarea {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.contact-form button {
    background-color: #0056b3;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s;
}

.contact-form button:hover {
    background-color: #003d80;
}

/* Header Section */
h1.display-4 {
    font-size: 2.5rem;
    color: #0056b3;
    text-transform: capitalize;
    letter-spacing: 1.5px;
    text-align: center;
    margin-bottom: 20px;
    animation: fadeIn 1.5s ease-in-out;
}

p.lead {
    font-size: 1.2rem;
    color: #444;
    text-align: center;
    margin-bottom: 30px;
}



/* Styled Inputs and Textareas */
input[type="text"], textarea, select {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0;
    font-size: 1rem;
    border-radius: 10px;
    border: none;
    background: #e8f0fe;
    color: #333;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease-in-out;
}

input[type="text"]:focus, textarea:focus, select:focus {
    outline: none;
    box-shadow: 0 0 5px #0056b3, inset 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Submit Button */
button[type="submit"] {
    background-color: #0056b3;
    border: none;
    padding: 12px 20px;
    font-size: 1rem;
    color: #fff;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
    margin-top: 20px;
    display: inline-block;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #003d80;
    transform: scale(1.05);
}

/* Features Section */
.feature-section {
    margin-top: 30px;
}

.feature {
    display: flex;
    align-items: center;
    background: #edf2f7;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.feature:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.feature i {
    font-size: 2rem;
    color: #0056b3;
    margin-right: 15px;
}

.feature h5 {
    font-size: 1.2rem;
    color: #333;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 20px;
    }

    h1.display-4 {
        font-size: 2rem;
    }

    .feature {
        flex-direction: column;
        text-align: center;
    }

    .feature i {
        margin-bottom: 10px;
    }
}

</style>
@endsection
