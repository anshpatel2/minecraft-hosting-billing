<x-global-layout title="Welcome">
    <!-- Hero Section -->
    <div class="hero-gradient text-white py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-cubes text-4xl"></i>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold mb-6">
                        Professional Minecraft Hosting
                    </h1>
                    <p class="text-xl md:text-2xl text-white text-opacity-90 mb-8 max-w-3xl mx-auto">
                        Launch your Minecraft server in seconds with 99.9% uptime guarantee, 
                        DDoS protection, and 24/7 support
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="bg-white text-purple-600 font-bold px-8 py-4 rounded-lg text-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>Get Started Free
                        </a>
                        <a href="{{ route('login') }}" 
                           class="bg-white bg-opacity-20 text-white font-semibold px-8 py-4 rounded-lg text-lg hover:bg-opacity-30 transition-all duration-300 border border-white border-opacity-30">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white text-purple-600 font-bold px-8 py-4 rounded-lg text-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Our Hosting?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Built for performance, designed for simplicity, trusted by thousands of Minecraft communities worldwide
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bolt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Instant Setup</h3>
                    <p class="text-gray-600">
                        Deploy your Minecraft server in under 60 seconds with our one-click installer
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">DDoS Protection</h3>
                    <p class="text-gray-600">
                        Enterprise-grade DDoS protection keeps your server online during attacks
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-purple-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Support</h3>
                    <p class="text-gray-600">
                        Our expert team is always ready to help you with any technical issues
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">99.9% Uptime</h3>
                    <p class="text-gray-600">
                        Guaranteed uptime with redundant infrastructure and automatic failover
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-red-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-cogs text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Easy Management</h3>
                    <p class="text-gray-600">
                        Intuitive control panel with one-click mod installation and server management
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center">
                    <div class="w-16 h-16 bg-indigo-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-database text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Automatic Backups</h3>
                    <p class="text-gray-600">
                        Daily automated backups ensure your world data is always safe and recoverable
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600">
                    Choose the perfect plan for your Minecraft community
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter Plan -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center border">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Starter</h3>
                    <div class="text-4xl font-bold text-purple-600 mb-6">
                        $5<span class="text-lg text-gray-500">/mo</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>2GB RAM</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>10 Player Slots</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>10GB SSD Storage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Daily Backups</span>
                        </li>
                    </ul>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Choose Plan
                    </button>
                </div>

                <!-- Pro Plan -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center border-2 border-purple-500 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-purple-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pro</h3>
                    <div class="text-4xl font-bold text-purple-600 mb-6">
                        $15<span class="text-lg text-gray-500">/mo</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>6GB RAM</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>50 Player Slots</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>50GB SSD Storage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Hourly Backups</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Plugin Management</span>
                        </li>
                    </ul>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Choose Plan
                    </button>
                </div>

                <!-- Enterprise Plan -->
                <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-8 rounded-xl text-center border">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                    <div class="text-4xl font-bold text-purple-600 mb-6">
                        $35<span class="text-lg text-gray-500">/mo</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>16GB RAM</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Unlimited Players</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>200GB SSD Storage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Real-time Backups</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Priority Support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Custom Domains</span>
                        </li>
                    </ul>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Choose Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="hero-gradient text-white py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Your Minecraft Journey?</h2>
            <p class="text-xl text-white text-opacity-90 mb-8">
                Join thousands of satisfied customers and launch your server today
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" 
                       class="bg-white text-purple-600 font-bold px-8 py-4 rounded-lg text-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-rocket mr-2"></i>Start Free Trial
                    </a>
                    <a href="#" 
                       class="bg-white bg-opacity-20 text-white font-semibold px-8 py-4 rounded-lg text-lg hover:bg-opacity-30 transition-all duration-300 border border-white border-opacity-30">
                        <i class="fas fa-phone mr-2"></i>Contact Sales
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="bg-white text-purple-600 font-bold px-8 py-4 rounded-lg text-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</x-global-layout>
