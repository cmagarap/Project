<?php
foreach ($feedback as $feed) {
    $min_rating_array[] = $feed->min_rating;
    $max_rating_array[] = $feed->max_rating;
    $latest_feedback[] = $feed->max_date;
}
$min_rating_unique = array_unique($min_rating_array);
$max_rating_unique = array_unique($max_rating_array);
sort($min_rating_unique);
sort($max_rating_unique);
rsort($latest_feedback);

foreach ($latest_feedback as $feed) {
    $formatted[] = date('M d, Y', $feed);
}
$latest_feedback_unique = array_unique($formatted);
?>

<script>
    function populate(s1, s2){
        var s1 = document.getElementById(s1);
        var s2 = document.getElementById(s2);
        s2.innerHTML = "";
        if(s1.value == "all"){
            var optionArray = ["-|—"];
        } else if(s1.value == "min_rating"){
            var optionArray = ["-|—", <?php foreach ($min_rating_unique as $min) echo '"' . $min. '|' . $min . '",'?>];
        } else if(s1.value == "max_rating"){
            var optionArray = ["-|—", <?php foreach ($max_rating_unique as $max) echo '"' . $max. '|' . $max . '",'?>];
        } else if(s1.value == "max_date") {
            var optionArray = ["-|—", <?php foreach ($latest_feedback_unique as $feed) echo '"' . $feed . '|' . $feed . '",'?>];
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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <div class="col-sm-3">
                            <h3 class="title"><span class="ti-comment" style="color: #dc2f54;2"></span>&nbsp; <b>Feedback Report</b></h3>
                            <p class="category">
                                <i class="ti-reload" style = "font-size: 12px;"></i> As of <?= date("F j, Y h:i A"); ?>
                            </p>
                        </div>
                        <form role="form" method="post">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Filter by:</label>
                                    <select name="filter_feedback" id="filter_feedback" class="form-control border-input file" onchange="populate(this.id, 'select_f')">
                                        <option value="all">All</option>
                                        <option value="min_rating">Minimum Rating Given</option>
                                        <option value="max_rating">Maximum Rating Given</option>
                                        <option value="max_date">Latest Feedback Date</option>
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
                                        <option value="customer">Customer</option>
                                        <option value="">Minimum Rating Given</option>
                                        <option value="added_at">Maximum Rating Given</option>
                                        <option value="product_quantity">Average Rating Given</option>
                                        <option value="product_price">Latest Feedback Date</option>
                                        <option value="">Total Feedback</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label style="color: white;">`</label>
                                    <br>
                                    <button type="submit" class="btn btn-info btn-fill" style="background-color: #31bbe0; border-color: #31bbe0; color: white; width: 55px" name="filter" title="Filter"><i class="ti-filter"></i></button>
                                    <form target="_blank" method="post">
                                        <a href = "javascript:history.go(-1)" class="btn btn-info btn-fill" style = "background-color: #dc2f54; border-color: #dc2f54; color: white;"><i class="ti-arrow-left"></i></a>
                                        <input type="submit" name="generate_pdf" class="btn btn-info btn-fill" style="background-color: #F3BB45; border-color: #F3BB45; color: white;" value="Generate PDF" />
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br><br><br><br>
                    <hr style="margin-bottom: -10px">
                    <?php
                    if (!$feedback) {
                        # echo $html_tags[0] . $if_none . $html_tags[0];
                        echo "NO FEEDBACK";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <td colspan="2"><b><p>Customer</p></b></td>
                                <td align="right"><b><p>Minimum Rating Given</p></b></td>
                                <td align="right"><b><p>Maximum Rating Given</p></b></td>
                                <td align="right"><b><p>Average Rating Given</p></b></td>
                                <td><b><p>Latest Feedback Date</p></b></td>
                                <td align="right"><b><p>Total Feedback</p></b></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_feedback = 0;
                            $avg_feedback = 0;
                            foreach ($feedback as $feed): ?>
                                <tr>
                                    <?php
                                    $user_image = (string)$feed->image;
                                    $image_array = explode(".", $user_image);
                                    ?>
                                    <td>
                                        <p><img src="<?= $this->config->base_url() ?>uploads_users/<?= $image_array[0] . "_thumb." . $image_array[1]; ?>" class="img-responsive img-circle" alt="<?= $feed->username ?>" title="<?= $feed->username ?>"></p>
                                    </td>
                                    <td>
                                        <a href="<?= base_url() ?>accounts/view/<?= $feed->customer_id ?>" style="text-decoration: underline"><?= $feed->username ?></a>
                                    </td>
                                    <td align="right"><i class="ti-star" style="color: #F3BB45"></i> <?= $feed->min_rating ?></td>
                                    <td align="right"><i class="ti-star" style="color: #F3BB45"></i> <?= $feed->max_rating ?></td>
                                    <td align="right"><i class="ti-star" style="color: #F3BB45"></i> <?= $feed->average ?></td>
                                    <td><?= date('M-d-Y h:i A', $feed->max_date) ?></td>
                                    <td align="right"><?= $feed->total_feedback ?></td>
                                    <?php $total_feedback += $feed->total_feedback;
                                    $avg_feedback += $feed->average; ?>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>-</b></td>
                                <td><h3 align="right"><span class="ti-star" style="color: #F3BB45"></span> <?= number_format($avg_feedback / count($feedback), 2) ?></h3></td>
                                <td><b>-</b></td>
                                <td align="right"><h3><?= $total_feedback ?></h3></td>
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
