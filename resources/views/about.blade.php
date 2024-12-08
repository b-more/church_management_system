<!-- resources/views/about.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - His Kingdom Church</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('layouts.navigation')


    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-[500px]">
        <div class="absolute top-0 w-full h-full bg-center bg-cover" style="background-image: url('/images/about-banner.jpg');">
            <span class="w-full h-full absolute opacity-75 bg-primary"></span>
        </div>
        <div class="container relative mx-auto">
            <div class="items-center flex flex-wrap">
                <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
                    <div class="text-white">
                        <h1 class="text-5xl font-bold">About His Kingdom Church</h1>
                        <p class="mt-4 text-lg">Raising Kingdom Ambassadors</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- History Section -->
            <div class="mb-20">
                <h2 class="text-3xl font-bold text-primary mb-6">Our History</h2>
                <div class="prose max-w-none text-gray-600">
                    <p class="mb-4">
                        The ministry started as HIS KINGDOM CHURCH and was founded as a result of many prophetic utterances 
                        and years of travailing prayer. In 1992 a teenager stumbled into a Pentecostal Church where he met 
                        Jesus Christ and accepted him as Lord and savior. This teenager was to become the founder of this 
                        ministry 16 years later.
                    </p>
                    <p class="mb-4">
                        On 2nd February 2008, Apostle Chris, Pastor Sepiso, and 7 other faithful believers started the 
                        HIS KINGDOM CHURCH cell group at Plot 428 in Avondale, Lusaka, Zambia. A month later, the first 
                        Church service was held at Reens Private School in Chelstone, Lusaka, with an attendance of nine (9) people.
                    </p>
                </div>
            </div>

            <!-- Identity Section -->
            <div class="grid md:grid-cols-2 gap-12 mb-20">
                <div>
                    <h2 class="text-3xl font-bold text-primary mb-6">Who We Are</h2>
                    <p class="text-gray-600 mb-4">
                        We are a multi-faceted, church planting, disciple making and teaching based ministry.
                    </p>
                    <h3 class="text-xl font-semibold text-primary mt-8 mb-4">Our Mission</h3>
                    <p class="text-gray-600">
                        To reach the lost for Christ, train them in the Word of God and send them as leaders, 
                        representatives of Christ and ambassadors of Gods' kingdom to their world.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-primary mb-4">Our Vision</h3>
                    <p class="text-gray-600 mb-4">
                        To be a global ministry which reaches out to the lost, plants churches, makes disciples and raises 
                        leaders of global influence in their sphere of calling.
                    </p>
                    <p class="text-gray-600">
                        As a ministry, it is our vision to be present on every continent and in every country of the world 
                        in a significant and relevant way.
                    </p>
                </div>
            </div>

            <!-- Values Section -->
            <div class="mb-20">
                <h2 class="text-3xl font-bold text-primary mb-10">Our Core Values</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    {/* Add each value as a card */}
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" {/* Add icon SVG */}></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Prayer</h3>
                        <p class="text-gray-600">Prayer is the foundation for all that God does therefore we seek to build 
                        the ministry on effective prayer both individual and corporate.</p>
                    </div>
                    <!-- Add more value cards -->
                </div>
            </div>

            <!-- Vision 150 Section -->
            <div class="bg-primary text-white rounded-lg p-8 md:p-12 mb-20">
                <h2 class="text-3xl font-bold mb-6">Vision 150</h2>
                <p class="mb-8">
                    In 2012, the Lord gave us a vision to plant a Kingdom Centre in every political constituency of Zambia.
                </p>
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-secondary font-semibold text-xl mb-4">Kingdom Center</h3>
                        <p class="text-white/80">The local church for the ministry where believers enjoy Christian fellowship 
                        and edification.</p>
                    </div>
                    <div>
                        <h3 class="text-secondary font-semibold text-xl mb-4">Teach the Nations</h3>
                        <p class="text-white/80">Educational facilities providing quality education to deserving but vulnerable 
                        students.</p>
                    </div>
                    <div>
                        <h3 class="text-secondary font-semibold text-xl mb-4">Heal the Nations</h3>
                        <p class="text-white/80">Health facilities providing physical and spiritual care in constituencies where 
                        health facilities are unavailable.</p>
                    </div>
                </div>
            </div>

            <!-- The City of the Lord Section -->
            <div class="mb-20">
                <h2 class="text-3xl font-bold text-primary mb-6">The City of the Lord</h2>
                <p class="text-gray-600 mb-8">
                    "....they shall call thee; The City of the Lord, The Zion of the Holy One of Israel." Isaiah 60:14
                </p>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-xl mb-4">International Prayer Center</h3>
                        <p class="text-gray-600">A mega auditorium and conference facility for church services and other meetings.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-xl mb-4">Kingdom University</h3>
                        <p class="text-gray-600">An international, excellent, multi discipline academic institution.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-xl mb-4">Heal the Nations Hospital</h3>
                        <p class="text-gray-600">A world class health facility with a holistic approach to heal spirit, soul and body.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

</body>
</html>