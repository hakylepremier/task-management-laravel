@php
    $videoAdded = false;
    $pendingFeatures = [
        [
            'name' => 'Get recommendations based on the thinkers you follow',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Get recommendations based on your liked thoughts',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Complete the new UI',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Add chat functionality',
            'description' => 'Yet be added',
        ],
    ];

    $actions = [
        [
            'name' => 'Try out the features with a dummy account',
            'atext' => 'here',
            'href' => '/login',
        ],
        [
            'name' => 'Explore the list of features added and planned',
            'atext' => 'here',
            'href' => '#features',
        ],
        [
            'name' =>
                'Learn more about the project, like what technology is being used, how it\'s being developed and many more',
            'atext' => 'here',
            'href' => 'https://humphreyyeboah.com/projects/taskify',
        ],
        // [
        //     'name' => 'Watch a Youtube video walkthrough of the features that have been added thus far',
        //     'atext' => 'here',
        //     'href' => '#video-walkthrough',
        // ],
    ];

    $completedFeatures = [
        [
            'name' => 'Get recommendations based on the thinkers you follow',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Get recommendations based on your liked thoughts',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Complete the new UI',
            'description' => 'Yet be added',
        ],
        [
            'name' => 'Add chat functionality',
            'description' => 'Yet be added',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <main class="text-slate-600 ">
        <header
            class="relative flex flex-col items-center justify-center w-full min-h-screen gap-4 py-8 border-b-2 border-gray-200 font-poppins px-8">
            <livewire:welcome.navigation />
            <div class="z-10">

                <h1 class="max-w-6xl mx-auto text-6xl font-bold text-center text-teal-700 font-merriweather ">
                    Get right on Task with Taskify
                </h1>
                <div class="max-w-6xl mx-auto pt-2">
                    <p class="pb-6 text-xl text-slate-600 text-center font-inter">
                        Taskify is currently still under development hence this placeholder homepage but you can:
                    </p>
                    <ul class="flex flex-col gap-4 rounded-lg font-inter">
                        @foreach ($actions as $action)
                            <li
                                class="px-8 py-4 bg-white rounded-xl shadow-[10px_10px_1px_rgba(15,_118,_110,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                                {{ $action['name'] }}
                                <a href="{{ $action['href'] }}" class="text-teal-500 border-b-2 border-teal-500">
                                    {{ $action['atext'] }}
                                </a>
                                .
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div style="background-image: url('{{ Vite::asset('resources/images/bg-image.webp') }}')"
                class="absolute top-0 left-0 w-full h-full bg-cover md:bg-center blur bg-[left_-17rem_top_-2rem] bg-none position z-0 ">
            </div>
        </header>
        {{-- <section
            class="flex flex-col items-center justify-center w-full min-h-screen py-16 lg:px-6 px-8 bg-gray-100">
            <h2>Gallery</h2>
        </section> --}}
        @if ($videoAdded)
            <section class="flex flex-col items-center justify-center w-full sm:min-h-screen sm:py-16 py-32"
                id="video-walkthrough">
                <h2 class="text-3xl font-bold text-center text-teal-700 font-merriweather pb-4">
                    Video walkthrough
                </h2>
                <iframe // width="1035" // height="582"
                    class="max-w-3xl md:w-2/3 w-4/5  aspect-video p-4 bg-slate-400 rounded-lg shadow-[0px_10px_1px_rgba(211,_211,_211,_1),_0_10px_20px_rgba(204,_204,_204,_1)]"
                    src="https://www.youtube.com/embed/e2nkq3h1P68" title="Learn Accessibility - Full a11y Tutorial"
                    frameBorder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerPolicy="strict-origin-when-cross-origin" allowFullScreen />
                <details class="transition-all duration-300 ease-in-out w-full mt-6">
                    <summary
                        class="transition-all duration-300 ease-in-out p-4 rounded-lg bg-gray-200 list-none cursor-pointer flex gap-4 items-center justify-between max-w-3xl md:w-2/3 w-4/5 m-auto ">
                        <h4>Previous Video Updates</h4>
                        {/* <i class="text-lg fa-brands fa-facebook" /> */}
                        <div class="relative w-4 h-4">
                            <i class="fa-solid absolute top-0 right-0 fa-chevron-down detail-open" />
                            <i class="fa-solid absolute top-0 right-0 fa-chevron-up detail-close" />
                        </div>
                    </summary>
                    <div class="max-w-3xl md:w-2/3 w-4/5 m-auto px-8 py-4">
                        <iframe // width="1035" // height="582"
                            class="w-full m-auto aspect-video p-4 bg-slate-400 rounded-lg"
                            src="https://www.youtube.com/embed/e2nkq3h1P68"
                            title="Learn Accessibility - Full a11y Tutorial" frameBorder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerPolicy="strict-origin-when-cross-origin" allowFullScreen />
                    </div>
                </details>
            </section>
        @endif

        <section id="features"
            class="flex flex-col items-center justify-center w-full min-h-screen py-16 lg:px-6 px-8 bg-gray-100">
            <h2 class="text-3xl font-bold text-center text-teal-700 font-merriweather pb-4">
                Features
            </h2>
            <div class="flex lg:gap-8 gap-12 max-w-5xl flex-col lg:flex-row">
                <article class="flex-1">
                    <h3 class="text-teal-600 lg:pl-4 lg:text-left text-center text-xl pb-4 font-merriweather">
                        Completed Features
                    </h3>
                    <ul
                        class="space-y-5 rounded-xl shadow-[10px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)] p-6">
                        @foreach ($completedFeatures as $feature)
                            <li>
                                <details class="transition-all duration-300 ease-in-out">
                                    <summary
                                        class="transition-all duration-300 ease-in-out p-4 rounded-lg bg-gray-200 list-none cursor-pointer flex gap-4 items-center justify-between">
                                        <h4>{{ $feature['name'] }}</h4>
                                        {{-- {/* <i class="text-lg fa-brands fa-facebook" /> */} --}}
                                        <div class="relative w-4 h-4">
                                            <i class="fa-solid absolute top-0 right-0 fa-chevron-down detail-open"></i>
                                            <i class="fa-solid absolute top-0 right-0 fa-chevron-up detail-close"></i>
                                        </div>
                                    </summary>
                                    <p class="p-2">{{ $feature['description'] }}</p>
                                </details>
                            </li>
                        @endforeach
                    </ul>
                </article>
                <article class="flex-1">
                    <h3 class="text-teal-600 lg:pl-4 text-xl lg:text-left text-center pb-4 font-merriweather">
                        Pending Features
                    </h3>
                    <ul
                        class="space-y-5 rounded-xl shadow-[10px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)] p-6">
                        @foreach ($pendingFeatures as $feature)
                            <li>
                                <details class="transition-all duration-300 ease-in-out">
                                    <summary
                                        class="transition-all duration-300 ease-in-out p-4 rounded-lg bg-gray-200 list-none cursor-pointer flex gap-4 items-center justify-between">
                                        <h4>{{ $feature['name'] }}</h4>
                                        <div class="relative w-4 h-4">
                                            <i class="fa-solid absolute top-0 right-0 fa-chevron-down detail-open"></i>
                                            <i class="fa-solid absolute top-0 right-0 fa-chevron-up detail-close"></i>
                                        </div>
                                    </summary>
                                    <p class="p-2">{{ $feature['description'] }}</p>
                                </details>
                            </li>
                        @endforeach
                    </ul>
                </article>
            </div>
        </section>
    </main>
    <footer class="py-8 lg:px-0 md:px-8 px-0">
        <div class="flex pb-6 md:px-0 px-8 gap-4  border-b border-gray-600 max-w-4xl m-auto ">
            <article class="flex-1">
                <h2 class="pb-2 text-lg font-bold text-teal-700 uppercase">
                    Humphrey Yeboah
                </h2>
                <p>
                    A full stack and mobile developer ready to help to bring your
                    business online.
                </p>
            </article>
            <article>
                <h2 class="pb-2 text-lg font-bold text-teal-700 uppercase">
                    Socials
                </h2>
                <div class="sm:block grid grid-cols-2">
                    <a href="https://www.linkedin.com/in/humphrey-yeboah-9850881b3/"
                        class="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on LinkedIn">
                        <i class="text-lg fa-brands fa-linkedin"></i>
                    </a>
                    <a href="https://www.twitter.com/hakylepremier"
                        class="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Twitter">
                        <i class="text-lg fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://github.com/hakylepremier"
                        class="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Github">
                        <i class="text-lg fa-brands fa-github"></i>
                    </a>
                    <a href="https://facebook.com/humphrey.yeboah.5"
                        class="p-2 transition-colors rounded hover:dark:bg-gray-800 hover:bg-gray-400 hover:text-white"
                        target="_blank" rel="noopener noreferrer" title="Humphrey Yeboah on Facebook">
                        <i class="text-lg fa-brands fa-facebook"></i>
                    </a>
                </div>
            </article>
        </div>
        <p class="px-8 pt-6 text-center">
            &copy; Copyright
            <script>
                new Date().getFullYear()
                }, Made by {
                    " "
                }
            </script>
            <a href="http://humphreyyeboah.com" target="_blank" rel="noopener noreferrer"
                class="font-bold text-inherit text-teal-700 border-b dark:border-b-white hover:text-neutral hover:dark:text-gray-300 border-b-gray-600 pb-1">
                Humphrey Yeboah
            </a>
        </p>
        <p class="text-center pt-4">
            <a href="https://www.pexels.com/photo/flat-lay-photography-of-macbook-pro-beside-white-spiral-notebook-and-green-mug-434337/"
                class="text-center text-gray-300 border-b-2" target="_blank" rel="noopener noreferrer">
                Photo by Pixabay
            </a>
        </p>
    </footer>
</body>

</html>
