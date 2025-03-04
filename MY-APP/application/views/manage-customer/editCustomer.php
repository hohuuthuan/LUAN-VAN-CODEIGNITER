<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
<?php } elseif ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
<?php } ?>

<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>

<form action="<?php echo base_url('manage-customer/update/' . $customers->id) ?>" method="post" class="box"
    enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $customers->id ?>">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung của người dùng</div>
                    <div class="panel-description">
                        - Thông tin chung của người dùng
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
                                    <label for="username">Tên người dùng</label>
                                    <input name="username" value="<?php echo $customers->username ?>" type="text"
                                        class="form-control" placeholder="Nhập tên người dùng">
                                    <?php echo '<span class="text-danger">' . form_error('title') . '</span>' ?>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="email">Email</label>
                                    <input name="email" value="<?php echo $customers->email ?>" type="text"
                                        class="form-control" id="convert_slug" placeholder="Nhập slug">
                                    <?php echo '<span class="text-danger">' . form_error('slug') . '</span>' ?>
                                </div>
                            </div>



                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="<?php echo $customers->address ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="<?php echo $customers->phone ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Ảnh đại diện</label>
                                        <input type="file" class="form-control-file" id="image" name="image">
                                        <div class="mt-2">
                                            <img src="<?php echo base_url('uploads/user/' . $customers->avatar) ?>"
                                                alt="Avatar" width="150" height="150">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="status" class="form-label">Trạng thái tài khoản</label>
                                    <select class="form-control setupSelect2" id="status" name="status">
                                        <option value="1" <?php echo ($customers->status == 1) ? 'selected' : ''; ?>>Kích
                                            hoạt</option>
                                        <option value="0" <?php echo ($customers->status == 0) ? 'selected' : ''; ?>>Khóa
                                            tài khoản
                                        </option>
                                    </select>
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