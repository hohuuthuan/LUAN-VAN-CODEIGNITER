<table style="border-collapse: collapse;" class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">#</th>
            <th scope="text-center">Image</th>
            <th scope="text-center">Title</th>
            <th scope="text-center">Status</th>
            <th scope="text-center">Manage</th>

        </tr>
    </thead>

    <tbody>
        <?php foreach ($slider as $key => $sli): ?>
            <tr>
                <td>
                    <input type="checkbox" value="" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>
                <td style="width: 500px;">
                    <img src="<?php echo base_url('uploads/sliders/' . $sli->image); ?>" alt=""
                        style="width: 100%; height: 150px; object-fit: cover;">
                </td>

                <td style="max-width: 170px; word-wrap: break-word;">
                    <?php echo $sli->title; ?>
                </td>

                <td>
                    <span class="badge <?php echo $sli->status == 1 ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $sli->status == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td style="width: 100px" class="text-center">
                    <a href="<?php echo base_url('slider/edit/' . $sli->id); ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <a href="<?php echo base_url('slider/delete/' . $sli->id); ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>

</table>