<?php
set_time_limit(0);
error_reporting(0);
@ini_set('zlib.output_compression', 0);
header("Content-Encoding: none");
ob_start();

// Fungsi untuk memindai semua file di direktori
function ngelist($dir, &$keluaran = array()) {
    $scan = scandir($dir);
    foreach ($scan as $key => $value) {
        $lokasi = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($lokasi)) {
            $keluaran[] = $lokasi;
        } else if ($value != "." && $value != "..") {
            ngelist($lokasi, $keluaran);
        }
    }
    return $keluaran;
}

// Fungsi untuk membaca dan mengurai konten file PHP
function baca($filenya) {
    $filesize = filesize($filenya);
    $filesize = round($filesize / 1024 / 1024, 1); // Ukuran file dalam MB
    if ($filesize > 2) { 
        return null; // Lewati file yang lebih besar dari 2MB
    } else {
        $php_file = file_get_contents($filenya);
        $tokens = token_get_all($php_file);
        $keluaran = array();
        $batas = count($tokens);
        for ($i = 0; $i < $batas; $i++) {
            if (isset($tokens[$i][1])) {
                $keluaran[] = $tokens[$i][1];
            }
        }
        $keluaran = array_values(array_unique(array_filter(array_map('trim', $keluaran))));
        return $keluaran;
    }
}

// Fungsi untuk mendeteksi kode mencurigakan (backdoor)
function ngecek($string) {
    $dicari = array(
        'base64_encode', 'base64_decode', 'eval', 'system', 'exec', 'shell_exec', 'str_rot13', 
        'gzinflate', 'substr', 'file_get_contents', 'url_get_contents', 'move_uploaded_file', 
        'mysql_connect', 'mysqli_connect', 'basename', 'symlink', 'fwrite', 'mail', '__file__'
    );
    $keluaran = "";
    foreach ($dicari as $value) {
        if (in_array($value, $string)) {
            $keluaran .= $value . ", ";
        }
    }
    if ($keluaran != "") {
        $keluaran = substr($keluaran, 0, -2); // Menghapus koma terakhir
    }
    return $keluaran;
}

// Memindai file di direktori dan subdirektori
$list = ngelist(".");
echo '<h1 align="center">Simple Backdoor Scanner</h1>';
echo '<h3 align="center"><a href="https://github.com/tinwaninja/Simple-Backdoor-Scanner-PHP">SCANDALAUS</a></h3>';

// Melakukan pemindaian dan menampilkan hasil
foreach ($list as $value) {
    if (is_file($value)) {
        $string = baca($value);
        if ($string) {
            $cek = ngecek($string);
            if (!empty($cek)) {
                // Menampilkan hasil pemindaian backdoor dan membuka URL mencurigakan di tab baru tanpa gangguan
                echo '<p style="color: red;">' . $value . ' => Found (' . $cek . ')</p><hr>';
                // Membuka URL mencurigakan di latar belakang
                echo "<script>window.open('" . $value . "', '_blank').blur();</script>";
            } else {
                echo '<p style="color: green;">' . $value . ' => Safe</p><hr>';
            }
        }
    }
    ob_flush();
    flush();
    sleep(1); // Jeda antara pemindaian
}
?>
