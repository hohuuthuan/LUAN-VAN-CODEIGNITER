


<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">#</th>
            <th scope="text-center">Image</th>
            <th scope="text-center">Name</th>

            <th scope="text-center">Description</th>

            <th scope="text-center">Status</th>
            <th scope="text-center">Manage</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($category as $key => $cate): ?>
            <tr>
                <td>
                    <input type="checkbox" value="" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>
                <td>
                    <img style="height: 20px; width: 20px" src="<?php echo base_url('uploads/category/' . $cate->Image); ?>"
                        alt="" width="150" height="150">
                </td>
                <td><?php echo $cate->Name; ?></td>

                <td style="max-width: 700px"><?php echo $cate->Description ?></td>

                <td>
                    <span class="badge <?php echo $cate->Status == 1 ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $cate->Status == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td style="width: 100px" class="text-center">
                    <a href="<?php echo base_url('category/list/edit/' . $cate->CategoryID); ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <a href="<?php echo base_url('category/delete/' . $cate->CategoryID); ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
    
</table>