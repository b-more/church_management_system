<!-- resources/views/about.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about His Kingdom Church - our history, mission, vision and values.">
    <title>About Us - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#011EB7',
                        secondary: '#E0B041',
                        accent: '#754DA4'
                    },
                    spacing: {
                        '128': '32rem',
                    },
                    boxShadow: {
                        'custom': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Document ready - no animation code
        });
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[60vh]">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-fixed" style="background-image: url('/images/about-banner.jpg');">
            <span class="w-full h-full absolute opacity-80 bg-primary bg-gradient-to-r from-primary/90 to-primary/70"></span>
        </div>
        <div class="container relative mx-auto px-4">
            <div class="items-center flex flex-wrap">
                <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
                    <div class="text-white">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow">About His Kingdom Church</h1>
                        <p class="mt-4 text-lg md:text-xl text-white/90">Raising Kingdom Ambassadors Since 2008</p>
                        <div class="mt-8">
                            <a href="#history" class="inline-block px-6 py-3 bg-secondary text-primary font-semibold rounded-full transition-all hover:bg-white hover:shadow-lg">Our Journey</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 w-full overflow-hidden leading-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="h-16 w-full text-white">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C159,0,213,33.88,258.89,48.7,294.83,59.18,304.67,66.15,321.39,56.44Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative py-12 md:py-20 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <!-- History Section -->
            <div class="mb-16 md:mb-24" id="history">
                <div class="flex flex-col md:flex-row items-center mb-10">
                    <div class="w-20 h-1 bg-primary hidden md:block"></div>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary md:ml-4">Our History</h2>
                </div>
                <div class="grid md:grid-cols-5 gap-8">
                    <div class="md:col-span-3">
                        <div class="prose max-w-none text-gray-700 text-lg leading-relaxed">
                            <p class="mb-6">
                                The ministry started as <span class="font-semibold">HIS KINGDOM CHURCH</span> and was founded as a result of many prophetic utterances
                                and years of travailing prayer. In 1992 a teenager stumbled into a Pentecostal Church where he met
                                Jesus Christ and accepted him as Lord and savior. This teenager was to become the founder of this
                                ministry 16 years later.
                            </p>
                            <p class="mb-6">
                                On 2nd February 2008, Apostle Chris, Pastor Sepiso, and 7 other faithful believers started the
                                HIS KINGDOM CHURCH cell group at Plot 428 in Avondale, Lusaka, Zambia. A month later, the first
                                Church service was held at Reens Private School in Chelstone, Lusaka, with an attendance of nine (9) people.
                            </p>
                            <p>
                                From these humble beginnings, His Kingdom Church has grown to become a beacon of hope and transformation in the community,
                                touching countless lives with the message of God's love.
                            </p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="relative h-64 md:h-full rounded-lg overflow-hidden shadow-custom">
                            <div class="absolute inset-0 bg-primary/20"></div>
                            <img src="/images/church-history.jpg" alt="His Kingdom Church beginnings" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary/80 to-transparent p-6">
                                <p class="text-white font-medium">Founded: February 2, 2008</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mb-16 md:mb-24 overflow-hidden">
                <div class="relative max-w-3xl mx-auto">
                    <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary/20"></div>

                    <!-- Timeline item 1 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white font-bold shadow-lg">
                                    2008
                                </div>
                                <div class="w-1 h-24 bg-primary/30"></div>
                            </div>
                            <div class="ml-6 bg-white p-6 rounded-lg shadow-custom">
                                <h3 class="text-xl font-semibold text-primary mb-2">Church Founded</h3>
                                <p class="text-gray-600">First service held at Reens Private School with just 9 members.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline item 2 -->
                    <div class="relative z-10 mb-12 ml-12 md:ml-24">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white font-bold shadow-lg">
                                    2012
                                </div>
                                <div class="w-1 h-24 bg-primary/30"></div>
                            </div>
                            <div class="ml-6 bg-white p-6 rounded-lg shadow-custom">
                                <h3 class="text-xl font-semibold text-primary mb-2">Vision 150 Unveiled</h3>
                                <p class="text-gray-600">God gave us the vision to plant a Kingdom Centre in every political constituency of Zambia.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline item 3 -->
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white font-bold shadow-lg">
                                    Today
                                </div>
                            </div>
                            <div class="ml-6 bg-white p-6 rounded-lg shadow-custom">
                                <h3 class="text-xl font-semibold text-primary mb-2">Growing Global Ministry</h3>
                                <p class="text-gray-600">Expanding our reach across continents to fulfill our mission and vision.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Section -->
            <div class="grid md:grid-cols-2 gap-8 lg:gap-16 mb-16 md:mb-24">
                <div class="bg-gray-50 p-8 rounded-lg shadow-custom">
                    <div class="mb-6">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mb-4">
                            <i class="fas fa-church text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-primary mb-4">Who We Are</h2>
                    </div>
                    <p class="text-gray-700 text-lg mb-6">
                        We are a multi-faceted, church planting, disciple making and teaching based ministry committed to
                        expanding God's kingdom throughout the world.
                    </p>
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-primary mb-4 flex items-center">
                            <i class="fas fa-bullseye text-secondary mr-3"></i>
                            Our Mission
                        </h3>
                        <p class="text-gray-700 ml-8 border-l-4 border-secondary pl-4 py-2">
                            To reach the lost for Christ, train them in the Word of God and send them as leaders,
                            representatives of Christ and ambassadors of Gods' kingdom to their world.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-lg shadow-custom">
                    <div class="mb-6">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mb-4">
                            <i class="fas fa-eye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-primary mb-4">Our Vision</h3>
                    </div>
                    <p class="text-gray-700 text-lg mb-6">
                        To be a global ministry which reaches out to the lost, plants churches, makes disciples and raises
                        leaders of global influence in their sphere of calling.
                    </p>
                    <p class="text-gray-700 mb-6">
                        As a ministry, it is our vision to be present on every continent and in every country of the world
                        in a significant and relevant way.
                    </p>
                    <div class="flex justify-end">
                        <div class="w-20 h-1 bg-secondary"></div>
                    </div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="mb-16 md:mb-24">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Our Core Values</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">The fundamental beliefs and principles that guide our decisions and actions</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-pray text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Prayer</h3>
                        <p class="text-gray-600">Prayer is the foundation for all that God does therefore we seek to build
                        the ministry on effective prayer both individual and corporate.</p>
                    </div>

                    <!-- Value 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-book-bible text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Bible-Based Teaching</h3>
                        <p class="text-gray-600">We are committed to teaching and living according to the Word of God, which is our ultimate authority and guide.</p>
                    </div>

                    <!-- Value 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-hands-helping text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Discipleship</h3>
                        <p class="text-gray-600">We believe in nurturing spiritual growth through intentional discipleship that transforms believers into mature followers of Christ.</p>
                    </div>

                    <!-- Value 4 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-globe-africa text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Global Outreach</h3>
                        <p class="text-gray-600">We are committed to reaching every nation with the gospel, establishing God's kingdom in every sphere of society.</p>
                    </div>

                    <!-- Value 5 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Community</h3>
                        <p class="text-gray-600">We foster authentic relationships and create a loving community where everyone feels welcomed, valued and belongs.</p>
                    </div>

                    <!-- Value 6 -->
                    <div class="bg-white p-6 rounded-lg shadow-custom hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                            <i class="fas fa-hand-holding-heart text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-primary">Service</h3>
                        <p class="text-gray-600">We are dedicated to serving others with love and compassion, meeting both spiritual and practical needs in our community.</p>
                    </div>
                </div>
            </div>

            <!-- Vision 150 Section -->
            <div class="bg-gradient-to-r from-primary to-primary/90 text-white rounded-xl p-8 md:p-12 mb-16 md:mb-24 shadow-custom overflow-hidden relative">
                <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
                    <i class="fas fa-map-marked-alt text-9xl"></i>
                </div>

                <div class="relative z-10">
                    <div class="inline-block bg-secondary text-primary font-bold px-4 py-1 rounded-full text-sm mb-4">Our Strategic Vision</div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-8">Vision 150</h2>
                    <p class="mb-8 text-white/90 text-lg max-w-3xl">
                        In 2012, the Lord gave us a vision to plant a Kingdom Centre in every political constituency of Zambia,
                        bringing transformation through the power of the gospel and meeting the holistic needs of communities.
                    </p>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-white/10 p-6 rounded-lg backdrop-blur-sm hover:bg-white/20 transition-all">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-church text-secondary text-xl"></i>
                            </div>
                            <h3 class="text-secondary font-semibold text-xl mb-4">Kingdom Center</h3>
                            <p class="text-white/80">The local church for the ministry where believers enjoy Christian fellowship
                            and edification.</p>
                        </div>
                        <div class="bg-white/10 p-6 rounded-lg backdrop-blur-sm hover:bg-white/20 transition-all">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-graduation-cap text-secondary text-xl"></i>
                            </div>
                            <h3 class="text-secondary font-semibold text-xl mb-4">Teach the Nations</h3>
                            <p class="text-white/80">Educational facilities providing quality education to deserving but vulnerable
                            students.</p>
                        </div>
                        <div class="bg-white/10 p-6 rounded-lg backdrop-blur-sm hover:bg-white/20 transition-all">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-heartbeat text-secondary text-xl"></i>
                            </div>
                            <h3 class="text-secondary font-semibold text-xl mb-4">Heal the Nations</h3>
                            <p class="text-white/80">Health facilities providing physical and spiritual care in constituencies where
                            health facilities are unavailable.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- The City of the Lord Section -->
            <div class="mb-16 md:mb-24">
                <div class="text-center mb-12">
                    <div class="inline-block bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg text-sm mb-4">Our Future</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6">The City of the Lord</h2>
                    <p class="text-gray-600 italic mb-4 max-w-3xl mx-auto text-lg">
                        "....they shall call thee; The City of the Lord, The Zion of the Holy One of Israel."
                        <span class="text-sm">Isaiah 60:14</span>
                    </p>
                </div>

                <div class="relative">
                    <div class="hidden lg:block absolute -left-16 top-1/2 transform -translate-y-1/2 -rotate-90 text-primary/10 text-8xl font-bold">VISION</div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="p-8 bg-white rounded-lg border border-gray-100 shadow-custom hover:shadow-lg transition-all">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mb-6 text-white">
                                <i class="fas fa-praying-hands text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-xl mb-4 text-primary">International Prayer Center</h3>
                            <p class="text-gray-600 mb-6">A mega auditorium and conference facility for church services and other meetings.</p>
                            <div class="w-12 h-1 bg-secondary"></div>
                        </div>

                        <div class="p-8 bg-white rounded-lg border border-gray-100 shadow-custom hover:shadow-lg transition-all">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mb-6 text-white">
                                <i class="fas fa-university text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-xl mb-4 text-primary">Kingdom University</h3>
                            <p class="text-gray-600 mb-6">An international, excellent, multi discipline academic institution.</p>
                            <div class="w-12 h-1 bg-secondary"></div>
                        </div>

                        <div class="p-8 bg-white rounded-lg border border-gray-100 shadow-custom hover:shadow-lg transition-all">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mb-6 text-white">
                                <i class="fas fa-hospital text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-xl mb-4 text-primary">Heal the Nations Hospital</h3>
                            <p class="text-gray-600 mb-6">A world class health facility with a holistic approach to heal spirit, soul and body.</p>
                            <div class="w-12 h-1 bg-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center py-12 px-4 sm:px-6 lg:py-16 lg:px-8 bg-gray-50 rounded-xl shadow-custom">
                <h2 class="text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
                    <span class="block">Ready to join our mission?</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-gray-600 max-w-3xl mx-auto">
                    Whether you're looking to grow in your faith, serve in ministry, or simply learn more about His Kingdom Church,
                    we invite you to be part of what God is doing through this ministry.
                </p>
                <div class="mt-8 flex justify-center">
                    <div class="inline-flex rounded-md shadow">
                        <a href="/contact" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                            Contact Us
                        </a>
                    </div>
                    <div class="ml-3 inline-flex">
                        <a href="/services" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-100">
                            Join Our Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
