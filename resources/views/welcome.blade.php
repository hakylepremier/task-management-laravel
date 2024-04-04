<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
            <livewire:welcome.navigation />
        @endif


    </div>
    <footer className="py-8 lg:px-0 md:px-8 px-0">
        <div className="flex pb-6 md:px-0 px-8 gap-4  border-b border-gray-600 max-w-4xl m-auto ">
            <article className="flex-1">
                <h2 className="pb-2 text-lg font-bold text-teal-700 uppercase">
                    Humphrey Yeboah
                </h2>
                <p>
                    A full stack and mobile developer ready to help to bring your
                    business online.
                </p>
            </article>
            <article>
                <h2 className="pb-2 text-lg font-bold text-teal-700 uppercase">
                    Socials
                </h2>
                <div className="sm:block grid grid-cols-2">
                    <a href="https://www.linkedin.com/in/humphrey-yeboah-9850881b3/"
                        className="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on LinkedIn">
                        <i className="text-lg fa-brands fa-linkedin" />
                    </a>
                    <a href="https://www.twitter.com/hakylepremier"
                        className="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Twitter">
                        <i className="text-lg fa-brands fa-x-twitter" />
                    </a>
                    <a href="https://github.com/hakylepremier"
                        className="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Github">
                        <i className="text-lg fa-brands fa-github" />
                    </a>
                    <a href="https://facebook.com/humphrey.yeboah.5"
                        className="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Facebook">
                        <i className="text-lg fa-brands fa-facebook" />
                    </a>
                </div>
            </article>
        </div>
        <p className="px-8 pt-6 text-center">
            &copy; Copyright
            <script>
                new Date().getFullYear()
                }, Made by {
                    " "
                }
            </script>
            <a href="http://humphreyyeboah.com" target="_blank" rel="noopener noreferrer"
                className="font-bold text-inherit text-teal-700 border-b dark:border-b-white hover:text-neutral hover:dark:text-gray-300 border-b-gray-600 pb-1">
                Humphrey Yeboah
            </a>
        </p>
        <p className="text-center pt-4">
            <a href="https://www.pexels.com/photo/flat-lay-photography-of-macbook-pro-beside-white-spiral-notebook-and-green-mug-434337/"
                className="text-center text-gray-300 border-b-2" target="_blank" rel="noopener noreferrer">
                Photo by Pixabay
            </a>
        </p>
    </footer>
</body>

</html>
