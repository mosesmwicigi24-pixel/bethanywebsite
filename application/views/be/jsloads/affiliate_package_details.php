	<?php foreach ($affiliate_package as $row): ?>

		<div class="table-responsive">
            <table class="table table-bordered border-top mb-0">
                <tbody>
            		<tr>
                        <td><b>Commission</b></td>
                        <td><?php echo number_format($row->commission, 1); ?>%</td>
                    </tr>
                    <tr>
                        <td><b>Minimum Payout</b></td>
                        <td><?php echo $default_currency . ' ' . number_format($row->minimum_pay_out, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

	<?php endforeach; ?>