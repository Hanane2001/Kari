<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Vacation Rentals & Short-Term Stays</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-bg { background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'); }
        .btn-primary { background-color: #FF385C; }
        .btn-primary:hover { background-color: #E31C5F; }
        .text-primary { color: #FF385C; }
        .border-primary { border-color: #FF385C; }
        .bg-primary-light { background-color: #FFF5F5; }
    </style>
</head>
<body class="text-gray-800">
    <nav class="bg-white shadow-md py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <i class="fas fa-home text-3xl text-primary"></i>
                <span class="text-2xl font-bold text-gray-900">Stay<span class="text-primary">Ease</span></span>
            </a>
            
            <div class="hidden md:flex space-x-8">
                <a href="#" class="font-medium hover:text-primary">Home</a>
                <a href="#" class="font-medium hover:text-primary">Rentals</a>
                <a href="#" class="font-medium hover:text-primary">Become a Host</a>
                <a href="#" class="font-medium hover:text-primary">Help</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-4">
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                        <i class="fas fa-user mr-1"></i> 
                        <span id="userRole">Guest</span>
                    </span>
                    <a href="#" class="px-4 py-2 bg-primary text-white font-medium rounded-full hover:bg-red-600 transition">Login / Sign up</a>
                </div>
                <button id="mobileMenuBtn" class="md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="md:hidden hidden mt-4 pb-4">
            <div class="flex flex-col space-y-4">
                <a href="#" class="font-medium hover:text-primary">Home</a>
                <a href="#" class="font-medium hover:text-primary">Rentals</a>
                <a href="#" class="font-medium hover:text-primary">Become a Host</a>
                <a href="#" class="font-medium hover:text-primary">Help</a>
                <div class="pt-4 border-t">
                    <a href="#" class="block text-center px-4 py-2 bg-primary text-white font-medium rounded-full hover:bg-red-600 transition">Login / Sign up</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-bg bg-cover bg-center text-white py-24 px-6">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Find your perfect getaway</h1>
            <p class="text-xl mb-10 max-w-2xl mx-auto">Book unique homes, apartments, and experiences for your next vacation.</p>
            
            <div class="bg-white rounded-2xl shadow-2xl p-4 md:p-6 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Location</label>
                        <input type="text" placeholder="Where are you going?" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-in</label>
                        <input type="date" class="w-full p-3 text-gray-400 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-out</label>
                        <input type="date" class="w-full p-3 border text-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Guests</label>
                        <div class="flex items-center border rounded-lg p-3">
                            <i class="fas fa-user text-gray-500 mr-2"></i>
                            <select class="w-full focus:outline-none text-gray-400">
                                <option>1 guest</option>
                                <option>2 guests</option>
                                <option>3 guests</option>
                                <option>4+ guests</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="mt-6 w-full md:w-auto px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-red-600 transition flex items-center justify-center mx-auto">
                    <i class="fas fa-search mr-2"></i> Search Rentals
                </button>
            </div>
        </div>
    </section>

    <section class="py-16 px-6 bg-primary-light">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose StayEase?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Secure Booking</h3>
                    <p class="text-gray-600">Your reservations are protected with our secure payment system and cancellation policies.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Verified Properties</h3>
                    <p class="text-gray-600">Every listing is carefully reviewed to ensure quality and accuracy for our guests.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-16 h-16 bg-primary-light text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">24/7 Support</h3>
                    <p class="text-gray-600">Our customer service team is available around the clock to assist with any issues.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-bold">Featured Rentals</h2>
                <a href="#" class="text-primary font-medium hover:underline">View all <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Modern Apartment" class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-2xl text-gray-300 hover:text-primary">
                            <i class="far fa-heart"></i>
                        </button>
                        <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium">$125/night</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-1">Modern Apartment in Downtown</h3>
                        <p class="text-gray-600 mb-3"><i class="fas fa-map-marker-alt text-primary mr-2"></i>Paris, France</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium">4.8</span>
                                <span class="text-gray-500 ml-1">(124 reviews)</span>
                            </div>
                            <span class="text-sm bg-gray-100 px-2 py-1 rounded"><i class="fas fa-user-friends mr-1"></i> 4 guests</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Beach Villa" class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-2xl text-gray-300 hover:text-primary">
                            <i class="far fa-heart"></i>
                        </button>
                        <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium">$245/night</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-1">Beachfront Villa</h3>
                        <p class="text-gray-600 mb-3"><i class="fas fa-map-marker-alt text-primary mr-2"></i>Bali, Indonesia</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium">4.9</span>
                                <span class="text-gray-500 ml-1">(89 reviews)</span>
                            </div>
                            <span class="text-sm bg-gray-100 px-2 py-1 rounded"><i class="fas fa-user-friends mr-1"></i> 6 guests</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Mountain Cabin" class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-2xl text-gray-300 hover:text-primary">
                            <i class="far fa-heart"></i>
                        </button>
                        <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium">$95/night</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-1">Cozy Mountain Cabin</h3>
                        <p class="text-gray-600 mb-3"><i class="fas fa-map-marker-alt text-primary mr-2"></i>Swiss Alps, Switzerland</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium">4.7</span>
                                <span class="text-gray-500 ml-1">(67 reviews)</span>
                            </div>
                            <span class="text-sm bg-gray-100 px-2 py-1 rounded"><i class="fas fa-user-friends mr-1"></i> 2 guests</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="City Loft" class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-2xl text-gray-300 hover:text-primary">
                            <i class="far fa-heart"></i>
                        </button>
                        <span class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-medium">$180/night</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-1">Urban Loft in City Center</h3>
                        <p class="text-gray-600 mb-3"><i class="fas fa-map-marker-alt text-primary mr-2"></i>New York, USA</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium">4.6</span>
                                <span class="text-gray-500 ml-1">(203 reviews)</span>
                            </div>
                            <span class="text-sm bg-gray-100 px-2 py-1 rounded"><i class="fas fa-user-friends mr-1"></i> 3 guests</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12 px-6">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="#" class="flex items-center space-x-2 mb-6">
                        <i class="fas fa-home text-3xl text-primary"></i>
                        <span class="text-2xl font-bold">Stay<span class="text-primary">Ease</span></span>
                    </a>
                    <p class="text-gray-400">Your trusted platform for unique vacation rentals around the world.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Explore</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Popular Destinations</a></li>
                        <li><a href="#" class="hover:text-white transition">Luxury Rentals</a></li>
                        <li><a href="#" class="hover:text-white transition">Budget Stays</a></li>
                        <li><a href="#" class="hover:text-white transition">Experiences</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Hosting</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Become a Host</a></li>
                        <li><a href="#" class="hover:text-white transition">Host Resources</a></li>
                        <li><a href="#" class="hover:text-white transition">Safety Standards</a></li>
                        <li><a href="#" class="hover:text-white transition">Host Protection</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-6">Contact</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-primary"></i>
                            <span>support@stayease.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-primary"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                            <span>123 Rental Street, San Francisco, CA</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-8 text-center text-gray-400">
                <p>&copy; 2026 StayEase. All rights reserved. | <a href="#" class="hover:text-white">Privacy Policy</a> | <a href="#" class="hover:text-white">Terms of Service</a></p>
                <div class="flex justify-center space-x-6 mt-6 text-2xl">
                    <a href="#" class="hover:text-primary"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
        const roles = ['Guest', 'Traveler', 'Host', 'Administrator'];
        let currentRoleIndex = 0;
        const roleElement = document.getElementById('userRole');
        
        const favButtons = document.querySelectorAll('.fa-heart');
        favButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.classList.contains('far')) {
                    this.classList.remove('far');
                    this.classList.add('fas', 'text-primary');
                } else {
                    this.classList.remove('fas', 'text-primary');
                    this.classList.add('far');
                }
            });
        });

        document.querySelector('button[class*="bg-primary"]').addEventListener('click', function() {
            const location = document.querySelector('input[type="text"]').value;
            if (location) {
                alert(`Searching for rentals in ${location}...`);
            } else {
                alert('Please enter a location to search for rentals.');
            }
        });
    </script>
</body>
</html>