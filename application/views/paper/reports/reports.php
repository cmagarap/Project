<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <div align = "left">
                            <h3 class="title"><b>Daily Sales</b></h3></br>
                        </div>
                    </div>
                    <?php
                    if (!$daily) {
                        echo "<center><h3><hr><br>There are no reports recorded today.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <th><b>Date</b></th>
                            <th><b>Items sold</b></th>
                            <th><b>Income</b></th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php $total_items = 0;
                            foreach ($daily as $daily): ?>
                                <tr>
                                    <td><?= date("F j, Y", $daily->sales_date) ?>
                                    </td>
                                    <td></td>
                                    <td align="right">&#8369; <?= number_format($daily->income, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                            <td><h3>Total</h3></td>
                            <td><?= $total_items ?></td>
                            <td align="right"><h3>&#8369; <?= number_format($dailytotal, 2) ?></h3></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <div align = "left">
                            <h3 class="title"><b>Weekly Sales</b></h3></br>
                        </div>
                    </div>
                    <?php
                    if (!$weekly) {
                        echo "<center><h3><hr><br>There are no reports recorded this week.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <th><b>Date</b></th>
                            <th><b>Items Sold</b></th>
                            <th><b>Income</b></th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php $total_items = 0;
                            foreach ($weekly as $weekly): ?>
                                <tr>
                                    <td><?= date("m-j-Y", $weekly->sales_date) ?></td>
                                    <td></td>
                                    <td align="right">&#8369;<?= number_format($weekly->income, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><h3>Total</h3></td>
                                <td><?= $total_items ?></td>
                                <td align="right"><h3>&#8369; <?= number_format($weeklytotal, 2) ?></h3></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <div align = "left">
                            <h3 class="title"><b>Monthly Sales</b></h3></br>
                        </div>
                    </div>

                    <?php
                    if (!$monthly) {
                        echo "<center><h3><hr><br>There are no reports recorded this month.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <th><b>Month</b></th>
                            <th><b>Income</b></th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php foreach ($monthly as $monthly): ?>
                                <tr>
                                    <td><?= $monthly->sales_month ?>
                                    </td>
                                    <td align="right">&#8369;<?= number_format($monthly->income, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><h3>Total</h3></td>
                                <td align="right"><h3>&#8369;<?= number_format($monthlytotal, 2) ?></h3></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <div align = "left">
                            <h3 class="title"><b>Annual Sales</b></h3></br>
                        </div>
                    </div>
                    <?php
                    if (!$annual) {
                        echo "<center><h3><hr><br>There are no reports recorded in this year.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <th><b>Year</b></th>
                            <th><b>Income</b></th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php foreach ($annual as $annual): ?>
                                <tr>
                                    <td><?= $annual->sales_y ?>
                                    </td>
                                    <td align="right">&#8369;<?= number_format($annual->income, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><h3>Total</h3></td>
                                <td align="right"><h3>&#8369;<?= number_format($annualtotal, 2) ?></h3></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <div align = "left">
                            <h3 class="title"><b>Inventory Report</b></h3></br>
                        </div>
                    </div>

                    <?php
                    if (!$annual) {
                        echo "<center><h3><hr><br>There are no products recorded in the database.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                            <th><b title = "Product ID">#</b></th>
                            <th><b>Asset</b></th>
                            <th><b>Quantity</b></th>
                            <th><b>Value</b></th>
                            <th><b title = "Exact Value">Ext. Value</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_price = 0;
                            $total_items = 0;
                            foreach ($inventory as $product): ?>
                                <tr>
                                    <td><?= $product->product_id ?>
                                    </td>
                                    <td><?= $product->product_name ?></td>
                                    <td align="right"><?= $product->product_quantity ?></td>
                                    <td align="right">&#8369; <?= number_format($product->product_price, 2) ?></td>
                                    <td align="right">&#8369; <?= number_format($product->product_price * $product->product_quantity, 2) ?></td>
                                    <?php $total_price += $product->product_price * $product->product_quantity;
                                    $total_items += $product->product_quantity; ?>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><h3>Total Inventory Value</h3></td>
                                <td></td>
                                <td><h3 align="right"><?= $total_items ?></h3></td>
                                <td align="right">-</td>
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