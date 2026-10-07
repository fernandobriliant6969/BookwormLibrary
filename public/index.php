<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discontinued</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f17;
            color: #f3f4f6;
            overflow-x: hidden;
        }

        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.45;
            animation: float 18s ease-in-out infinite alternate;
        }

        .orb-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
            animation-delay: 0s;
        }

        .orb-2 {
            bottom: -15%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #a855f7 0%, rgba(168, 85, 247, 0) 70%);
            animation-delay: -5s;
        }

        .orb-3 {
            top: 40%;
            left: 30%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
            animation-delay: -10s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1);
            }
            50% {
                transform: translate(60px, 80px) scale(1.1);
            }
            100% {
                transform: translate(-40px, -50px) scale(0.9);
            }
        }

        /* Subtle Grid Pattern Overlay */
        .grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        .glass-card {
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 50%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-link {
            background: linear-gradient(135deg, #c084fc 0%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .gradient-link:hover {
            filter: brightness(1.2);
            text-shadow: 0 0 15px rgba(192, 132, 252, 0.5);
        }

        .animate-fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.25s; }
        .delay-3 { animation-delay: 0.4s; }
        .delay-4 { animation-delay: 0.55s; }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-dot {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between items-center relative p-6 selection:bg-indigo-500 selection:text-white">

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="absolute inset-0 grid-pattern"></div>
    </div>

    <div class="flex items-center gap-2.5 px-4 py-2 rounded-full glass-card border border-white/5">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 pulse-dot"></span>
            <span class="text-xs uppercase tracking-widest font-semibold text-gray-400">Offline / Inactive</span>
        </div>

    <main class="my-auto w-full max-w-2xl text-center z-10 py-12">
        
        <div class="glass-card p-8 md:p-14 rounded-3xl relative overflow-hidden group border border-white/10 hover:border-indigo-500/30 transition-all duration-500 shadow-2xl">
            
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-80"></div>

            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 mb-8 animate-fade-up delay-1 shadow-inner">
                <i class="fa-solid fa-power-off text-2xl"></i>
            </div>

            <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-6 gradient-text animate-fade-up delay-2 leading-tight">
                This website is now discontinued.
            </h1>

            <p class="text-gray-400 text-sm sm:text-base max-w-lg mx-auto mb-8 font-light animate-fade-up delay-3 leading-relaxed">
                Terima kasih telah mengunjungi website ini. Website ini tidak beroperasi lagi karena project telah selesai digunakan.
            </p>

            <div class="pt-6 border-t border-white/5 animate-fade-up delay-4 flex items-center justify-center gap-2">
                <span class="text-gray-400 font-medium text-lg">Thanks to</span>
                <a id="displayLink" href="https://github.com/fernandobriliant6969/BookwormLibrary" target="_blank" class="text-lg font-bold gradient-link underline decoration-purple-500/40 underline-offset-8 hover:decoration-purple-400 transition-all flex items-center gap-1.5 group/link">
                    <span id="displayName">Kelompok 1</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-70 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </main>

    <footer class="w-full text-center py-4 z-10 text-xs text-gray-500 font-light animate-fade-up delay-4">
        <p>&copy; <span id="year"></span> All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
