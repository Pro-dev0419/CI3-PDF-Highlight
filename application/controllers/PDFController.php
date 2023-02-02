<?php
use Dompdf\Dompdf;

defined('BASEPATH') or exit('No direct script access allowed');

class PDFController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('user_agent');
        // $this->load->library("pdf");
        $this->load->helper('url');
        $this->load->model('pdf');
        $this->load->model('test');
    }

    public function index()
    {
        echo site_url();
        // echo "<pre>";
        // var_dump($pdfs);
        $this->load->view('home-view', ['pdf_url' => ""]);
    }



    public function upload()
    {
        if (isset($_FILES['pdf-file']) && $_FILES['pdf-file']['size'] > 0 && isset($_FILES['pdf-file']['name']) && $_FILES['pdf-file']['name'] != '') {
            // $file = $_FILES["pdf-file"];
            // $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            // $filename = random_int(1000000000, 10000000000) . "." . $ext;
            // $target_dir = "uploads/";
            // $target_file = $target_dir . basename($filename);
            // if (move_uploaded_file($file["tmp_name"], $target_file)) {
            //     $this->session->set_flashdata('status', 'Uploaded Successfully!');
            // } else {
            //     $this->session->set_flashdata('error', "Sorry, there was an error uploading your file.!");
            //     redirect($_SERVER['HTTP_REFERER']);
            // }

            $apiKey = 'dongbeo99@outlook.com_af796d9f249bcce8f1f340307bb2426d3b6798717edb6e906127b33031135567016b8b50'; // The authentication key (API Key). Get your own by registering at https://app.pdf.co
            $pages = "";
            if (isset($_POST["pages"])) {
                $pages = $_POST["pages"];
            }

            $plainHtml = false;
            if (isset($_POST["plainHtml"])) {
                $plainHtml = $_POST["plainHtml"];
            }

            $columnLayout = false;
            if (isset($_POST["columnLayout"])) {
                $columnLayout = $_POST["columnLayout"];
            }

            // 1. RETRIEVE THE PRESIGNED URL TO UPLOAD THE FILE.
// * If you already have the direct PDF file link, go to the step 3.

            // Create URL
            $url = "https://api.pdf.co/v1/file/upload/get-presigned-url" .
                "?name=" . urlencode($_FILES["pdf-file"]["name"]) .
                "&contenttype=application/octet-stream";

            // Create request
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            // Execute request
            $result = curl_exec($curl);

            if (curl_errno($curl) == 0) {
                $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($status_code == 200) {
                    $json = json_decode($result, true);

                    // Get URL to use for the file upload
                    $uploadFileUrl = $json["presignedUrl"];
                    // Get URL of uploaded file to use with later API calls
                    $uploadedFileUrl = $json["url"];

                    // 2. UPLOAD THE FILE TO CLOUD.

                    $localFile = $_FILES["pdf-file"]["tmp_name"];
                    $fileHandle = fopen($localFile, "r");

                    curl_setopt($curl, CURLOPT_URL, $uploadFileUrl);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array("content-type: application/octet-stream"));
                    curl_setopt($curl, CURLOPT_PUT, true);
                    curl_setopt($curl, CURLOPT_INFILE, $fileHandle);
                    curl_setopt($curl, CURLOPT_INFILESIZE, filesize($localFile));

                    // Execute request
                    curl_exec($curl);

                    fclose($fileHandle);

                    if (curl_errno($curl) == 0) {
                        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                        if ($status_code == 200) {
                            // 3. CONVERT UPLOADED PDF FILE TO HTML

                            $this->PdfToHtml($apiKey, $uploadedFileUrl, $pages, $plainHtml, $columnLayout);
                        } else {
                            // Display request error
                            echo "<p>Status code: " . $status_code . "</p>";
                            echo "<p>" . $result . "</p>";
                        }
                    } else {
                        // Display CURL error
                        echo "Error: " . curl_error($curl);
                    }
                } else {
                    // Display service reported error
                    echo "<p>Status code: " . $status_code . "</p>";
                    echo "<p>" . $result . "</p>";
                }

                curl_close($curl);




                // Note: If you have input files large than 200kb we highly recommend to check "async" mode example.

                // Get submitted form data

            } else {
                $this->session->set_flashdata('error', 'File Required!');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function PdfToHtml($apiKey, $uploadedFileUrl, $pages, $plainHtml, $columnLayout)
    {
        // Create URL
        $url = "https://api.pdf.co/v1/pdf/convert/to/html";

        // Prepare requests params
        $parameters = array();
        $parameters["url"] = $uploadedFileUrl;
        $parameters["pages"] = $pages;

        if ($plainHtml) {
            $parameters["simple"] = $plainHtml;
        }

        if ($columnLayout) {
            $parameters["columns"] = $columnLayout;
        }

        // Create Json payload
        $data = json_encode($parameters);

        // Create request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey, "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Execute request
        $result = curl_exec($curl);

        if (curl_errno($curl) == 0) {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($status_code == 200) {
                $json = json_decode($result, true);

                if (!isset($json["error"]) || $json["error"] == false) {
                    $resultFileUrl = $json["url"];

                    // Display link to the file with conversion results
                    $data2 = [
                        "name" => $_FILES['pdf-file']['name'],
                        "url" => $resultFileUrl
                    ];
                    $pdf = new Pdf;
                    $pdf->insertPdf($data2);
                    // echo "<div><h2>Conversion Result:</h2><a href='" . $resultFileUrl . "' target='_blank'>" . $resultFileUrl . "</a></div>";

                    $this->load->view('home-view', ['pdf_url' => $resultFileUrl]);
                    // redirect($_SERVER['HTTP_REFERER']);


                } else {
                    // Display service reported error
                    echo "<p>Error: " . $json["message"] . "</p>";
                }
            } else {
                // Display request error
                echo "<p>Status code: " . $status_code . "</p>";
                echo "<p>" . $result . "</p>";
            }
        } else {
            // Display CURL error
            echo "Error: " . curl_error($curl);
        }

        // Cleanup
        curl_close($curl);
    }

    public function show($id = false)
    {

        // $PdfModel = new Pdf();
        // $pdf = $PdfModel->find($id);
        $parser = new \Smalot\PdfParser\Parser();
        // $PDFfile = $pdf['name'];
        // $PDF = $parser->parseFile($PDFfile);
        // $PDFContent = $PDF->getText();
        // $data = ['pdf' => $PDFContent, 'pdf_id' => $id];

        // print_r(json_encode($data));
        // return view('pdf-view', ['pdf' => $PDFContent, 'pdf_id' => $id]);
    }
    public function tests($id = false)
    {
        $Test = new Test();
        $data = $Test->findAll($id);
        $data = json_encode($data);
        print_r($data);
    }
    public function save()
    {
        $data = $_POST;
        $html = $data['elements'];
        $filename = "newpdffile";
        // $this->load->library('Pdf');
        $dompdf = new Dompdf();
        // var_dump($data['elements']);
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // $dompdf->output()
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        // $dompdf->stream("mypdf.pdf", array("Attachment" => 0));

        $file_to_save = 'mypdf.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $dompdf->output());
        //print the pdf file to the screen for saving
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="mypdf.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file_to_save));
        header('Accept-Ranges: bytes');
        readfile($file_to_save);
        // exit(0);
        // $data = $_POST;
        // $Test = new Test();
        // $Test->deleteItem($data['pdf_id']);
        // $key_values = array_column($data['elements'], 'start');
        // array_multisort($key_values, SORT_ASC, $data['elements']);
        // foreach ($data['elements'] as $item) {
        //     $Test = new Test();
        //     $Test->insertData([
        //         'pdf_id' => $data['pdf_id'],
        //         'start_offset' => $item['start'],
        //         'end_offset' => $item['end'],
        //     ]);
        // }
        echo 'success';
    }
}