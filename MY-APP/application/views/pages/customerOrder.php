<div class="container">
    <div class="card">
        <div class="card-header">Danh sách đơn đặt hàng</div>
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
            <?php } elseif($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
            <?php } ?>
        <div class="card-body">
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Order code</th>
                    <th scope="col">Customer name</th>
                    <th scope="col">Customer phone</th>
                    <th scope="col">Customer address</th>
                    <th scope="col">Form of cash payment</th>
                    <th scope="col">Status</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($order as $key => $ord){
                ?>
                <tr>
                    <th scope="row"><?php echo $key ?></th>
                    <td><?php echo $ord->order_code?></td>
                    <td><?php echo $ord->name?></td>
                    <td><?php echo $ord->phone?></td>
                    <td><?php echo $ord->address?></td>
                    <td><?php echo $ord->form_of_payment?></td>
                    <td>
                        <?php
                        if ($ord->status == 1) {
                            echo '<span class="text text-primary">Đang chờ xử lý</span>';
                        }
                        
                        elseif ($ord->status == 2) {
                            echo '<span class="text text-warning">Đang chuẩn bị hàng</span>';
                        } 
                        elseif ($ord->status == 3) {
                            echo '<span class="text text-success">Đã giao cho đơn vị vận chuyển</span>';
                        }
                        // elseif ($ord->status == 3) {
                        //     echo '<span class="text text-success">Đã giao cho đơn vị vận chuyển</span>';
                        // }
                          else {
                            echo '<span class="text text-danger">Đã hủy</span>';
                        }
                        ?>
                        </td>
                    <td>
                        <a onclick="return confirm('Bạn chắc chắn muốn xóa chứ?')" href="<?php echo base_url('order_admin/deleteOrder/'.$ord->order_code) ?>" class="btn btn-danger">Delete</a>
                        <a href="<?php echo base_url('order_admin/viewOrder/'.$ord->order_code) ?>" class="btn btn-warning">View</a>
                        <a href="<?php echo base_url('order_admin/printOrder/'.$ord->order_code) ?>" class="btn btn-warning">Print Order</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
        </div>
    </div>
</div>