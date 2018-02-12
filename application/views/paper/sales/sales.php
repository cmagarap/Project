<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="header">
                        <h3><span class="ti-stats-up" style = "color: #dc2f54;"></span>&nbsp;<b> Daily Sales Chart</b></h3>
                        <hr>
                    </div>
                    <div class="content">
                        <div id="chart-container">
                            <canvas id="dailySales"></canvas>
                        </div>
                        <div class="footer">
                            <hr>
                            <div class="stats">
                                <i class="ti-reload"></i> Updated <?= date("F j, Y h:i A"); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h3><span class="ti-calendar" style = "color: #dc2f54;"></span>&nbsp; <b>Calendar</b></h3>
                        <hr>
                        <div class="calendar"></div>
                            <form action="<?= base_url() . 'sales/page'; ?>" method="POST">
                            </br>
                                <div align="center">
                                <button type="submit" id="submit" class="btn btn-info btn-fill" style="background-color: #31bbe0; border-color: #31bbe0; color: white;">Submit</button>
                                <input type="hidden" id="date" name="date">
                                </div>
                            </form>
                            </br>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="header">
                        <h3><span class="ti-notepad" style = "color: #dc2f54;"></span>&nbsp; <b>List of Sales</b></h3>
                        <p class="category"><?= $date ?></p>
                    </div>
                    <?php if(!$sales) {
                        echo "<center><h3><hr><br>There are no sales recorded for the date you have selected.</h3><br></center><br><br>";
                    } else {
                    ?>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                            <th></th>
                            <th><b>#</b></th>
                            <th><b>Details</b></th>
                            <th><b>Income</b></th>
                            <th><b>Date</b></th>
                            <th><b>Order</b></th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($sales as $sales): ?>
                                <tr>
                                    <td><p style = "font-size: 12px"><span class='ti-money' style = 'font-size: 15px; color: green'></span></p></td>
                                    <td><?= $sales->sales_id ?></td>
                                    <td><i><?= $sales->sales_detail ?></i></td>
                                    <td>&#8369;<?= number_format($sales->income, 2) ?></td>
                                    <td><?= date("m-j-Y", $sales->sales_date) ?>
                                    </td>
                                    <td><u><a href = "<?= $this->config->base_url() ?>orders/view/<?= $sales->order_id ?>"><?= $sales->order_id ?></a></u></td>
                                    <td>
                                        <a class="btn btn-danger delete" href="#" data-id="<?= $sales->sales_id ?>" title = "Delete" alt = "Delete">
                                            <span class="ti-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php echo "<div align = 'center'>" . $links . "</div>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#submit").hide();
    
    $(".delete").click(function () {
        var id = $(this).data('id');

        swal({
            title: "Are you sure you want to delete this?",
            // text: "You will not be able to undo this action once cancelled.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = "<?= $this->config->base_url() ?>sales/delete/" + id;
                } else {
                    // swal("This order is safe!");
                }
            });
    });

    $(function() {
            $('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.version);

            function onClickHandler(date, obj) {
                /**
                 * @date is an array which be included dates(clicked date at first index)
                 * @obj is an object which stored calendar interal data.
                 * @obj.calendar is an element reference.
                 * @obj.storage.activeDates is all toggled data, If you use toggle type calendar.
                 * @obj.storage.events is all events associated to this date
                 */

                var $calendar = obj.calendar;
                $("#submit").show();
                var text = '';

                if(date[0] !== null) {
                    text += date[0].format('YYYY-MM-DD');
                }

                if(date[0] !== null && date[1] !== null) {
                    text += ' ~ ';
                } else if(date[0] === null && date[1] == null) {
                    text += 'nothing';
					$("#submit").hide();
                }

                if(date[1] !== null) {
                    text += date[1].format('YYYY-MM-DD');
                }

				$('#date').val(text);
            }

            // Default Calendar
            $('.calendar').pignoseCalendar({
                select: onClickHandler
            });

            // This use for DEMO page tab component.
        });
</script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dailySales_line.js"></script>