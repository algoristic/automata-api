<?php

include_once __DIR__ . '/automaton.php';
include_once __DIR__ . '/image.php';

define('DIMENSIONS_MAX', 4096);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Not allowed', 405);
    }

    $request = strtok($_SERVER['REQUEST_URI'], '?');
    $path = substr($request, 10);
    $path_params = explode('/', $path);
    $query_params = $_GET;
    $count = count($path_params);

    if ($count === 1 && $path_params[1] === null) {
        header('Location: https://github.com/algoristic/automata-api/blob/main/README.md', true);
        exit();
    }

    if ($count > 5) {
        throw new Exception('Path contains unrecognized parameters', 404);
    }

    $filetype = 'png';
    $filetype_changes = 0;
    if ($count > 4 && $path_params[4] !== '') {
        $file_tokens = explode('.', $path_params[4]);
        $filetype = $file_tokens[count($file_tokens) - 1];
        $filetype_changes++;
    }

    $start = 'single';
    $seed = floor(microtime(true) * 1000);
    if ($count > 3) {
        if (is_numeric($path_params[3])) {
            $start = 'random';
            $seed = intval($path_params[3]);
        } else if ($path_params[3] !== '') {
            $conf_or_file = explode('.', $path_params[3]);
            if (count($conf_or_file) < 2) {
                $start = $conf_or_file[0];
            } else {
                $file_tokens = $conf_or_file;
                $filetype = $file_tokens[count($file_tokens) - 1];
                $filetype_changes++;
            }
        }
    }
    if ($start === 'random') {
        srand($seed);
    }

    $generations = null;
    if ($count > 2) {
        if (is_numeric($path_params[2])) {
            $generations = intval($path_params[2]);
        } else if ($path_params[2] !== '') {
            $file_tokens = explode('.', $path_params[2]);
            $filetype = $file_tokens[count($file_tokens) - 1];
            $filetype_changes++;
        }
    }

    $size = null;
    if ($count > 1) {
        if (is_numeric($path_params[1])) {
            $size = intval($path_params[1]);
            if (is_null($generations)) {
                $generations = $size;
            }
        }
    }

    $rule = null;
    if ($count > 0) {
        if (is_numeric($path_params[0])) {
            $rule = intval($path_params[0]);
        }
    }

    $scale = $query_params['scale'] ?? $query_params['k'] ?? 2;
    $alive_hex_code = $query_params['alive'] ?? $query_params['a'] ?? '000000';
    $alive_interpolate_hex_code = $query_params['alive-interpolate'] ?? $query_params['ai'] ?? $alive_hex_code;
    $dead_hex_code = $query_params['dead'] ?? $query_params['d'] ?? 'ffffff';
    $dead_interpolate_hex_code = $query_params['dead-interpolate'] ?? $query_params['di'] ?? $dead_hex_code;

    if ($rule === null) {
        throw new Exception('Rule value not provided', 404);
    }
    if (255 < $rule || $rule < 0) {
        throw new Exception('Rule out of bounds', 400);
    }

    if ($size === null) {
        throw new Exception('Size value not provided', 404);
    }
    if (DIMENSIONS_MAX < ($size * $scale) || $size < 2) {
        throw new Exception('Size out of bounds', 400);
    }

    if ($generations === null) {
        throw new Exception('Generations value not provided', 404);
    }
    if (DIMENSIONS_MAX < ($generations * $scale) || $generations < 1) {
        throw new Exception('Generations out of bounds', 400);
    }

    if ($filetype === null || $filetype_changes > 1) {
        throw new Exception('Path invalid', 400);
    }
    if (!in_array($filetype, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
        throw new Exception('Filetype invalid', 400);
    }

    if (!in_array($start, ['single', 'random'])) {
        throw new Exception('Seed invalid', 400);
    }

    if ($scale < 1) {
        throw new Exception('Scale out of bounds', 400);
    }

    if (!preg_match('/^[A-Fa-f0-9]{6}$/', $alive_hex_code)) {
        throw new Exception('Color code for living cells invalid', 400);
    }

    if (!preg_match('/^[A-Fa-f0-9]{6}$/', $alive_interpolate_hex_code)) {
        throw new Exception('Color code for living cells interpolation invalid', 400);
    }

    if (!preg_match('/^[A-Fa-f0-9]{6}$/', $dead_hex_code)) {
        throw new Exception('Color code for dead cells invalid', 400);
    }

    if (!preg_match('/^[A-Fa-f0-9]{6}$/', $dead_interpolate_hex_code)) {
        throw new Exception('Color code for dead cells interpolation invalid', 400);
    }

    $automaton = new Automaton($rule, $size, $start);
    $automaton->evolve($generations);

    $image = new Image(
        $size,
        $generations,
        $scale,
        $alive_hex_code,
        $alive_interpolate_hex_code,
        $dead_hex_code,
        $dead_interpolate_hex_code
    );
    $image->render($automaton, $filetype);
} catch (Exception $exception) {
    http_response_code($exception->getCode());
    header('Content-Type: application/json');
    echo json_encode(['error' => $exception->getMessage()]);
}

