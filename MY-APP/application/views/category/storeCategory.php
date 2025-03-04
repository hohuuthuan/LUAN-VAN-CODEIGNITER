<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
<?php } elseif ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
<?php } ?>

<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>

<form action="<?php echo base_url('category/store') ?>" method="post" class="box" enctype="multipart/form-data">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin danh mục</div>
                    <div class="panel-description">
                        - Nhập thông tin chung của nhãn hàng
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
                                    <label for="" class="control-label text-right">Tên thương hiệu<span
                                            class="text-danger" required>(*)</span></label>
                                            <input name="title" type="text" class="form-control" id="slug" onkeyup="ChangeToSlug();" placeholder="Nhập tên thương hiệu">
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-right">Slug<span
                                            class="text-danger">(*)</span></label>
                                    <input name="slug" type="text" class="form-control" id="convert_slug"
                                        placeholder="Nhập slug">
                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea name="description" type="text" class="form-control" rows="4"
                                            placeholder="Nhập mô tả thương hiệu"></textarea>
                                        <?php echo '<span class="text-danger">' . form_error('description') . '</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="image">Hình ảnh</label>
                                        <input name="image" type="file" class="form-control-file">
                                        <small class="text-danger"><?php if (isset($error))
                                            echo $error ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb15">
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
            </div>
            <hr>
         
            <div class="text-right mb15">
                <button type="submit" name="send" value="send" class="btn btn-primary">Lưu lại</button>
            </div>
        </div>
    </form>