<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Danh sách khách hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Status</th>
                        <th scope="col">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($customers as $key => $cus){
                    ?>
                    <tr>
                        <th scope="row"><?php echo $key ?></th>
                        <td><?php echo $cus->Name?></td>
                        <td><?php echo $cus->Email?></td>
                        <td><?php echo $cus->Phone?></td>
                        <td><?php echo $cus->Address?></td>
                        <td><img src="<?php echo base_url('uploads/user/'.$cus->Avatar) ?>" alt="" class="img-thumbnail" width="100" height="100"></td>
                        <td>
                            <?php 
                                if($cus->Status == 1){
                                    echo "<span class='badge bg-success'>Bình thường</span>";
                                }else{
                                    echo "<span class='badge bg-danger'>Người dùng bị khóa</span>";
                                }     
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</a>
                            <a href="<?php echo base_url('manage-customer/edit/'.$cus->User_ID) ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-wrench"></i> Edit</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
