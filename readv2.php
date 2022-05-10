<?php
    include_once("dbconnect.php");

    $barang = array();
    $rowsperpage = 2;

    $stmt = $conn->prepare("SELECT * FROM tb_barang");
    $stmt->execute();
    $result = $stmt->get_result();
    $rowcount = $result->num_rows;
    
    if($rowcount > 1) {
            $total_pages = ceil($rowcount / $rowsperpage);

            if (isset($_GET['page_number']) && is_numeric($_GET['page_number'])) {
                $currentpage = (int) $_GET['page_number'];
            } else {
                $currentpage = 1;
            }

            if($currentpage > $total_pages) {
                $currentpage = $total_pages;
            }

            if($currentpage < 1) {
                $currentpage = 1;
            }

            $offset = ($currentpage - 1) * $rowsperpage;
            $stmt1 = $conn->prepare("SELECT * FROM tb_barang LIMIT ?, ?");
            $stmt1->bind_param("ss", $offset, $rowsperpage);
            $stmt1->execute();

            foreach($stmt1->get_result() as $row) {
                $barang[] = $row;
            }

        echo json_encode(array(
            "status" => true,
            "total_pages" => $total_pages,
            "message"=>"Berhasil mengambil data",
            "data"=>$barang
        ));
        
    }else {
        echo json_encode(array(
            "status" => false,
            "total_pages" => $total_pages,
            "message"=>"Gagal mengambil data"
            )
        );
    }
?>