<?php
    require 'dompdf/vendor/autoload.php'; // jika menggunakan Composer
    // require 'path/to/dompdf/autoload.inc.php'; // jika menginstal secara manual
    
    use Dompdf\Dompdf;
    use Dompdf\Options;
    
    // Mengatur opsi dompdf
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $options->set('isRemoteEnabled', true);
    
    // Membuat instance dompdf baru
    $dompdf = new Dompdf($options);
    
    // Mengambil HTML yang ingin dikonversi
    $html = file_get_contents('Form Ijin Kerja.html');
    
    // Memuat konten HTML ke dompdf
    $dompdf->loadHtml($html);
    
    // Mengatur ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'portrait'); // 'landscape' untuk orientasi lanskap
    
    // Merender HTML menjadi PDF
    $dompdf->render();
    
    // Menghasilkan file PDF
    $output = $dompdf->output();
    
    // Menyimpan file PDF ke server
    file_put_contents('output.pdf', $output);
    
    // Untuk mengunduh file PDF langsung ke browser
    $dompdf->stream("output.pdf", array("Attachment" => 1));
?>