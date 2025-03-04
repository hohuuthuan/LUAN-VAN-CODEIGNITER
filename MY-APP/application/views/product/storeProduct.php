<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
<?php } elseif ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
<?php } ?>

<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>

<form action="<?php echo base_url('product/store') ?>" method="post" class="box" enctype="multipart/form-data">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung của sản phẩm</div>
                    <div class="panel-description">
                        - Nhập thông tin chung của Thông tin chung của sản phẩm
                        <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="title">Tên sản phẩm</label>
                                    <input name="title" type="text" class="form-control" id="slug"
                                        onkeyup="ChangeToSlug();" placeholder="Nhập tên sản phẩm">
                                    <?php echo '<span class="text-danger">' . form_error('title') . '</span>' ?>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="slug">Slug</label>
                                    <input name="slug" type="text" class="form-control" id="convert_slug"
                                        placeholder="Nhập slug">
                                    <?php echo '<span class="text-danger">' . form_error('slug') . '</span>' ?>
                                </div>
                            </div>



                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="brand">Thương hiệu</label>
                                        <select name="brand_id" class="form-control setupSelect2    ">
                                            <?php foreach ($brand as $key => $bra) { ?>
                                                <option value="<?php echo $bra->id ?>"><?php echo $bra->title ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo '<span class="text-danger">' . form_error('brand') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="quantity">Số lượng</label>
                                        <input name="quantity" type="number" class="form-control"
                                            placeholder="Nhập số lượng">
                                        <?php echo '<span class="text-danger">' . form_error('quantity') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category">Danh mục</label>
                                        <select name="category_id" class="form-control setupSelect2">
                                            <?php foreach ($category as $key => $cate) { ?>
                                                <option value="<?php echo $cate->id ?>"><?php echo $cate->title ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo '<span class="text-danger">' . form_error('category') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" class="form-control setupSelect2">
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="panel-head">
                        <div class="panel-title">Thông tin giá và đơn vị tính của sản phẩm</div>
                        <div class="panel-description">
                            - Nhập thông tin giá và đơn vị tính của sản phẩm
                            <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="import_price">Giá nhập (1 sản phẩm)</label>
                                        <input name="import_price_one_product" type="number" class="form-control"
                                            placeholder="Nhập giá nhập">
                                        <?php echo '<span class="text-danger">' . form_error('import_price_one_product') . '</span>' ?>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="selling_price">Giá bán</label>
                                        <input name="selling_price" type="number" class="form-control"
                                            placeholder="Nhập giá bán">
                                        <?php echo '<span class="text-danger">' . form_error('selling_price') . '</span>' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="discount">Giảm giá</label>
                                            <input name="discount" type="number" class="form-control"
                                                placeholder="Giảm giá (%)">
                                            <?php echo '<span class="text-danger">' . form_error('discount') . '</span>' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="unit">Đơn vị tính của sản phẩm</label>
                                            <input name="unit" type="text" class="form-control"
                                                placeholder="Nhập đơn vị tính">
                                            <?php echo '<span class="text-danger">' . form_error('unit') . '</span>' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="panel-head">
                        <div class="panel-title">Thông tin hạn sử dụng</div>
                        <div class="panel-description">
                            - Nhập thông tin hạn sử dụng của sản phẩm
                            <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="production_date">Ngày sản xuất của sản phẩm</label>
                                        <input name="production_date" type="date" class="form-control"
                                            placeholder="Chọn ngày sản xuất">
                                        <?php echo '<span class="text-danger">' . form_error('production_date') . '</span>' ?>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="expiration_date">Ngày hết hạn của sản phẩm</label>
                                        <input name="expiration_date" type="date" class="form-control"
                                            placeholder="Chọn ngày hết hạn">
                                        <?php echo '<span class="text-danger">' . form_error('expiration_date') . '</span>' ?>
                                    </div>
                                </div>
                            </div>

            



                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="panel-head">
                        <div class="panel-title">Mô tả và hình ảnh sản phẩm</div>
                        <div class="panel-description">
                            - Nhập thông tin mô tả và hình ảnh của sản phẩm
                            <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="description">Mô tả</label>
                                        <textarea name="description" class="form-control" rows="10"
                                            placeholder="Nhập mô tả sản phẩm"></textarea>
                                        <?php echo '<span class="text-danger">' . form_error('description') . '</span>' ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="image">Hình ảnh</label>
                                        <input name="image" type="file" class="form-control-file">
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

                <div class="text-right mb15">
                    <button type="submit" name="send" value="send" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
    </form>