




<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
<?php } elseif ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
<?php } ?>

<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>

<form action="<?php echo base_url('warehouse/plusquantity-importpriceinwwarehouses/' . $product->id); ?>" method="post" class="box" enctype="multipart/form-data">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Nhập thông tin sản phẩm của lần nhập kho</div>
                    <div class="panel-description">

                        <p>- Lưu ý: những trường được đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>

                    </div>
                </div>
                <img src="<?php echo base_url('uploads/product/' . $product->image); ?>"
                    alt="<?php echo htmlspecialchars($product->title); ?>" class="img-thumbnail"
                    style="width: 300px; height: auto;">
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="title">Tên sản phẩm</label>
                                    <p><?php echo $product->title ?></p>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="slug">Số lượng hiện tại</label>
                                    <p><?php echo $product->quantity; ?></p>
                                </div>
                            </div>



                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="brand">Giá bán hiện tại</label>
                                        <p><?php echo number_format($product->selling_price, 0, ',', '.'); ?> VNĐ</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="quantity">Số lượng tồn kho</label>
                                        <p><?php echo $product->quantity ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="quantity_warehouses" class="form-label">SL sản phẩm nhập kho</label>
                                    <input type="number" name="quantity_warehouses" class="form-control"
                                        id="quantity_warehouses" placeholder="Nhập số lượng" required>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="import_price_warehouses" class="form-label">Giá nhập mới (VNĐ)</label>
                                    <input type="number" name="import_price_warehouses" class="form-control"
                                        id="import_price_warehouses" placeholder="Nhập giá tiền" required>
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