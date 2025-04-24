<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    
    <!-- Production-ready Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Google Fonts with preconnect for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        /* Define custom colors directly instead of using Tailwind config */
        .bg-primary { background-color: #5a65f1; }
        .bg-secondary { background-color: #32325d; }
        .bg-accent { background-color: #f6416c; }
        .bg-success { background-color: #00b894; }
        .bg-warning { background-color: #fdcb6e; }
        .bg-danger { background-color: #ff7675; }
        .bg-dark { background-color: #2d3436; }
        .bg-light { background-color: #f8f9fa; }
        
        .text-primary { color: #5a65f1; }
        .text-secondary { color: #32325d; }
        .text-accent { color: #f6416c; }
        .text-success { color: #00b894; }
        .text-warning { color: #fdcb6e; }
        .text-danger { color: #ff7675; }
        .text-dark { color: #2d3436; }
        .text-light { color: #f8f9fa; }
        
        /* Apply pixel font globally to specific elements */
        h1, h2, h3, h4, h5, h6, 
        button, .pixel-button, 
        th, td, div, label, 
        .font-pixel {
            font-family: 'Press Start 2P', 'Courier New', monospace !important;
        }
        
        /* Basic styles */
        body {
            background-color: #a8d8e0; /* Light blue background */
            position: relative;
            font-family: 'Courier New', monospace;
            font-size: 12px;
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
        }
        
        /* Content container */
        .content-container {
            min-height: calc(100vh - 300px); /* Ensure content doesn't overlap with GIF */
            padding-bottom: 20px;
        }
        
        /* Pixel button style */
        .pixel-button {
            position: relative;
            display: inline-block;
            box-shadow: 4px 4px 0 0 #000;
            transition: all 0.1s ease;
            cursor: pointer;
        }
        
        .pixel-button:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 0 0 #000;
        }
        
        .pixel-button:active {
            transform: translateY(2px);
            box-shadow: 2px 2px 0 0 #000;
        }
        
        /* Pixel input style */
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
        
        /* Pixel card style */
        .pixel-card {
            border: 2px solid #000;
            box-shadow: 6px 6px 0 0 #000;
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        /* Pixel table style */
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
        
        /* Simple animation */
        @keyframes pixelFadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-pixel {
            animation: pixelFadeIn 0.3s ease-out forwards;
        }
        
        /* Main content area */
        main {
            background-color: rgba(248, 249, 250, 0.85);
            border: 2px solid #000;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-dark text-xs">
    <!-- Background GIF - using a relative path instead of a blob URL -->
    <div class="bg-gif-container">
        <img src="assets/pixel-bg.gif" alt="Pixel Art Office" class="bg-gif">
    </div>

    <header class="bg-white border-b-2 border-black sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-10 h-10 bg-primary border-2 border-black flex items-center justify-center mr-3" style="box-shadow: 3px 3px 0 0 #000;">
                        <span class="text-white text-xs">EM</span>
                    </div>
                    <h1 class="text-lg text-secondary">EVENT MANAGER</h1>
                </div>
                <nav class="flex flex-wrap gap-4">
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