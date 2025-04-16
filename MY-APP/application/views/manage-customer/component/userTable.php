<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
        
            <th scope="text-center">STT</th>
            <th scope="text-center">Avatar</th>
            <th scope="text-center">Username</th>
            <th scope="text-center">Email</th>
            <th scope="text-center">Phone</th>
            <th scope="text-center">Address</th>
            <th scope="text-center">Vai trò</th>
            <th scope="text-center">Status</th>
            <th scope="text-center">Manage</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($customers as $key => $cus): ?>
            <tr>
                
                <th scope="row"><?php echo ($start + $key + 1); ?></th>
                <td><img src="<?php echo base_url('uploads/user/' . $cus->Avatar) ?>" alt="" class="img-thumbnail"
                        width="70" height="70"></td>
                <td><?php echo $cus->Name ?></td>
                <td><?php echo $cus->Email ?></td>
                <td><?php echo $cus->Phone ?></td>
                <td class="width300"><?php echo $cus->Address ?></td>
                <td><?php echo $cus->Role_name ?></td>
                <td>
                    <?php
                    if ($cus->Status == 1) {
                        echo "<span class='badge bg-success'>Bình thường</span>";
                    } else {
                        echo "<span class='badge bg-danger'>Người dùng bị khóa</span>";
                    }
                    ?>
                </td>
                <td style="width: 100px" class="text-center">
                    <a href="<?php echo base_url('manage-customer/list/edit/' . $cus->UserID) ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <!-- <a onclick="return confirm('Bạn chắc chắn muốn xóa khách hàng này chứ?')"
                        href="<?php echo base_url('manage-customer/delete/' . $cus->UserID) ?>" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a> -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>
<div class="text-center">
    <?php echo $links; ?>
</div>