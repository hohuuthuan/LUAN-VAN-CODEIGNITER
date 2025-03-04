<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">#</th>
            <th scope="text-center">Avatar</th>
            <th scope="text-center">Username</th>
            <th scope="text-center">Email</th>
            <th scope="text-center">Phone</th>
            <th scope="text-center">Address</th>
            <th scope="text-center">Status</th>
            <th scope="text-center">Manage</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($customers as $key => $cus): ?>
            <tr>
                <td>
                    <input type="checkbox" value="" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>
                <td><img src="<?php echo base_url('uploads/user/' . $cus->avatar) ?>" alt="" class="img-thumbnail"
                        width="70" height="70"></td>
                <td><?php echo $cus->username ?></td>
                <td><?php echo $cus->email ?></td>
                <td><?php echo $cus->phone ?></td>
                <td><?php echo $cus->address ?></td>

                <td>
                    <?php
                    if ($cus->status == 1) {
                        echo "<span class='badge bg-success'>Bình thường</span>";
                    } else {
                        echo "<span class='badge bg-danger'>Người dùng bị khóa</span>";
                    }
                    ?>
                </td>
                <td style="width: 100px" class="text-center">
                    <a href="<?php echo base_url('manage-customer/edit/' . $cus->id) ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <a onclick="return confirm('Bạn chắc chắn muốn xóa khách hàng này chứ?')"
                        href="<?php echo base_url('manage-customer/delete/' . $cus->id) ?>" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>