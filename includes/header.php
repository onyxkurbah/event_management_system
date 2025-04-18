<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5a65f1', // Bright blue
                        secondary: '#32325d', // Dark blue
                        accent: '#f6416c', // Pink
                        success: '#00b894', // Green
                        warning: '#fdcb6e', // Yellow
                        danger: '#ff7675', // Red
                        dark: '#2d3436',
                        light: '#f8f9fa',
                    },
                    fontFamily: {
                        pixel: ['"Press Start 2P"', 'cursive'],
                    },
                    boxShadow: {
                        'pixel': '4px 4px 0 rgba(0,0,0,0.2)',
                        'pixel-sm': '2px 2px 0 rgba(0,0,0,0.2)',
                        'pixel-lg': '6px 6px 0 rgba(0,0,0,0.2)',
                    },
                },
            },
        }
    </script>
    <!-- Custom styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        
        * {
            image-rendering: pixelated;
        }
        
        body {
            background-color: #a8d8e0; /* Light blue background to match the GIF */
            position: relative;
        }
        
        /* Background GIF */
        .bg-gif-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 300px; /* Fixed height for the GIF */
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }
        
        .bg-gif {
            width: 100%;
            height: auto;
            max-height: 100%;
            object-fit: contain;
            object-position: bottom;
            image-rendering: pixelated;
        }
        
        /* Content container to ensure proper spacing from the GIF */
        .content-container {
            min-height: calc(100vh - 300px); /* Ensure content doesn't overlap with GIF */
            padding-bottom: 20px;
        }
        
        .pixel-border {
            border-style: solid;
            border-width: 4px;
            border-image-slice: 2;
            border-image-width: 2;
            border-image-outset: 0;
            border-image-source: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='6' height='6'%3E%3Cpath d='M0 2h6M2 0v6' stroke='%23000' fill='none'/%3E%3C/svg%3E");
            border-image-repeat: space;
            image-rendering: pixelated;
        }
        
        .pixel-button {
            position: relative;
            display: inline-block;
            box-shadow: 0 4px 0 0 #000;
            transition: all 0.1s ease;
            image-rendering: pixelated;
        }
        
        .pixel-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 0 #000;
        }
        
        .pixel-button:active {
            transform: translateY(2px);
            box-shadow: 0 2px 0 0 #000;
        }
        
        .pixel-input {
            border: 2px solid #000;
            box-shadow: 3px 3px 0 0 #000;
            background-color: #fff;
            transition: all 0.1s ease;
        }
        
        .pixel-input:focus {
            outline: none;
            box-shadow: 5px 5px 0 0 #000;
        }
        
        .pixel-card {
            border: 2px solid #000;
            box-shadow: 6px 6px 0 0 #000;
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .pixel-table th, .pixel-table td {
            border: 2px solid #000;
        }
        
        .pixel-table th {
            background-color: #5a65f1;
            color: #fff;
        }
        
        .pixel-table tr:nth-child(even) {
            background-color: rgba(240, 240, 240, 0.95);
        }
        
        .pixel-table tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .animate-pixel {
            animation: pixelFadeIn 0.3s steps(5) forwards;
        }
        
        @keyframes pixelFadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        main {
            background-color: rgba(248, 249, 250, 0.85);
            border-radius: 8px;
            border: 2px solid #000;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-dark font-pixel text-xs">
    <!-- Background GIF -->
    <div class="bg-gif-container">
        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/working-AQDNRVXYmPUpxM9ZswHiR7UV9H9Hop.gif" alt="Pixel Art Office" class="bg-gif">
    </div>

    <header class="bg-white border-b-2 border-black sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-10 h-10 bg-primary border-2 border-black flex items-center justify-center mr-3 shadow-pixel">
                        <span class="text-white text-xs">EM</span>
                    </div>
                    <h1 class="text-lg text-secondary">EVENT MANAGER</h1>
                </div>
                <nav class="flex gap-4">
                    <a href="index.php" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
                        HOME
                    </a>
                    <a href="create_event.php" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black">
                        NEW
                    </a>
                    <a href="register_attendee.php" class="pixel-button bg-success text-white px-4 py-2 border-2 border-black">
                        REGISTER
                    </a>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="content-container">
        <main class="flex-1 container mx-auto px-4 py-8">