



<h1 style="text-align: center; margin-bottom: 30px; color: #FE980F;">HỆ THỐNG DỰ ĐOÁN BỆNH TRÊN CÂY SẦU RIÊNG TRÊN LÁ
</h1>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class='upload_part'>
                <h2 style="margin-bottom: 0px;">Tải ảnh lên:</h2>
                <button style="font-size: 16px; background-color: #FE980F; color: white;"
                    class='upload_button btn btn-primary' id="upload_button">
                    <i class="fa fa-upload" aria-hidden="true"></i> Chọn file ảnh lá
                </button>

                <div style="color: red" class='upload_hint' id='upload_hint'>
                    Các định dạng được hỗ trợ: PNG, JPG và JPEG
                </div>
                <form action="/" method="POST" enctype="multipart/form-data" id='form'>
                    <input type="file" name="file" id="fileinput" accept="image/*" style="display:none">
                </form>
            </div>

            <div style="margin-top: -85px;" class='result_part'>
                <div class='result_title'>
                    <h2 style="color: #FE980F;">Kết quả nhận diện</h2>
                </div>
                <div class='result_id' id="result_info" style="font-size: 18px; color: #333;">_</div>
                <img style="max-width:300px; border-radius:1rem" id="display_image"
                    alt="Hiện chưa có ảnh nào được tải lên">
            </div>



            <!-- Phần xử lý AI -->
            <script>
                const DISEASE_CLASS = {
                    0: 'Đốm rong',
                    1: 'Cháy lá',
                    2: 'Đốm lá',
                    3: 'Không có bệnh',
                };

                // Load model
                $("document").ready(async function () {
                    model = await tf.loadLayersModel('<?php echo base_url('public/static/model.json'); ?>');

                    console.log('Load model');
                    console.log(model.summary());
                });

                $("#upload_button").click(function () {
                    $("#fileinput").trigger('click');
                });

                async function predict() {
                    // 1. Chuyển ảnh về tensor
                    let image = document.getElementById("display_image");
                    let img = tf.browser.fromPixels(image);
                    let normalizationOffset = tf.scalar(255 / 2); // 127.5
                    let tensor = img
                        .resizeNearestNeighbor([224, 224])
                        .toFloat()
                        .sub(normalizationOffset)
                        .div(normalizationOffset)
                        .reverse(2)
                        .expandDims();

                    // 2. Dự đoán
                    let predictions = await model.predict(tensor);
                    predictions = predictions.dataSync();
                    console.log(predictions);

                    // 3. Hiển thị kết quả chỉ với xác suất cao nhất
                    let topPrediction = Array.from(predictions)
                        .map(function (p, i) {
                            return {
                                probability: p,
                                className: DISEASE_CLASS[i]
                            };
                        }).sort(function (a, b) {
                            return b.probability - a.probability;
                        })[0]; // Lấy phần tử đầu tiên (có xác suất cao nhất)

                    console.log(topPrediction);

                    if (topPrediction && !isNaN(topPrediction.probability)) {
                        let probabilityPercent = (topPrediction.probability * 100).toFixed(2);

                        // Hiển thị kết quả trên giao diện
                        let listItem = `Có vẻ như cây của bạn đang bị bệnh: <b style="color: red">${topPrediction.className}</b> với xác suất là <b style="color: red">${probabilityPercent}%</b>`;
                        $("#result_info").empty();
                        $("#result_info").append(listItem);

                        // Gửi kết quả className đến server
                        $.ajax({
                            url: "<?php echo base_url('search-by-disease'); ?>",
                            type: "POST",
                            data: { disease_name: topPrediction.className },
                            success: function (response) {
                                // Thay đổi giao diện bên phải với danh sách sản phẩm trả về
                                $(".features_items").html(response);
                            },
                            error: function (error) {
                                console.error("Lỗi khi gửi dữ liệu tới server:", error);
                            }
                        });
                    }
                }


                $("#fileinput").change(function () {
                    let reader = new FileReader();
                    reader.onload = function () {
                        let dataURL = reader.result;

                        imEl = document.getElementById("display_image");
                        imEl.onload = function () {
                            predict();
                        };
                        $("#display_image").attr("src", dataURL);
                        $("#result_info").empty(); // Xóa các kết quả trước đó
                    }

                    let file = $("#fileinput").prop("files")[0];
                    reader.readAsDataURL(file);
                });

            </script>


        </div>
        <div class="col-sm-9 padding-right">
            <div class="features_items">
                <h2 class="title text-center">Gợi ý sản phẩm điều trị</h2>

                <?php if (!empty($products_by_disease)) { ?>
                    <?php foreach ($products_by_disease as $product) { ?>
                        <form action="<?php echo base_url('add-to-cart') ?>" method="POST">
                        <input type="hidden" value="<?php echo $product->id ?>" name="product_id">
                        <input type="hidden" value="1" name="quantity">
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="<?php echo base_url('uploads/product/' . $product->image); ?>"
                                            alt="<?php echo $product->title; ?>" />
                                        <h2><?php echo number_format($product->selling_price, 0, ',', '.'); ?> VND</h2>
                                        <p><?php echo $product->title; ?></p>
                                        <a href="<?php echo base_url('san-pham/' . $product->id . '/' . $product->slug); ?>"
                                            class="btn btn-default add-to-cart">
                                            <i class="fa fa-eye"></i> Details
                                        </a>
                                        <button type="submit" class="btn btn-fefault cart">
												<i class="fa fa-shopping-cart"></i>
												Add to cart
										</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-center">Hiện chưa có sản phẩm.</p>
                <?php } ?>
            </div>
        </div>

    </div>
</div>