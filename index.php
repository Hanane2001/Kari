<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEasy | Vacation Rentals & Short-Term Stays</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/color/96/000000/beach-house.png">
</head>
<body class="font-poppins bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-home text-2xl text-blue-600"></i>
                <a href="index.php" class="text-2xl font-bold text-gray-800">StayEasy</a>
            </div>
            
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-blue-600 font-semibold hover:text-blue-800">Home</a>
                <a href="#rentals" class="text-gray-700 hover:text-blue-600">Rentals</a>
                <a href="#how-it-works" class="text-gray-700 hover:text-blue-600">How it works</a>
                <a href="#testimonials" class="text-gray-700 hover:text-blue-600">Testimonials</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="auth/login.php" class="text-gray-700 hover:text-blue-600">Log in</a>
                <a href="auth/signup.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Sign up</a>
                <button id="menu-toggle" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white px-4 py-3 shadow-md">
            <a href="index.html" class="block py-2 text-blue-600 font-semibold">Home</a>
            <a href="#rentals" class="block py-2 text-gray-700">Rentals</a>
            <a href="#how-it-works" class="block py-2 text-gray-700">How it works</a>
            <a href="#testimonials" class="block py-2 text-gray-700">Testimonials</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-500 to-teal-400 text-white py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Find Your Perfect Short-Term Stay</h1>
            <p class="text-xl mb-10 max-w-3xl mx-auto">Discover unique homes, apartments, and villas for your next vacation. Simple booking, trusted hosts, unforgettable experiences.</p>
            
            <!-- Search Bar -->
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="flex-1 mb-4 md:mb-0">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Destination</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" placeholder="Where are you going?" class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex-1 mb-4 md:mb-0">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Dates</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" placeholder="Select dates" class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex-1 mb-4 md:mb-0">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Guests</label>
                        <div class="relative">
                            <i class="fas fa-user-friends absolute left-3 top-3 text-gray-400"></i>
                            <select class="pl-10 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>1 guest</option>
                                <option>2 guests</option>
                                <option>3 guests</option>
                                <option>4+ guests</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button class="w-full md:w-auto bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </div>
            </div>
            
            <p class="mt-8 text-blue-100">Trusted by over 500,000 travelers worldwide</p>
        </div>
    </section>

    <!-- Featured Rentals Section -->
    <section id="rentals" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Featured Rentals</h2>
                    <p class="text-gray-600">Handpicked accommodations for an exceptional stay</p>
                </div>
                <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View all <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Rental Card 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Modern Apartment" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 shadow-md">
                            <i class="fas fa-star text-yellow-500 mr-1"></i> 4.8
                        </div>
                        <button class="absolute top-4 left-4 text-white bg-gray-900 bg-opacity-50 hover:bg-opacity-70 rounded-full w-10 h-10 flex items-center justify-center">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Modern Apartment in Paris Center</h3>
                        <p class="text-gray-600 mb-4">Entire apartment · 2 guests · 1 bedroom · 1 bath</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-gray-800">€89</span>
                                <span class="text-gray-600"> / night</span>
                            </div>
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Rental Card 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1065&q=80" alt="Beach Villa" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 shadow-md">
                            <i class="fas fa-star text-yellow-500 mr-1"></i> 4.9
                        </div>
                        <button class="absolute top-4 left-4 text-white bg-gray-900 bg-opacity-50 hover:bg-opacity-70 rounded-full w-10 h-10 flex items-center justify-center">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Beachfront Villa in Santorini</h3>
                        <p class="text-gray-600 mb-4">Entire villa · 6 guests · 3 bedrooms · 2 baths</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-gray-800">€245</span>
                                <span class="text-gray-600"> / night</span>
                            </div>
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Rental Card 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Mountain Cabin" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 shadow-md">
                            <i class="fas fa-star text-yellow-500 mr-1"></i> 4.7
                        </div>
                        <button class="absolute top-4 left-4 text-white bg-gray-900 bg-opacity-50 hover:bg-opacity-70 rounded-full w-10 h-10 flex items-center justify-center">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Cozy Cabin in Swiss Alps</h3>
                        <p class="text-gray-600 mb-4">Entire cabin · 4 guests · 2 bedrooms · 1 bath</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-gray-800">€120</span>
                                <span class="text-gray-600"> / night</span>
                            </div>
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">How StayEasy Works</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Search</h3>
                    <p class="text-gray-600">Find the perfect rental by location, dates, price, and amenities. Use filters to narrow down your options.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-check text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">2. Book</h3>
                    <p class="text-gray-600">Check availability and book instantly. Our secure payment system protects your transaction.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-home text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">3. Enjoy</h3>
                    <p class="text-gray-600">Check in and enjoy your stay! Message your host directly if you need anything during your trip.</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="signup.html" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 transition duration-300 font-medium">Get Started Now</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">What Our Guests Say</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah L." class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Sarah L.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The Paris apartment was even better than the photos! The host was incredibly responsive and gave us great local recommendations. We'll definitely use StayEasy again!"</p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Michael T." class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Michael T.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"As a host, StayEasy has made managing my rental property so much easier. The booking system is seamless, and I love the automated notifications for new reservations."</p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Jessica R." class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Jessica R.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The cabin in the Alps was a dream! The booking process was straightforward, and the customer support team helped quickly when I had a question about check-in."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-teal-500 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">50,000+</div>
                    <div class="text-blue-100">Properties Worldwide</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">120+</div>
                    <div class="text-blue-100">Countries</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">500,000+</div>
                    <div class="text-blue-100">Happy Travelers</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">4.8/5</div>
                    <div class="text-blue-100">Average Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <i class="fas fa-home text-2xl text-blue-400"></i>
                        <span class="text-2xl font-bold">StayEasy</span>
                    </div>
                    <p class="text-gray-400 mb-6">Your trusted platform for short-term rentals and unforgettable travel experiences.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in text-xl"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-6">For Travelers</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Search Rentals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">How it Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Trust & Safety</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Travel Insurance</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Gift Cards</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-6">For Hosts</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Become a Host</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Host Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Host Protection</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Community Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Hosting FAQs</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-6">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Press</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms & Privacy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-10 pt-8 text-center text-gray-400">
                <p>&copy; 2026 StayEasy. All rights reserved. This is a demonstration interface for a PHP OOP project.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>