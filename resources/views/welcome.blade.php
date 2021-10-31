<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Attla</title>
        <link rel="icon" type="image/png" sizes="32x32" href="@asset('/img/favicon-32x32.png')">
        <link rel="icon" type="image/png" sizes="16x16" href="@asset('/img/favicon-16x16.png')">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.hover\:underline:hover{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}body{font-family:'Nunito', sans-serif}@font-face{font-family:Archicoco;src:url(@asset('/fonts/ArchicocoRegular.ttf')) format('truetype');font-weight:700;font-style:normal}.attla-logo svg{display:inline;width:4rem;margin-right:.7rem;margin-top:-.5rem}.attla-logo{font-family:Archicoco;font-size:4rem;color:#000;background:#9493df;background:-moz-linear-gradient(bottom,#6c8de7 0%,#cd9cd2 75%);background:-webkit-linear-gradient(bottom,#6c8de7 0%,#cd9cd2 75%);background:linear-gradient(to top,#6c8de7 0%,#cd9cd2 75%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6c8de7',endColorstr='#cd9cd2',GradientType=1);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (route()->has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="@route('track.dashboard')" class="text-sm text-gray-700 dark:text-white hover:underline">Home</a>
                    @else
                        <a href="@route('login')" class="text-sm text-gray-700 dark:text-white hover:underline">Log in</a>
                        @if (route()->has('register'))
                            <a href="@route('register')" class="ml-4 text-sm text-gray-700 dark:text-white hover:underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <span class="attla-logo">
                        <svg viewBox="0 0 36 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Attla logo</title>
                            <defs>
                                <linearGradient x1="78.6063975%" y1="2.57282033%" x2="116.400857%" y2="143.648749%" id="linearGradient-1">
                                    <stop stop-color="#DD9FCF" offset="0%"></stop>
                                    <stop stop-color="#007BFF" offset="100%"></stop>
                                </linearGradient>
                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Group" fill="url(#linearGradient-1)" fill-rule="nonzero">
                                    <path d="M1.12229,24.937705 C2.11861,16.920805 6.27157,14.349305 9.99018,12.070905 C13.7873,9.743295 17.0716,7.704665 16.6765,0.0451050014 C14.9072,0.171463 13.2074,0.542489 11.6123,1.130015 C11.7775,7.208885 8.85072,9.011705 6.01063,10.752505 C3.01104,12.590805 0.177505,14.327605 1.17015e-08,21.181505 C0.237219,22.486105 0.614315,23.745705 1.12229,24.937705 Z" id="Shape"></path>
                                    <path d="M5.145145,9.38594 C7.792185,7.7626 9.932875,6.43463 9.981135,1.82135001 C5.163145,4.14489 1.531245,8.49177 0.231445005,13.72955 C1.565595,11.58151 3.419995,10.44268 5.145145,9.38594 Z" id="Shape"></path>
                                    <path d="M5.6289,31.2459 C6.27348,22.7018 10.0837,19.7199 13.77858,16.833 C17.79248,13.6974 21.58558,10.726 21.37208,0.301811 C20.37658,0.119115 19.35408,0.0169014 18.30948,0 C18.73318,8.58028 14.73728,11.0575 10.85507,13.4366 C6.92215,15.8471 2.863254,18.3493 2.55077994,27.6185 C3.413765,28.9569 4.44853,30.1771 5.6289,31.2459 Z" id="Shape"></path>
                                    <path d="M25.2206,21.8519 C28.8869,18.9569 32.3454,16.2012 32.1605,6.59475 C31.1732,5.41085 30.0354,4.356523 28.7773,3.45269997 C28.7258,13.9477 24.5793,17.2282 20.56052,20.4008 C16.77811,23.3875 13.207559,26.2133 12.9458007,35.3022 C14.45418,35.7288 16.04028,35.971 17.67954,36 C18.11063,27.4905 21.7188,24.6181 25.2206,21.8519 Z" id="Shape"></path>
                                    <path d="M31.3654,25.60644 C28.73963,27.18068 26.462332,28.56097 26.1973013,34.0966 C31.23207,31.6072 34.94331,26.9006 36.00016,21.2836998 C34.82061,23.53239 33.03165,24.60764 31.3654,25.60644 Z" id="Shape"></path>
                                    <path d="M30.5144,24.23268 C33.3267,22.54568 35.9844,20.95378 35.6605,13.55175 L35.7447,13.54853 C35.3128,11.87931 34.6388,10.30426 33.7684,8.85718009 C33.3815,17.44471 29.7643,20.32918 26.24613,23.10748 C22.89808,25.74898 19.739797,28.26568 19.3218007,35.95498 C21.14511,35.82778 22.89481,35.43588 24.53407,34.82018 C24.63387,27.75858 27.75697,25.88578 30.5144,24.23268 Z" id="Shape"></path>
                                    <path d="M19.53751,19.145295 C23.55801,15.971795 27.35681,12.965795 27.13431,2.400005 C25.85161,1.670825 24.47091,1.090543 23.01731,0.682494992 C23.13431,11.579895 18.89701,14.889295 14.79562,18.093395 C11.06065,21.010895 7.535906,23.782695 7.19970976,32.517495 C8.46515,33.432595 9.85411,34.188295 11.33795,34.762195 C11.74204,25.325595 15.69951,22.175495 19.53751,19.145295 Z" id="Shape"></path>
                                </g>
                            </g>
                        </svg>
                        Attla
                    </span>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">

                    <div class="grid grid-cols-1 md:grid-cols-1">
                        <a href="https://attla.github.io" class="text-gray-900 dark:text-white">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold">Documentation</div>
                                </div>

                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                        Attla has wonderful, thorough documentation covering every aspect of the framework. Whether you are new to the framework or have previous experience with Attla, we recommend reading all of the documentation from beginning to end.
                                    </div>
                                </div>
                            </div>
                        </a>

                        {{-- <a href="" class="text-gray-900 dark:text-white">
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold">Screencast</div>
                                </div>

                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">

                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-gray-900 dark:text-white">
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold">News</div>
                                </div>

                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">

                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-gray-900 dark:text-white">
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">Vibrant Ecosystem</div>
                                </div>

                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">

                                    </div>
                                </div>
                            </div>
                        </a> --}}
                    </div>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    {{-- <div class="text-center text-sm text-gray-500 sm:text-left">
                        <div class="flex items-center">
                            <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="-mt-px w-5 h-5 text-gray-400">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>

                            <a href="" class="ml-1 underline">
                                Shop
                            </a>
                        </div>
                    </div> --}}

                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Attla {{ app()->version() }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>