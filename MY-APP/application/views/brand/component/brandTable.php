


<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">#</th>
            <th scope="text-center">Image</th>
            <th scope="text-center">Title</th>

            <th scope="text-center">Description</th>

            <th scope="text-center">Status</th>
            <th scope="text-center">Manage</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($brand as $key => $bra): ?>
            <tr>
                <td>
                    <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>
                <td>
                    <img style="height: 20px; width: 20px" src="<?php echo base_url('uploads/brand/' . $bra->image); ?>"
                        alt="" width="150" height="150">
                </td>
                <td><?php echo $bra->title; ?></td>

                <td style="max-width: 700px"><?php echo $bra->description ?></td>

                <td>
                    <span class="badge <?php echo $bra->status == 1 ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $bra->status == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td style="width: 100px" class="text-center">
                    <a href="<?php echo base_url('brand/edit/' . $bra->id); ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <a href="<?php echo base_url('brand/delete/' . $bra->id); ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
    
</table>