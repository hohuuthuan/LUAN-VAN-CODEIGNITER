<h1 class="page-title">HỆ THỐNG DỰ ĐOÁN BỆNH TRÊN CÂY SẦU RIÊNG TRÊN LÁ</h1>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="upload-part">
                <h2>Tải ảnh lên:</h2>
                <button class="upload-button btn btn-primary" id="upload_button">
                    <i class="fa fa-upload" aria-hidden="true"></i> Chọn file ảnh lá
                </button>

                <div class="upload-hint" id="upload_hint">
                    Các định dạng được hỗ trợ: PNG, JPG và JPEG
                </div>
                <form action="/" method="POST" enctype="multipart/form-data" id="form">
                    <input type="file" name="file" id="fileinput" accept="image/*" style="display:none">
                </form>
            </div>

            <div class="result-part">
                <h2 class="result-title">Kết quả nhận diện</h2>
                <div class="result-id" id="result_info">_</div>
                <img class="result-image" id="display_image" alt="Hiện chưa có ảnh nào được tải lên">
            </div>
        </div>

        <div class="col-sm-9 padding-right">
            <div class="features_items">
                <h2 class="title text-center">Gợi ý sản phẩm điều trị</h2>
                
                <?php if (!empty($products_by_disease)) { ?>
                    <?php foreach ($products_by_disease as $product) { ?>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="<?php echo base_url('uploads/product/' . $product->image); ?>" alt="<?php echo $product->title; ?>" />
                                        <h2><?php echo number_format($product->selling_price, 0, ',', '.'); ?> VND</h2>
                                        <p><?php echo $product->title; ?></p>
                                        <a href="<?php echo base_url('san-pham/' . $product->id . '/' . $product->slug); ?>" class="btn btn-default add-to-cart">
                                            <i class="fa fa-eye"></i> Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-center">Hiện chưa có sản phẩm.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript -->
<script>
    const DISEASE_CLASS = {
        0: 'Đốm rong',
        1: 'Cháy lá',
        2: 'Đốm lá',
        3: 'Không có bệnh',
    };

    $("document").ready(async function () {
        model = await tf.loadLayersModel('<?php echo base_url('public/static/model.json'); ?>');
        console.log('Load model');
    });

    $("#upload_button").click(function () {
        $("#fileinput").trigger('click');
    });

    async function predict() {
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

        let predictions = await model.predict(tensor);
        predictions = predictions.dataSync();

        let topPrediction = Array.from(predictions)
            .map(function (p, i) {
                return {
                    probability: p,
                    className: DISEASE_CLASS[i]
                };
            }).sort(function (a, b) {
                return b.probability - a.probability;
            })[0];

        if (topPrediction && !isNaN(topPrediction.probability)) {
            let probabilityPercent = (topPrediction.probability * 100).toFixed(2);
            let resultMessage = `Có vẻ như cây của bạn đang bị bệnh: <b style="color: red">${topPrediction.className}</b> với xác suất là: <b style="color: red">${probabilityPercent}%</b>`;
            $("#result_info").html(resultMessage);

            $.ajax({
                url: "<?php echo base_url('search-by-disease'); ?>",
                type: "POST",
                data: { disease_name: topPrediction.className },
                success: function (response) {
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
            let imgEl = document.getElementById("display_image");
            imgEl.onload = function () {
                predict();
            };
            $("#display_image").attr("src", dataURL);
            $("#result_info").empty(); // Clear previous result
        }
        let file = $("#fileinput").prop("files")[0];
        reader.readAsDataURL(file);
    });
</script>

<!-- CSS Styling (optional) -->
<style>
    .page-title {
        text-align: center;
        margin-bottom: 30px;
        color: #FE980F;
    }

    .upload-part, .result-part {
        margin-top: 20px;
    }

    .upload-button {
        font-size: 16px;
        background-color: #FE980F;
        color: white;
    }

    .upload-hint {
        color: red;
    }

    .result-title {
        color: #FE980F;
    }

    .result-id {
        font-size: 18px;
        color: #333;
    }

    .result-image {
        max-width: 300px;
        border-radius: 1rem;
    }
</style>
