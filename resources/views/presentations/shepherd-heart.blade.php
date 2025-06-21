<p class<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Heart of a Shepherd - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#011EB7',
                        secondary: '#E0B041',
                        accent: '#754DA4'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif']
                    },
                    fontSize: {
                        '7xl': '4.5rem',
                        '8xl': '6rem',
                        '9xl': '8rem',
                        '10xl': '10rem'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .slide {
            min-height: 100vh;
            width: 100%;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out;
        }
        .slide.active {
            display: flex;
            opacity: 1;
            visibility: visible;
            z-index: 10;
        }
        .text-shadow {
            text-shadow: 0 4px 8px rgba(0,0,0,0.5);
        }
        .logo-overlay {
            position: fixed;
            top: 30px;
            left: 30px;
            z-index: 100;
            opacity: 0.8;
        }
        .background-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
        }
        .enhanced-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .enhanced-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-controls {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        body {
            overflow: hidden;
            margin: 0;
            padding: 0;
            position: relative;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Church Logo Overlay -->
    <div class="logo-overlay">
        <img src="{{ asset('images/black_logo.png') }}" alt="His Kingdom Church" class="h-20 w-auto brightness-0 invert opacity-90">
    </div>

    <!-- Slide Controls -->
    <div class="slide-controls flex space-x-3">
        <button onclick="previousSlide()" class="bg-primary hover:bg-primary/80 text-white p-3 rounded-full shadow-lg transition-all">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="nextSlide()" class="bg-primary hover:bg-primary/80 text-white p-3 rounded-full shadow-lg transition-all">
            <i class="fas fa-chevron-right"></i>
        </button>
        <button onclick="toggleFullscreen()" class="bg-accent hover:bg-accent/80 text-white p-3 rounded-full shadow-lg transition-all">
            <i class="fas fa-expand"></i>
        </button>
    </div>

    <!-- Slide Counter -->
    <div class="fixed top-6 right-6 bg-black/50 text-white px-4 py-2 rounded-lg z-50">
        <span id="slideCounter">1 / 12</span>
    </div>

    <!-- Slide 1: Title Slide -->
    <div class="slide active bg-gradient-to-br from-primary via-accent to-primary flex items-center justify-center relative background-image" style="background-image: url('{{ asset('images/shepherd-landscape.jpg') }}');">
        <div class="image-overlay"></div>
        <div class="text-center z-10 fade-in px-8">
            <h1 class="text-8xl md:text-9xl font-black mb-8 text-shadow text-white">The Heart of a Shepherd</h1>
            <p class="text-4xl md:text-5xl text-secondary mb-12 font-bold">Valuable insights into the character of David</p>
            <p class="text-3xl md:text-4xl text-white/95 font-semibold">A man after God's own heart</p>
            <div class="mt-16 bg-white/10 backdrop-blur-sm p-8 rounded-xl border border-white/20">
                <p class="text-2xl md:text-3xl text-secondary font-bold">1 Samuel 13:14 • Acts 13:22</p>
            </div>
        </div>
    </div>

    <!-- Slide 2: Scripture Foundation -->
    <div class="slide bg-gradient-to-br from-gray-900 via-gray-800 to-primary/20 flex items-center justify-center background-image" style="background-image: url('{{ asset('images/bible-open.jpg') }}');">
        <div class="image-overlay"></div>
        <div class="max-w-6xl mx-auto text-center px-8 fade-in relative z-10">
            <h2 class="text-7xl font-black text-secondary mb-16 text-shadow">Biblical Foundation</h2>
            <div class="grid md:grid-cols-2 gap-16">
                <div class="enhanced-card p-12 rounded-2xl">
                    <h3 class="text-4xl font-bold mb-6 text-primary">1 Samuel 13:14</h3>
                    <p class="text-2xl italic text-white/95 leading-relaxed">"But now your kingdom will not endure; the Lord has sought out a man after his own heart and appointed him ruler of his people..."</p>
                </div>
                <div class="enhanced-card p-12 rounded-2xl">
                    <h3 class="text-4xl font-bold mb-6 text-secondary">Acts 13:22</h3>
                    <p class="text-2xl italic text-white/95 leading-relaxed">"After removing Saul, he made David their king. God testified concerning him: 'I have found David son of Jesse, a man after my own heart...'"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3: Humility -->
    <div class="slide bg-gradient-to-br from-blue-900 via-primary to-blue-800 flex items-center justify-center background-image" style="background-image: url('{{ asset('images/kneeling-prayer.jpg') }}');">
        <div class="image-overlay"></div>
        <div class="max-w-6xl mx-auto px-8 fade-in relative z-10">
            <div class="text-center mb-16">
                <div class="w-32 h-32 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-8 border border-white/30">
                    <i class="fas fa-praying-hands text-5xl text-white"></i>
                </div>
                <h2 class="text-8xl font-black text-white mb-8 text-shadow">Humility</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-secondary flex items-center">
                        <i class="fas fa-crown mr-4 text-4xl"></i>
                        Recognizing God's Sovereignty
                    </h3>
                    <p class="text-2xl text-white/95 leading-relaxed">David's humility stemmed from his understanding of God's sovereignty and his own limitations.</p>
                </div>
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-secondary flex items-center">
                        <i class="fas fa-graduation-cap mr-4 text-4xl"></i>
                        Willingness to Learn
                    </h3>
                    <p class="text-2xl text-white/95 leading-relaxed">David's humility allowed him to learn from others, including his experiences as a shepherd and his mistakes as a leader.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 4: Patience -->
    <div class="slide bg-gradient-to-br from-yellow-800 via-secondary to-yellow-600 flex items-center justify-center">
        <div class="max-w-6xl mx-auto px-8 fade-in">
            <div class="text-center mb-16">
                <div class="w-32 h-32 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-8 border border-white/30">
                    <i class="fas fa-clock text-5xl text-gray-900"></i>
                </div>
                <h2 class="text-8xl font-black text-white mb-8 text-shadow">Patience</h2>
                <p class="text-3xl text-gray-900 font-bold">Waiting for Your Time</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-white flex items-center">
                        <i class="fas fa-hourglass-half mr-4 text-4xl"></i>
                        Trusting God's Timing
                    </h3>
                    <p class="text-2xl text-gray-100 leading-relaxed">David demonstrated patience by trusting God's timing, even when faced with adversity or uncertainty.</p>
                </div>
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-white flex items-center">
                        <i class="fas fa-seedling mr-4 text-4xl"></i>
                        Preparing for the Future
                    </h3>
                    <p class="text-2xl text-gray-100 leading-relaxed">David's patience allowed him to prepare for future opportunities, such as his anointing as king.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 5: Service -->
    <div class="slide bg-gradient-to-br from-purple-900 via-accent to-purple-700 flex items-center justify-center background-image" style="background-image: url('{{ asset('images/helping-hands.jpg') }}');">
        <div class="image-overlay"></div>
        <div class="max-w-6xl mx-auto px-8 fade-in relative z-10">
            <div class="text-center mb-16">
                <div class="w-32 h-32 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-8 border border-white/30">
                    <i class="fas fa-hands-helping text-5xl text-white"></i>
                </div>
                <h2 class="text-8xl font-black text-white mb-8 text-shadow">Service</h2>
                <p class="text-3xl text-secondary font-bold">Willing to Serve When Nobody Is There</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-secondary flex items-center">
                        <i class="fas fa-eye-slash mr-4 text-4xl"></i>
                        Faithfulness in Obscurity
                    </h3>
                    <p class="text-2xl text-white/95 leading-relaxed">David's willingness to serve as a shepherd, even when unnoticed, demonstrates his faithfulness and commitment to his responsibilities.</p>
                </div>
                <div class="enhanced-card p-10 rounded-2xl">
                    <h3 class="text-3xl font-bold mb-6 text-secondary flex items-center">
                        <i class="fas fa-heart mr-4 text-4xl"></i>
                        Serving with Integrity
                    </h3>
                    <p class="text-2xl text-white/95 leading-relaxed">David's service as a shepherd reflects his integrity and dedication to caring for others, including his flock.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 6: Brokenness -->
    <div class="slide bg-gradient-to-br from-red-900/30 to-red-800/20 flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart-broken text-3xl text-white"></i>
                </div>
                <h2 class="text-5xl font-bold text-red-400 mb-6">Brokenness</h2>
                <p class="text-xl text-gray-300">A Warrior Who Weeps Before God (Psalm 51)</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Recognizing Sin and Guilt
                    </h3>
                    <p class="text-lg">David's brokenness, as seen in Psalm 51, demonstrates his recognition of sin and guilt, and his willingness to seek forgiveness.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-undo mr-3"></i>
                        Seeking Restoration
                    </h3>
                    <p class="text-lg">David's prayer for restoration and cleansing reflects his desire to maintain a right relationship with God.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 7: A Man After God's Heart -->
    <div class="slide bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-3xl text-primary"></i>
                </div>
                <h2 class="text-5xl font-bold text-white mb-6 text-shadow">A Man After God's Own Heart</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/10 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-white flex items-center">
                        <i class="fas fa-search mr-3"></i>
                        Desiring to Seek God
                    </h3>
                    <p class="text-lg text-white/90">David's heart was characterized by a deep desire to seek God and follow His ways.</p>
                </div>
                <div class="bg-white/10 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-white flex items-center">
                        <i class="fas fa-star mr-3"></i>
                        Prioritizing Relationship
                    </h3>
                    <p class="text-lg text-white/90">David's relationship with God was his top priority, guiding his decisions and actions.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 8: Sacrifice -->
    <div class="slide bg-gradient-to-br from-orange-900/40 to-orange-800/20 flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                <h2 class="text-5xl font-bold text-orange-400 mb-6">Sacrifice</h2>
                <p class="text-xl text-gray-300">You Won't Steal My Sheep, Bear</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-gem mr-3"></i>
                        Protecting What is Valuable
                    </h3>
                    <p class="text-lg">David's willingness to protect his flock from predators demonstrates his commitment to safeguarding what is valuable.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-fist-raised mr-3"></i>
                        Demonstrating Courage
                    </h3>
                    <p class="text-lg">David's bravery in defending his flock reflects his courage and willingness to take risks.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 9: A Big Heart -->
    <div class="slide bg-gradient-to-br from-green-900/30 to-green-800/20 flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-3xl text-white"></i>
                </div>
                <h2 class="text-5xl font-bold text-green-400 mb-6">A Big Heart</h2>
                <p class="text-xl text-gray-300">Accommodating Shimei</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-dove mr-3"></i>
                        Showing Mercy and Forgiveness
                    </h3>
                    <p class="text-lg">David's response to Shimei's cursing demonstrates his capacity for mercy and forgiveness.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-hand-paper mr-3"></i>
                        Demonstrating Restraint
                    </h3>
                    <p class="text-lg">David's decision not to retaliate against Shimei reflects his humility and restraint.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 10: Recognizing Rank and Order -->
    <div class="slide bg-gradient-to-br from-purple-900/30 to-purple-800/20 flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-crown text-3xl text-white"></i>
                </div>
                <h2 class="text-5xl font-bold text-purple-400 mb-6">Recognizing Rank and Order</h2>
                <p class="text-xl text-gray-300">Calling Saul, the Lord's Anointed</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-handshake mr-3"></i>
                        Respecting Authority
                    </h3>
                    <p class="text-lg">David's respect for Saul's authority, despite being anointed king himself, demonstrates his understanding of rank and order.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        Prioritizing Obedience
                    </h3>
                    <p class="text-lg">David's decision not to harm Saul reflects his commitment to obeying God's appointed authorities.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 11: Integrity of Heart -->
    <div class="slide bg-gradient-to-br from-blue-900/30 to-blue-800/20 flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-8 fade-in">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-balance-scale text-3xl text-white"></i>
                </div>
                <h2 class="text-5xl font-bold text-blue-400 mb-6">Integrity of Heart</h2>
                <p class="text-xl text-gray-300">His Conscience Troubled Him After Cutting Saul's Garment</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Demonstrating Accountability
                    </h3>
                    <p class="text-lg">David's reaction to cutting Saul's garment demonstrates his accountability to God and his own conscience.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4 text-secondary flex items-center">
                        <i class="fas fa-medal mr-3"></i>
                        Showing Respect for Authority
                    </h3>
                    <p class="text-lg">David's concern about cutting Saul's garment reflects his respect for authority and his recognition of the gravity of his actions.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 12: Conclusion -->
    <div class="slide bg-gradient-to-br from-primary via-accent to-secondary flex items-center justify-center background-image" style="background-image: url('{{ asset('images/church-worship.jpg') }}');">
        <div class="image-overlay"></div>
        <div class="max-w-6xl mx-auto text-center px-8 fade-in relative z-10">
            <h2 class="text-8xl font-black text-white mb-12 text-shadow">Conclusion</h2>
            <div class="enhanced-card p-12 rounded-2xl backdrop-blur-sm">
                <p class="text-3xl text-white leading-relaxed font-semibold">
                    These characteristics of David's heart, as reflected in his life as a shepherd and a leader,
                    offer valuable insights into what it means to be a person after God's own heart.
                </p>
            </div>
            <div class="mt-16 bg-white/10 backdrop-blur-sm p-8 rounded-xl border border-white/20 flex items-center justify-center">
                <img src="{{ asset('images/black_logo.png') }}" alt="His Kingdom Church" class="h-16 w-auto brightness-0 invert mr-6">
                <div class="text-left">
                    <p class="text-3xl text-secondary font-bold">His Kingdom Church</p>
                    <p class="text-2xl text-white/90 font-medium mt-2">Raising Kingdom Ambassadors</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function updateSlideCounter() {
            document.getElementById('slideCounter').textContent = `${currentSlide + 1} / ${totalSlides}`;
        }

        function showSlide(n) {
            // Remove active class from all slides
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
                console.log(`Slide ${index} - removed active class`);
            });

            // Calculate new slide index
            if (n >= totalSlides) {
                currentSlide = 0;
            } else if (n < 0) {
                currentSlide = totalSlides - 1;
            } else {
                currentSlide = n;
            }

            // Add active class to current slide
            slides[currentSlide].classList.add('active');
            console.log(`Slide ${currentSlide} - added active class`);

            updateSlideCounter();
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function previousSlide() {
            showSlide(currentSlide - 1);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowRight':
                case ' ':
                    nextSlide();
                    break;
                case 'ArrowLeft':
                    previousSlide();
                    break;
                case 'Escape':
                    if (document.fullscreenElement) {
                        document.exitFullscreen();
                    }
                    break;
                case 'f':
                case 'F':
                    toggleFullscreen();
                    break;
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log(`Total slides found: ${totalSlides}`);

            // Make sure all slides start hidden
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
                console.log(`Initialized slide ${index}`);
            });

            // Show first slide
            if (slides.length > 0) {
                slides[0].classList.add('active');
                console.log('First slide activated');
            }

            updateSlideCounter();

            // Prevent page scrolling
            document.body.style.overflow = 'hidden';
        });
    </script>
</body>
</html>
