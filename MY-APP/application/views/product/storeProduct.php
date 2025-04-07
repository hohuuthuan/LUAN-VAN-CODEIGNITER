<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>
<form action="<?php echo base_url('product/store') ?>" method="post" class="box" enctype="multipart/form-data">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung của sản phẩm
                    </div>
                    <div class="panel-description">
                        - Nhập thông tin chung của sản phẩm
                        <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Name">Tên sản phẩm</label>
                                    <input name="Name" type="text" class="form-control" id="slug"
                                        onkeyup="ChangeToSlug();" placeholder="Nhập tên sản phẩm">
                                    <?php echo '<span class="text-danger">' . form_error('Name') . '</span>' ?>

                                    <input type="hidden" name="Slug" type="text" class="form-control" id="convert_slug"
                                        placeholder="Nhập slug">
                                    <?php echo '<span class="text-danger">' . form_error('slug') . '</span>' ?>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="BrandID">Thương hiệu</label>
                                        <select name="BrandID" class="form-control setupSelect2    ">
                                            <?php foreach ($brand as $key => $bra) { ?>
                                                <option value="<?php echo $bra->BrandID ?>"><?php echo $bra->Name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <?php echo '<span class="text-danger">' . form_error('brand') . '</span>' ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Promotion">Mã sản phẩm</label>
                                    <input name="Product_Code" type="text" class="form-control" min="0" max="100"
                                        placeholder="Nhập mã sản phẩm">
                                    <?php echo '<span class="text-danger">' . form_error('Product_Code') . '</span>' ?>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category">Danh mục</label>
                                        <select name="CategoryID" class="form-control setupSelect2">
                                            <?php foreach ($category as $key => $cate) { ?>
                                                <option value="<?php echo $cate->CategoryID ?>"><?php echo $cate->Name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <?php echo '<span class="text-danger">' . form_error('category') . '</span>' ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="Unit">Đơn vị tính của sản phẩm</label>
                                        <input name="Unit" type="text" class="form-control"
                                            placeholder="Nhập đơn vị tính">
                                        <?php echo '<span class="text-danger">' . form_error('unit') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="Selling_price">Giá bán</label>
                                        <input name="Selling_price" type="number" class="form-control"
                                            placeholder="Nhập giá bán">
                                        <?php echo '<span class="text-danger">' . form_error('selling_price') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Promotion">Khuyến mãi</label>
                                    <input name="Promotion" type="number" class="form-control" min="0" max="100"
                                        placeholder="Giảm giá (%)">
                                    <?php echo '<span class="text-danger">' . form_error('discount') . '</span>' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">
                        Mô tả và công dụng sản phẩm
                    </div>
                    <div class="panel-description">
                        - Nhập thông tin giá và đơn vị tính của sản phẩm
                        <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Description">Mô tả</label>
                                    <textarea name="Description" class="form-control" rows="4"
                                        placeholder="Nhập mô tả sản phẩm"></textarea>
                                    <?php echo '<span class="text-danger">' . form_error('description') . '</span>' ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="Product_uses">Công dụng của sản phẩm</label>
                                        <textarea name="Product_uses" class="form-control textarea_custom" rows="4"
                                            placeholder="Nhập mô tả sản phẩm"></textarea>
                                        <?php echo '<span class="text-danger">' . form_error('Product_uses') . '</span>' ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-head">
                    <div class="panel-title">
                        Trạng thái và hình ảnh sản phẩm
                    </div>
                    <!-- <div class="panel-description">
                            - Nhập thông tin mô tả và hình ảnh của sản phẩm
                            <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                        </div> -->
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Status">Trạng thái</label>
                                    <select name="Status" class="form-control setupSelect2">
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="Image">Hình ảnh</label>
                                    <input name="Image" type="file" class="form-control-file">
                                    <small class="text-danger"><?php if (isset($error))
                                                                    echo $error ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="text-right mb15 mr20">
            <button type="submit" name="send" value="send" class="btn btn-primary">Lưu lại</button>
        </div>
    </div>
</form>