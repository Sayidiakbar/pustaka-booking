<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        cek_user();
    }

    public function laporan_buku()
    {
        $data['judul'] = 'Laporan Data Buku';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('buku/laporan_buku', $data);
        $this->load->view('templates/footer');
    }
    public function cetak_laporan_buku()
    {
        $data['buku'] = $this ->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this ->ModelBuku->getKategori()->result_array();

        $this -> load->view('buku/laporan_print_buku',$data);
    }
    public function laporan_buku_pdf()
    {
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        // $this->load->library('dompdf_gen');
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php";
        
        $dompdf = new Dompdf\Dompdf();
        $this->load->view('buku/laporan_pdf_buku', $data);
        
        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; // tipe format kertas potrait atau landscape
        
        $html = $this->output->get_output();
        $dompdf->set_paper($paper_size, $orientation);
        
        // Convert to PDF
        $dompdf->load_html($html);
        $dompdf->render();
        
        $dompdf->stream("laporan_data_buku.pdf", array('Attachment' => 0));
        // nama file pdf yang dihasilkan
    }
    public function export_excel()
    {
        $data = array ('title' => 'Laporan Buku', 'buku'=>$this ->ModelBuku->getBuku()->result_array());
        $this ->load->view('buku/export_excel_buku',$data);
    }

    public function laporan_anggota()
    {
        $data['judul'] = 'Laporan Data Anggota';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $this->db->where('role_id', 2);
        $data['anggota'] = $this->db->get('user')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/laporan_anggota', $data);
        $this->load->view('templates/footer');
    }
    public function cetak_laporan_anggota()
    {
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $this->db->where('role_id', 2);
        $data['anggota'] = $this->db->get('user')->result_array();

        $this -> load->view('user/laporan_print_anggota',$data);
    }
    public function laporan_pdf_anggota()
    {   $this->db->where('role_id = 2');
        $data['anggota'] = $this->db->get('user')->result_array();
        //$this->load->library('dompdf_gen');
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php";
        
        $dompdf = new Dompdf\Dompdf();
        $this->load->view('user/laporan_pdf_anggota', $data);
        
        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; // tipe format kertas potrait atau landscape
        
        $html = $this->output->get_output();
        $dompdf->set_paper($paper_size, $orientation);
        
        // Convert to PDF
        $dompdf->load_html($html);
        $dompdf->render();
        
        $dompdf->stream("laporan_data_anggota.pdf", array('Attachment' => 0));
        // nama file pdf yang dihasilkan
    }
    public function export_excel_anggota() 
    {
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data = array( 'title' => 'Laporan Data anggota Buku', 
                       'laporan' => $this->db->query("SELECT * FROM user Where role_id = 2")->result_array()); 
        $this->load->view('user/export-excel-anggota', $data); 
    }
    public function laporan_pinjam() 
{ 
    $data['judul'] = 'Laporan Data Peminjaman'; 
    $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array(); 
    $data['laporan'] = $this->db->query("
        SELECT * 
        FROM pinjam p
        JOIN detail_pinjam d ON p.no_pinjam = d.no_pinjam
        JOIN buku b ON d.id_buku = b.id
        JOIN user u ON p.id_user = u.id")->result_array(); 
 
    $this->load->view('templates/header', $data); 
    $this->load->view('templates/sidebar'); 
    $this->load->view('templates/topbar', $data); 
    $this->load->view('pinjam/laporan-pinjam', $data); 
    $this->load->view('templates/footer'); 
}
    public function cetak_laporan_pinjam() 
{ 
    $data['laporan'] = $this->db->query("select * from pinjam p,detail_pinjam d, buku b,user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->result_array(); 
    $this->load->view('pinjam/laporan-print-pinjam', $data); 
} 
public function laporan_pinjam_pdf() 
{ 
    $data['laporan'] = $this->db->query("select * from pinjam p,detail_pinjam d, 
    buku b,user u where d.id_buku=b.id and p.id_user=u.id 
    and p.no_pinjam=d.no_pinjam")->result_array(); 
    // $this->load->library('dompdf_gen'); 
    $sroot      = $_SERVER['DOCUMENT_ROOT']; 
    include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php"; 
    $dompdf = new Dompdf\Dompdf(); 
    $this->load->view('pinjam/laporan-pdf-pinjam', $data); 
    $paper_size  = 'A4'; // ukuran kertas 
    $orientation = 'landscape'; //tipe format kertas potrait atau landscape 
    $html = $this->output->get_output(); 

    $dompdf->set_paper($paper_size, $orientation); 
    //Convert to PDF 
    $dompdf->load_html($html); 
    $dompdf->render(); 
    $dompdf->stream("laporan data peminjaman.pdf", array('Attachment' => 0)); 
    // nama file pdf yang di hasilkan 
} 
public function export_excel_pinjam() 
{ 
  $data = array( 'title' => 'Laporan Data Peminjaman Buku', 
  'laporan' => $this->db->query("select * from pinjam p,detail_pinjam d, 
  buku b,user u where d.id_buku=b.id and p.id_user=u.id 
  and p.no_pinjam=d.no_pinjam")->result_array()); 
  $this->load->view('pinjam/export-excel-pinjam', $data); 
}  

}