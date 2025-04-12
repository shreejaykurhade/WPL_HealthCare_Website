<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Library - HealthCare Web</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .article-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .article-card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #fff;
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .article-content {
        padding: 20px;
    }

    .article-title {
        font-size: 1.3rem;
        margin-bottom: 10px;
        color: #1E2A38;
    }

    .article-snippet {
        color: #555;
        margin-bottom: 15px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: #777;
    }

    .article-category {
        background-color: #E3F2FD;
        color: #1E88E5;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .btn-read {
        display: inline-block;
        background-color: #1E88E5;
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-read:hover {
        background-color: #1565C0;
    }

    .filter-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .category-filter {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .category-btn {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-btn:hover, .category-btn.active {
        background-color: #1E88E5;
        color: white;
        border-color: #1E88E5;
    }

    .featured-section {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .featured-title {
        font-size: 1.8rem;
        color: #1E2A38;
        margin-bottom: 20px;
        text-align: center;
    }

    .section-title {
        font-size: 1.5rem;
        color: #1E2A38;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #1E88E5;
    }
</style>

</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">
                <img src="logo.png" alt="Healthcare Logo" style="width:175px ; height : 70px";>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="articles.php">Health Library</a></li>
                <li><a href="doctors.php">Appointments</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">My Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2 style="text-align: center; margin-top: 30px;">Health Library</h2>
            <p style="text-align: center; margin-bottom: 30px;">Discover the latest articles and research in healthcare</p>
            
            <!-- Featured Articles Section -->
            <div class="featured-section">
                <h3 class="featured-title">Featured Health Topics</h3>
                <div class="article-container">
                    <!-- Featured Article 1 -->
                    <div class="article-card">
                        <img src="https://iskincarereviews.com/wp-content/uploads/2018/04/sleep.jpg" alt="Sleep Health" class="article-image">
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">Apr 10, 2025</span>
                                <span class="article-category">Sleep Health</span>
                            </div>
                            <h3 class="article-title">How Sleep Affects Your Mental Health</h3>
                            <p class="article-snippet">Research shows that sleep and mental health are closely connected. Poor sleep can contribute to the development of mental health problems, while mental health problems can make it harder to sleep well.</p>
                            <a href="https://pmc.ncbi.nlm.nih.gov/articles/PMC8651630/" target="_blank" class="btn-read">Read Article</a>
                        </div>
                    </div>
                    
                    <!-- Featured Article 2 -->
                    <div class="article-card">
                        <img src="https://th.bing.com/th/id/OIP.GET0bIKfwGnqKpsPJoBw6QHaE8?rs=1&pid=ImgDetMain" alt="Exercise Benefits" class="article-image">
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">Apr 8, 2025</span>
                                <span class="article-category">Fitness</span>
                            </div>
                            <h3 class="article-title">Moderate Exercise: Benefits Beyond Physical Health</h3>
                            <p class="article-snippet">Regular moderate exercise can improve your mood, boost brain function, and help prevent chronic diseases. Even 30 minutes of walking five days a week can make a significant difference.</p>
                            <a href="https://www.heart.org/en/healthy-living/fitness/fitness-basics/aha-recs-for-physical-activity-in-adults" target="_blank" class="btn-read">Read Article</a>
                        </div>
                    </div>
                    
                    <!-- Featured Article 3 -->
                    <div class="article-card">
                        <img src="https://th.bing.com/th/id/OIP._EHQPsckttdkYCxLxuATFgHaE8?rs=1&pid=ImgDetMain" alt="Nutrition Basics" class="article-image">
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">Apr 5, 2025</span>
                                <span class="article-category">Nutrition</span>
                            </div>
                            <h3 class="article-title">The Science of Balanced Nutrition</h3>
                            <p class="article-snippet">Understanding the principles of balanced nutrition can help you make better food choices. Learn about macronutrients, micronutrients, and how they work together for optimal health.</p>
                            <a href="https://www.hsph.harvard.edu/nutritionsource/healthy-eating-plate/" target="_blank" class="btn-read">Read Article</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Filter Section -->
            <div class="filter-container">
                <div class="category-filter">
                    <button class="category-btn active" data-category="all">All Categories</button>
                    <button class="category-btn" data-category="nutrition">Nutrition</button>
                    <button class="category-btn" data-category="mental-health">Mental Health</button>
                    <button class="category-btn" data-category="fitness">Fitness</button>
                    <button class="category-btn" data-category="prevention">Prevention</button>
                    <button class="category-btn" data-category="women">Women's Health</button>
                </div>
            </div>
            
            <!-- Main Articles Section -->
            <h3 class="section-title" style="text-align : center";>Latest Health Articles</h3>
            <div class="article-container" id="articles-grid">
                <!-- Article 1 -->
                <div class="article-card" data-category="nutrition">
                    <img src="https://st2.depositphotos.com/1000589/9203/i/950/depositphotos_92034640-stock-photo-balanced-diet-healthy-food-concept.jpg" alt="Healthy Diet" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 12, 2025</span>
                            <span class="article-category">Nutrition</span>
                        </div>
                        <h3 class="article-title">Rujuta Diwekar's Diet Plan: Healthy Eating, Happy Living</h3>
                        <p class="article-snippet">Learn how to create balanced meals that provide all the nutrients your body needs. This comprehensive guide explains portion sizes and food combinations for optimal health.</p>
                        <a href="https://shunketo.com/article/what-is-rujuta-diwekar-diet-plan" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 2 -->
                <div class="article-card" data-category="mental-health">
                    <img src="https://th.bing.com/th/id/OIP.ZlGg7g6AcWF-sJdzSoncjgHaE8?rs=1&pid=ImgDetMain" alt="Stress Management" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 11, 2025</span>
                            <span class="article-category">Mental Health</span>
                        </div>
                        <h3 class="article-title">Effective Strategies for Managing Stress</h3>
                        <p class="article-snippet">Chronic stress can have serious effects on both physical and mental health. Discover evidence-based techniques to reduce stress and improve your overall wellbeing.</p>
                        <a href="https://www.nimh.nih.gov/health/publications/stress" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 3 -->
                <div class="article-card" data-category="fitness">
                    <img src="https://bodyhacks.com/wp-content/uploads/2016/05/home-exercise.jpg" alt="Home Exercise" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 10, 2025</span>
                            <span class="article-category">Fitness</span>
                        </div>
                        <h3 class="article-title">Effective Home Workouts Without Equipment</h3>
                        <p class="article-snippet">You don't need a gym membership or expensive equipment to stay fit. This guide provides a complete workout routine using just your body weight that can be done anywhere.</p>
                        <a href="https://www.nia.nih.gov/health/exercise-physical-activity" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 4 -->
                <div class="article-card" data-category="prevention">
                    <img src="https://th.bing.com/th/id/R.04998b3717aab5f28630cab673244f93?rik=%2ff%2fPoYpOtCjjSQ&riu=http%3a%2f%2fhealthcareassociates.com%2fwp-content%2fuploads%2f2021%2f05%2fshutterstock_612916889-scaled.jpg&ehk=rt3XxcaUsah9NYIHq4niqI7LCwAyWwikjHDFIzPp1JQ%3d&risl=&pid=ImgRaw&r=0" alt="Skin Cancer Prevention" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 9, 2025</span>
                            <span class="article-category">Prevention</span>
                        </div>
                        <h3 class="article-title">Sun Safety: <br>Protecting Your Skin</h3>
                        <p class="article-snippet">Skin cancer is one of the most common types of cancer, but also one of the most preventable. Learn how to protect your skin from harmful UV rays and reduce your risk.</p>
                        <a href="https://www.cdc.gov/skin-cancer/sun-safety/index.html" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 5 -->
                <div class="article-card" data-category="women">
                    <img src="https://cdn.mothersalwaysright.com/wp-content/uploads/2024/12/Understanding-Hormonal-Changes-in-Women.webp" alt="Women's Health" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 8, 2025</span>
                            <span class="article-category">Women's Health</span>
                        </div>
                        <h3 class="article-title">Understanding Hormonal Health for Women</h3>
                        <p class="article-snippet">Hormones play a crucial role in women's health throughout different life stages. This comprehensive guide explains hormonal changes and how to maintain balance.</p>
                        <a href="https://www.mothersalwaysright.com/understanding-hormonal-changes-in-women/" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 6 -->
                <div class="article-card" data-category="nutrition">
                    <img src="http://content.health.harvard.edu/wp-content/uploads/2023/04/c92020e0-e209-403d-a334-2b544b03a9d4.jpg" alt="Plant-Based Diets" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 7, 2025</span>
                            <span class="article-category">Nutrition</span>
                        </div>
                        <h3 class="article-title">The Benefits of Plant-Based Eating</h3>
                        <p class="article-snippet">Research shows that diets rich in plant foods may help prevent chronic diseases. Learn about the health benefits of plant-based eating and how to incorporate more plants into your diet.</p>
                        <a href="https://www.health.harvard.edu/staying-healthy/the-right-plant-based-diet-for-you" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 7 -->
                <div class="article-card" data-category="mental-health">
                    <img src="https://files.nccih.nih.gov/meditation-thinkstockphotos-505023182-square.jpg" alt="Meditation Benefits" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 6, 2025</span>
                            <span class="article-category">Mental Health</span>
                        </div>
                        <h3 class="article-title">The Science Behind Meditation Benefits</h3>
                        <p class="article-snippet">Scientific research is increasingly validating the benefits of meditation for mental and physical health. Discover what science says about this ancient practice.</p>
                        <a href="https://www.nccih.nih.gov/health/meditation-in-depth" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 8 -->
                <div class="article-card" data-category="prevention">
                    <img src="https://cdn1.byjus.com/wp-content/uploads/2016/07/Diabetes1.jpg" alt="Diabetes Prevention" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 5, 2025</span>
                            <span class="article-category">Prevention</span>
                        </div>
                        <h3 class="article-title">Preventing Type 2 Diabetes: Latest Guidelines</h3>
                        <p class="article-snippet">Type 2 diabetes is largely preventable with lifestyle modifications. This article covers the latest research and recommendations for reducing your risk factors.</p>
                        <a href="https://pmc.ncbi.nlm.nih.gov/articles/PMC6893436/" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
                
                <!-- Article 9 -->
                <div class="article-card" data-category="fitness">
                    <img src="https://th.bing.com/th/id/OIP.CsO1E3oJLxHjzTLC41k6igHaE7?rs=1&pid=ImgDetMain" alt="Senior Fitness" class="article-image">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-date">Apr 4, 2025</span>
                            <span class="article-category">Fitness</span>
                        </div>
                        <h3 class="article-title">Staying Active as You Age: A Guide for Seniors</h3>
                        <p class="article-snippet">Regular physical activity becomes even more important as we age. Learn about exercises specifically designed for older adults to maintain strength, balance, and mobility.</p>
                        <a href="https://www.nia.nih.gov/health/exercise-physical-activity" target="_blank" class="btn-read">Read Article</a>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="articles.php">Medical Articles</a></li>
                    <li><a href="book_appointment.php">Book Appointment</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: support@healmateweb.com</li>
                    <li>Phone: +123 456 7890</li>
                    <li>Location: Multiple Hospital Locations</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to stay updated with healthcare news.</p>
                <form action="subscribe.php" method="POST">
                    <input type="email" name="email" placeholder="Your mail" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <p>&copy; 2025 HealMate Web. All rights reserved.</p>
    </footer>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category filtering functionality
        const categoryButtons = document.querySelectorAll('.category-btn');
        const articleCards = document.querySelectorAll('.article-card');
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const selectedCategory = this.getAttribute('data-category');
                
                // Show/hide articles based on category
                articleCards.forEach(card => {
                    if (selectedCategory === 'all' || card.getAttribute('data-category') === selectedCategory) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
    </script>
</body>
</html>