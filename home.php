<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavBar Component</title>
    <script src="https://kit.fontawesome.com/5bafccf36f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.emailjs.com/dist/email.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="nav">
        <img class="logo" src="public\images\logo.png" alt="logo">
        <ul class="ul">
            <li class="li" onclick="scrollToSection('Home')">Home</li>
            <li class="li" onclick="scrollToSection('Recipe')">Recipe</li>
            <li class="li" onclick="scrollToSection('About')">About</li>
            <li class="li" onclick="scrollToSection('Contact')">Contact</li>
        </ul>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-menu">
                <i class="fas fa-user user-icon" onclick="toggleDropdown()"></i>
                <div class="dropdown" id="dropdownMenu">
                    <p><?php echo $_SESSION['user']; ?></p>
                    <p><?php echo $_SESSION['email']; ?></p>
                    <button class="logout-btn" onclick="logout()">Logout</button>
                </div>
            </div>
        <?php else: ?>
            <button class="btn" onclick="navigate()">Login</button>
        <?php endif; ?>
        
        <div class="menu-icon" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
    <!-- Home -->
        
    <section id="Home">
        <div class="Home">
            <div class="Container-1">
                <h2>Hey there!</h2>
                <h1>Here We Share Our Recipes With You</h1>
                <p>Join us on a flavorful adventure as we bring together food lovers from all walks of life. Our platform is designed for you to explore a wide range of recipes, from quick weeknight dinners to elaborate holiday feasts. Share your culinary masterpieces, discover new favorites, and connect with fellow cooking enthusiasts. Let's create delicious memories together!</p>
            </div>
            <div class="Container-2">
                <img src="public/images/chef1-removebg-preview.png" alt="chef">
            </div>
        </div>
    </section>

    <!-- Slider -->
    
    <section id="Recipe">
        <div class="Recipe">
            <h1 class="heading">Popular Recipes</h1>
            <p class="heading1">Click me</p>
            <div class="slider">
                <img src="public/images/cheeseCake.webp" alt="cheese cake" id="cheeseCake" onclick="sendEmail('one')">
                <img src="public/images/chickenBiriyani.webp" alt="chicken biriyani" id="chickenBiriyani" onclick="sendEmail('two')">
                <img src="public/images/chocolateBrownie.webp" alt="chocolate brownie" id="chocolateBrownie" onclick="sendEmail('three')">
                <img src="public/images/cpizza.webp" alt="pizza" id="pizza" onclick="sendEmail('four')">
                <img src="public/images/friedEggsAvacado.webp" alt="fried eggs with avocado" id="friedEggsAvacado" onclick="sendEmail('five')">
                <img src="public/images/pancake.webp" alt="Pancake" id="pancake" onclick="sendEmail('six')">
                <img src="public/images/ramen.webp" alt="Ramen" id="ramen" onclick="sendEmail('seven')">
            </div>
        </div>
        
        <!-- Toast Notification -->
        <div id="toast" class="toast">Email sent successfully!</div>


    </section>
    
    <!-- Recipe section  -->
    <h1 class="heading">Global Flavors</h1>
    <input type="text" placeholder="Search for a recipe..." class="search-bar" id="search-bar" />

    <p class="no-recipes-message" id="no-recipes-message" style="display: none;">No recipes found. Try a different search term.</p>

    <ul class="recipe-list" id="recipe-list"></ul>

    <div class="popup" id="popup">
        <div class="popup-content">
            <span class="close" id="close-popup">&times;</span>
            <h2 id="popup-title"></h2>
            <p><strong>Ingredients:</strong> <span id="popup-ingredients"></span></p>
            <p><strong>Preparation:</strong></p>
            <ul class="preparation-list" id="popup-preparation-list"></ul>
        </div>
    </div>

    <!-- Parallax -->
    <div class="parallax">
            <div class="parallax-content">
                <h1>A recipe is a journey; each step is a new adventure in the kitchen.</h1>
            </div>
    </div>

    <!-- About -->
    <div class="about-container" id="About">
        <div class="about-container1">
            <h4>Hello!!</h4>
            <p>I'm the chef and creator behind the recipes on this site, with a mission to bring global flavors to your kitchen. Each recipe is crafted to be accessible, easy to follow, and designed to make every meal a joyful experience. Food, to me, is more than nourishment; it’s a way to connect, uplift, and bring happiness to those around us. I hope these recipes inspire creativity and culinary joy, transforming simple ingredients into extraordinary dishes. Let’s cook, share, and enjoy the journey of delicious food together. Welcome, and thank you for letting me be part of your kitchen!</p>
        </div>
        <div class="about-container2">
            <img src="public\images\about-chef.png" alt="chefimage">
        </div>
    </div>  
    <section class="reviews">
        <h2>User Reviews</h2>
        <div class="review-container">
            <div class="review">
                <img src="public\images\person1.jpeg" alt="person1" class="profile-pic">
                <h3>Alex</h3>
                <p>"This recipe transformed my dinner party! Everyone loved it, and it was so easy to follow. Highly recommend!"</p>
                <div class="stars">⭐⭐⭐⭐</div>
            </div>

            <div class="review">
                <img src="public\images\person2.jpeg" alt="person2" class="profile-pic">
                <h3>Michael</h3>
                <p>"Absolutely delicious! I never thought I could make something this good at home. Thank you!"</p><br>
                <div class="stars">⭐⭐⭐⭐</div>
            </div>

            <div class="review">
                <img src="public\images\preson3.jpeg" alt="person3" class="profile-pic">
                <h3>Jessica</h3>
                <p>"A perfect blend of flavors! My family enjoyed every bite. I’ll definitely be trying more recipes!"</p>
                <div class="stars">⭐⭐⭐⭐⭐</div>
            </div>

            <div class="review">
                <img src="public\images\person4.jpeg" alt="person4" class="profile-pic">
                <h3>David</h3>
                <p>"Simple and delicious! The instructions were clear, and the dish turned out fantastic. I’m a fan!"</p>
                <div class="stars">⭐⭐⭐⭐⭐</div>
            </div>
            
        </div>
    </section>

     
    <!-- Contact -->
    
    <div class="contact-container1" id="Contact">
        <h2 class="contact-h2">Contact Us</h2>
        <div class="contact-content1">
            <div class="contact-form1">
                <form id="contactForm">
                    <div class="form-group1">
                        <label class="contact-lable1" for="message">Any Queries:</label>
                        <textarea
                            class="contact-textarea1"
                            id="message"
                            name="message"
                            rows="4"
                            required
                            placeholder="Type your query here..."
                        ></textarea>
                    </div>
                    <button class="contact-btn" type="submit">Send Message</button>
                </form>
                <div class="contact-details">
                    <h3 class="contact-details-h3">Contact Details</h3>
                    <p class="contact-details-p">Email: <a href="mailto:sreerajmutha@gmail.com">sreerajmutha@gmail.com</a></p>
                    <p class="contact-details-p">Mobile: <a href="tel:9391410078">9391410078</a></p>
                </div>
            </div>
            <div class="map-container1">
                <iframe
                    title="Location Map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.5050974128913!2d80.61870621473945!3d16.507400588026978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a35f97ee3c1ef43%3A0xe9df55db30e693b4!2sChittinagar%2C%20Vijayawada%2C%20Andhra%20Pradesh%20520001!5e0!3m2!1sen!2sin!4v1698465671111!5m2!1sen!2sin"
                    width="100%"
                    height="300"
                    style="border: 0;"
                    allowfullscreen=""
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
                <div class="footer-container">
                    <div class="footer-section about">
                    <h2>About Us</h2>
                    <p>Sharing delicious recipes from around the world! Follow us for more tasty inspiration.</p>
                    </div>
                    
                    <div class="footer-section links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li onClick="scrollToSection('Home')">Home</li>
                        <li onClick="scrollToSection('Recipe')">Recipes</li>
                        <li onClick="scrollToSection('About')">About Us</li>
                        <li onClick="scrollToSection('Contact')">Contact</li>
                    </ul>
                    </div>
                    
                    <div class="footer-section contact">
                    <h2>Contact Us</h2>
                    <p>Email: sreerajmutha@gmail.com</p>
                    <p>Phone: +919391410078</p>
                    <div class="socials">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    &copy; 2024 DishHub | All rights reserved
                </div>
            </footer>

    <!-- javascrip -->
    <script>
        function navigate() {
            window.location.href = "login.html";
        }

        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        function toggleMenu() {
            document.querySelector(".ul").classList.toggle("show-menu");
        }

        function logout() {
            window.location.href = "logout.php";
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };

        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
            
                if (sectionId === 'Home') {
                    document.querySelector('.nav').style.backgroundColor = ''; 
                } else if (sectionId === 'Recipe') {
                    document.querySelector('.nav').style.backgroundColor = 'white'; 
                }

            }
        }

        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.nav');
            const scrollY = window.scrollY || window.pageYOffset;

            if (scrollY > 50) { 
                navbar.style.backgroundColor = 'white'; 
            } else {
                navbar.style.backgroundColor = 'rgba(198, 222, 230, 0.7)'; 
            }
        });

        function sendEmail(imageId) {
            fetch('send_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    imageId: imageId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === "Email sent successfully!") {
                    showToast(data.message); 
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function showToast(message) {
            const toast = document.getElementById("toast");
            toast.innerText = message;
            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");
            }, 3000);
        }

        const allRecipes = []; 
        const recipeListElement = document.getElementById('recipe-list');
        const searchBar = document.getElementById('search-bar');
        const noRecipesMessage = document.getElementById('no-recipes-message');
        const popup = document.getElementById('popup');
        const closePopupButton = document.getElementById('close-popup');
        const popupTitle = document.getElementById('popup-title');
        const popupIngredients = document.getElementById('popup-ingredients');
        const popupPreparationList = document.getElementById('popup-preparation-list');

        fetch('indian.json')
            .then(response => response.json())
            .then(data => {
                allRecipes.push(...data);
                return fetch('italian.json');
            })
            .then(response => response.json())
            .then(data => {
                allRecipes.push(...data);
                return fetch('korean.json');
            })
            .then(response => response.json())
            .then(data => {
                allRecipes.push(...data); 
                renderRecipes(allRecipes); 
            });

        function renderRecipes(recipes) {
            recipeListElement.innerHTML = ''; 
            if (recipes.length === 0) {
                noRecipesMessage.style.display = 'block'; 
            } else {
                noRecipesMessage.style.display = 'none'; 
                recipes.forEach(recipe => {
                    const li = document.createElement('li');
                    li.className = 'recipe-item';

                    const div = document.createElement('div');
                    div.className = 'image-container';
                    div.onclick = () => handleImageClick(recipe);

                    const img = document.createElement('img');
                    img.src = recipe.Pic;
                    img.alt = recipe.title;
                    img.className = 'recipe-image';

                    const p = document.createElement('p');
                    p.className = 'image-title';
                    p.textContent = recipe.title;

                    div.appendChild(img);
                    div.appendChild(p);
                    li.appendChild(div);
                    recipeListElement.appendChild(li);
                });
            }
        }

        function handleImageClick(recipe) {
            popupTitle.textContent = recipe.title;
            popupIngredients.textContent = recipe.ingredient.join(", ");
            popupPreparationList.innerHTML = ''; 
            recipe.preparation.forEach(step => {
                const li = document.createElement('li');
                li.textContent = step;
                popupPreparationList.appendChild(li);
            });
            popup.classList.add('show'); 
        }

        closePopupButton.onclick = function() {
            popup.classList.remove('show');
        };

        searchBar.addEventListener('input', (event) => {
            const searchTerm = event.target.value.toLowerCase();
            const filteredRecipes = allRecipes.filter(recipe =>
                recipe.title.toLowerCase().includes(searchTerm)
            );
            renderRecipes(filteredRecipes);
        });

        emailjs.init("ZHeI6UE4DIAdLc_f0"); 

        document.getElementById("contactForm").addEventListener("submit", function (event) {
            event.preventDefault(); 

            const message = document.getElementById("message").value;

            const userEmail = "marriyaswanth42@gmail.com";

            const templateParams = {
                user_email: userEmail,
                message: message,
            };

            emailjs.send("service_l0a3aej", "template_fzvnyuk", templateParams)
                .then(function (response) {
                    alert("Message sent successfully!");
                }, function (error) {
                    alert("Failed to send message. Please try again.");
                });
        });

    </script>
</body>
</html>
