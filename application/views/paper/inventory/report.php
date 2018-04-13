<?php
$total_price = 0;
$total_items = 0;
$image = $this->item_model->fetch("content",  array("content_id" => 1))[0];
if(isset($_POST["generate_pdf"]))  
{
    $space = str_repeat(" ", 65);
    $temp = unserialize($_POST["pdf"]);  
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle("Inventory Reports");  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('Deja Vu Sans Mono');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('Deja Vu Sans Mono', '', 8);  
    $pdf->AddPage();  
    $content = '';  
    $content .= '
    '.$pdf->Image($this->config->base_url().'assets/ordering/img/'.$image->company_logo, 54, 8, 0,15, '','','',true,300,'C').$pdf->Cell(0, 0, 'Grass Residences,Unit 1717-B'.$space.date("F j, Y"), 0, 1, 'L', 0, '', 0).$pdf->Cell(0, 0, 'Tower 1 SMDC The,Nueva', 0, 1, 'L', 0, '', 0).$pdf->Cell(0, 0, 'Viscaya, Bago Bantay, Quezon', 0, 1, 'L', 0, '', 0).$pdf->Cell(0, 0, 'City, Metro Manila Philippines', 0, 1, 'L', 0, '', 0).'
    <br><br>
    <table border="1" cellspacing="0" cellpadding="3">
    <tr>
    <th colspan="7"><h2 align="center">Inventory Report</h2></th>
    </tr> 
    <tr>
    <th width="8%"><b>Product ID</b></th>
    <th width="20%"><b>Asset</b></th>
    <th width="14%"><b>Brand</b></th>
    <th width="14%"><b>Date Acquired</b></th>
    <th width="8%"><b>Stock</b></th>
    <th width="17%"><b>Value</b></th>
    <th width="19%"><b>Exact Value</b></th>
    </tr>
    ';  
    foreach($temp as $product)
    {       
        $content .='
        <tr>  
        <td>'.$product->product_id.'</td>
        <td>'.$product->product_name.'</td>
        <td>'.ucwords($product->product_brand).'</td>
        <td>'.date('M. j, Y', $product->added_at).'</td>
        <td align="right">'.$product->product_quantity.'</td>
        <td align="right">&#8369;'.number_format($product->product_price, 2).'</td>
        <td align="right">&#8369;'.number_format($product->product_price * $product->product_quantity, 2).'</td>
        </tr>  
        ';  
        $total_price += $product->product_price * $product->product_quantity;
        $total_items += $product->product_quantity;
    }  

    $content .='                                
    <tr>
    <td></td>
    <td><h3>Total Inventory Value</h3></td>
    <td></td>
    <td></td>
    <td><h3 align="right">'.$total_items.'</h3></td>
    <td align="right"><b>-</b></td>
    <td align="right"><h3>&#8369;'.number_format($total_price, 2).'</h3></td>
    </tr>'; 

    $content .= '</table>';
    $pdf->writeHTML($content);  
    $pdf->Output('Inventory_Report.pdf', 'I');  
    exit;
}  
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <div class="col-sm-4">
                            <h3 class="title"><span class="ti-package" style="color: #dc2f54;2"></span>&nbsp; <b>Inventory Report</b></h3>
                            <p class="category">
                                <i class="ti-reload" style = "font-size: 12px;"></i> As of <?= date("F j, Y h:i A"); ?>
                            </p><br>
                            <form target="_blank" role="form" method="post">
                                <input type="submit" name="generate_pdf" class="btn btn-info btn-fill" style="background-color: #F3BB45; border-color: #F3BB45; color: white;" value="Generate PDF" />
                                <input type="hidden" name="pdf" value="<?php echo htmlspecialchars(serialize($inventory)) ?>">
                            </form>
                            <br>
                        </div>
                        <form role="form" method="post">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Filter by:</label>
                                    <select name="filter_inventory" id="filter_inventory" class="form-control border-input file" onchange="populate(this.id, 'select_f')">
                                        <option value="all">All</option>
                                        <option value="product_brand">Brand</option>
                                        <option value="added_at">Date Acquired</option>
                                        <option value="product_price">Price Range</option>
                                        <option value="product_quantity">Stock Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Select:</label>
                                    <select name="select_f" id="select_f" class="form-control border-input file">
                                        <option value="">—</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Sort by:</label>
                                    <select name="sort_inventory" class="form-control border-input file">
                                        <option value="product_name">Product Name</option>
                                        <option value="product_brand">Brand</option>
                                        <option value="added_at">Date Acquired</option>
                                        <option value="product_quantity">Stock</option>
                                        <option value="product_price">Price</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label style="color: white;">`</label>
                                    <br>
                                    <button type="submit" class="btn btn-info btn-fill" style="background-color: #31bbe0; border-color: #31bbe0; color: white; width: 55px" name="filter" title="Filter"><i class="ti-filter"></i></button>
                                    <a href = "javascript:history.go(-1)" class="btn btn-info btn-fill" style = "background-color: #dc2f54; border-color: #dc2f54; color: white;"><i class="ti-arrow-left"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <br><br><br><br><br>
                    <?php
                    if (!$inventory) {
                        echo $html_tags[0] . $if_none . $html_tags[0];
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td><p><b>Product ID</b></p></td>
                                    <td><p><b>Asset <?php if($sorted_by == 'product_name') echo '<sup><i class="ti-angle-double-up" style="font-size: 10px; color: #dc2f54;"></i></sup>'; ?></b></p></td>
                                    <td><p><b>Brand <?php if($sorted_by == 'product_brand') echo '<sup><i class="ti-angle-double-up" style="font-size: 10px; color: #dc2f54;"></i></sup>'; ?></b></p></td>
                                    <td><p><b>Date Acquired <?php if($sorted_by == 'added_at') echo '<sup><i class="ti-angle-double-up" style="font-size: 10px; color: #dc2f54;"></i></sup>'; ?></b></p></td>
                                    <td align="right"><p><b>Stock <?php if($sorted_by == 'product_quantity') echo '<sup><i class="ti-angle-double-up" style="font-size: 10px; color: #dc2f54;"></i></sup>'; ?></b></p></td>
                                    <td align="right"><p><b>Value <?php if($sorted_by == 'product_price') echo '<sup><i class="ti-angle-double-up" style="font-size: 10px; color: #dc2f54;"></i></sup>'; ?></b></p></td>
                                    <td align="right"><p><b>Exact Value</b></p></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_price = 0;
                                $total_items = 0;
                                foreach ($inventory as $product): ?>
                                <tr>
                                    <td><u><a href="<?= base_url() ?>inventory/view/<?= $product->product_id ?>"><?= $product->product_id ?></a></u>
                                    </td>
                                    <td><?= $product->product_name ?></td>
                                    <td><?= ucwords($product->product_brand) ?></td>
                                    <td><?= date('M. j, Y', $product->added_at)?></td>
                                    <td align="right"><?= $product->product_quantity ?></td>
                                    <td align="right">&#8369; <?= number_format($product->product_price, 2) ?></td>
                                    <td align="right">&#8369; <?= number_format($product->product_price * $product->product_quantity, 2) ?></td>
                                    <?php $total_price += $product->product_price * $product->product_quantity;
                                    $total_items += $product->product_quantity; ?>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td><h3>Total Inventory Value</h3></td>
                                <td></td>
                                <td></td>
                                <td><h3 align="right"><?= $total_items ?></h3></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><h3>&#8369; <?= number_format($total_price, 2) ?></h3></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function populate(s1, s2){
        var s1 = document.getElementById(s1);
        var s2 = document.getElementById(s2);
        s2.innerHTML = "";
        if(s1.value == "all"){
            var optionArray = ["-|—"];
        } else if(s1.value == "product_brand"){
            var optionArray = ["-|—", <?php foreach ($dropdown_brand as $dp_brand) echo '"' . $dp_brand->product_brand . '|' . ucwords($dp_brand->product_brand) . '",'?>];
        } else if(s1.value == "added_at"){
            var optionArray = ["-|—", <?php foreach ($dropdown_date as $dp_date) echo '"' . $dp_date->date_acq . '|' . $dp_date->date_acq . '",'?>];
        } else if(s1.value == "product_price") {
            var optionArray = ["-|—", "0-99|0 - 99", "100-499|100 - 499", "500-999|500 - 999", "1000-4999|1,000 - 4,999", "5000-9999|5,000 - 9,999", "10000-19999|10,000 - 19,999", "20000-49999|20,000 - 49,999", "50000-500000|50,000 and above"];
        } else if(s1.value == "product_quantity") {
            var optionArray = ["-|—", "0-9|0 - 9", "10-19|10 - 19", "20-49|20 - 49", "50-99|50 - 99", "100-199|100 - 199", "200-499|200 - 499", "500-999|500 - 999", "1000-500000|1,000 and above"];
        }

        for(var option in optionArray){
            var pair = optionArray[option].split("|");
            var newOption = document.createElement("option");
            newOption.value = pair[0];
            newOption.innerHTML = pair[1];
            s2.options.add(newOption);
        }
    }
</script>
