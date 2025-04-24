</main>
    </div>
    
    <footer class="bg-dark text-white py-6 mt-auto border-t-2 border-black relative z-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-xs font-pixel">&copy; <?= date('Y') ?> EVENTS</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-white hover:text-warning transition-colors duration-200 font-pixel">
                        ABOUT
                    </a>
                    <a href="#" class="text-white hover:text-warning transition-colors duration-200 font-pixel">
                        HELP
                    </a>
                    <a href="#" class="text-white hover:text-warning transition-colors duration-200 font-pixel">
                        CONTACT
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Simple script to check if font is loaded -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if the Press Start 2P font is loaded
        document.fonts.ready.then(function() {
            if (!document.fonts.check('1em "Press Start 2P"')) {
                console.log("Pixel font not available, using fallback");
                document.querySelectorAll('.font-pixel').forEach(function(el) {
                    el.style.fontFamily = 'Courier New, monospace';
                });
            }
        });
    });
    </script>
</body>
</html>