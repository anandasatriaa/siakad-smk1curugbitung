<?php

$lines = file('resources/views/welcome.blade.php');
if (!$lines) {
    die("Cannot read welcome.blade.php\n");
}

$app = [];
$sidebar = [];
$navbar = [];

$state = 'app';

foreach ($lines as $line) {
    if (strpos($line, '<!-- Menu -->') !== false) {
        $app[] = "        @include('layouts.sidebar')\n";
        $state = 'sidebar';
        $sidebar[] = $line;
        continue;
    }
    if (strpos($line, '<!-- / Menu -->') !== false) {
        $sidebar[] = $line;
        $state = 'app';
        continue;
    }
    
    if (strpos($line, '<!-- Navbar -->') !== false) {
        $app[] = "          @include('layouts.navbar')\n";
        $state = 'navbar';
        $navbar[] = $line;
        continue;
    }
    if (strpos($line, '<!-- / Navbar -->') !== false) {
        $navbar[] = $line;
        $state = 'app';
        continue;
    }

    if (strpos($line, '<!-- Content -->') !== false) {
        $app[] = $line;
        $app[] = "            @yield('content')\n";
        $state = 'content';
        continue;
    }
    if (strpos($line, '<!-- / Content -->') !== false) {
        $state = 'app';
        $app[] = $line;
        continue;
    }

    if ($state === 'app') {
        $app[] = str_replace('../assets/', "{{ asset('assets/') }}/", $line);
    } elseif ($state === 'sidebar') {
        $sidebar[] = str_replace('../assets/', "{{ asset('assets/') }}/", $line);
    } elseif ($state === 'navbar') {
        $navbar[] = str_replace('../assets/', "{{ asset('assets/') }}/", $line);
    }
}

if (!is_dir('resources/views/layouts')) {
    mkdir('resources/views/layouts', 0755, true);
}

file_put_contents('resources/views/layouts/app.blade.php', implode("", $app));
file_put_contents('resources/views/layouts/sidebar.blade.php', implode("", $sidebar));
file_put_contents('resources/views/layouts/navbar.blade.php', implode("", $navbar));

echo "Layout split cleanly.\n";
