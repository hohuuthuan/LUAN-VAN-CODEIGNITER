<footer id="footer"><!--Footer-->
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-sm-2">
					<div class="companyinfo">
						<h2><span>e</span>-shopper</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
					</div>
				</div>
				<div class="col-sm-7">
					<div class="col-sm-3">
						<div class="video-gallery text-center">
							<a href="#">
								<div class="iframe-img">
									<img src="images/home/iframe1.png" alt="" />
								</div>
								<div class="overlay-icon">
									<i class="fa fa-play-circle-o"></i>
								</div>
							</a>
							<p>Circle of Hands</p>
							<h2>24 DEC 2014</h2>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="video-gallery text-center">
							<a href="#">
								<div class="iframe-img">
									<img src="images/home/iframe2.png" alt="" />
								</div>
								<div class="overlay-icon">
									<i class="fa fa-play-circle-o"></i>
								</div>
							</a>
							<p>Circle of Hands</p>
							<h2>24 DEC 2014</h2>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="video-gallery text-center">
							<a href="#">
								<div class="iframe-img">
									<img src="images/home/iframe3.png" alt="" />
								</div>
								<div class="overlay-icon">
									<i class="fa fa-play-circle-o"></i>
								</div>
							</a>
							<p>Circle of Hands</p>
							<h2>24 DEC 2014</h2>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="video-gallery text-center">
							<a href="#">
								<div class="iframe-img">
									<img src="images/home/iframe4.png" alt="" />
								</div>
								<div class="overlay-icon">
									<i class="fa fa-play-circle-o"></i>
								</div>
							</a>
							<p>Circle of Hands</p>
							<h2>24 DEC 2014</h2>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="address">
						<img src="images/home/map.png" alt="" />
						<p>505 S Atlantic Ave Virginia Beach, VA(Virginia)</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer-widget">
		<div class="container">
			<div class="row">
				<div class="col-sm-2">
					<div class="single-widget">
						<h2>Service</h2>
						<ul class="nav nav-pills nav-stacked">
							<li><a href="#">Online Help</a></li>
							<li><a href="#">Contact Us</a></li>
							<li><a href="#">Order Status</a></li>
							<li><a href="#">Change Location</a></li>
							<li><a href="#">FAQ’s</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="single-widget">
						<h2>Quock Shop</h2>
						<ul class="nav nav-pills nav-stacked">
							<li><a href="#">T-Shirt</a></li>
							<li><a href="#">Mens</a></li>
							<li><a href="#">Womens</a></li>
							<li><a href="#">Gift Cards</a></li>
							<li><a href="#">Shoes</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="single-widget">
						<h2>Policies</h2>
						<ul class="nav nav-pills nav-stacked">
							<li><a href="#">Terms of Use</a></li>
							<li><a href="#">Privecy Policy</a></li>
							<li><a href="#">Refund Policy</a></li>
							<li><a href="#">Billing System</a></li>
							<li><a href="#">Ticket System</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="single-widget">
						<h2>About Shopper</h2>
						<ul class="nav nav-pills nav-stacked">
							<li><a href="#">Company Information</a></li>
							<li><a href="#">Careers</a></li>
							<li><a href="#">Store Location</a></li>
							<li><a href="#">Affillate Program</a></li>
							<li><a href="#">Copyright</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1">
					<div class="single-widget">
						<h2>About Shopper</h2>
						<form action="#" class="searchform">
							<input type="text" placeholder="Your email address" />
							<button type="submit" class="btn btn-default"><i
									class="fa fa-arrow-circle-o-right"></i></button>
							<p>Get the most recent updates from <br />our site and be updated your self...</p>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<p class="pull-left">Copyright © 2013 E-SHOPPER Inc. All rights reserved.</p>
				<p class="pull-right">Designed by <span><a target="_blank"
							href="http://www.themeum.com">Themeum</a></span></p>
			</div>
		</div>
	</div>

</footer><!--/Footer-->


<script src="<?php echo base_url('frontend/js/jquery.js') ?>"></script>
<script src="<?php echo base_url('frontend/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('frontend/js/jquery.scrollUp.min.js') ?>"></script>
<script src="<?php echo base_url('frontend/js/price-range.js') ?>"></script>
<script src="<?php echo base_url('frontend/js/jquery.prettyPhoto.js') ?>"></script>
<script src="<?php echo base_url('frontend/js/main.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
	integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script>
	$(document).ready(function () {
		var active = location.search;
		$('#select-filter option[value="' + active + '"]').attr('selected', 'selected');
	});


	$('.select-filter').change(function () {
		// const value = $(this).val();

		const value = $(this).find(':selected').val();

		if (value != 0) {
			var url = value;
			window.location.replace(url);
		} else {
			alert('Hãy lọc sản phẩm');
		}

	});
</script>

<script>
	$('.price_from').val(<?php echo $min_price ?>);
	$('.price_to').val(<?php echo $max_price / 2 ?>);
	$(function () {
		$("#slider-range").slider({
			range: true,
			min: <?php echo $min_price ?>,
			max: <?php echo $max_price ?>,
			values: [<?php echo $min_price ?>, <?php echo $max_price / 2 ?>],
			slide: function (event, ui) {
				$("#amount").val(addPlus(ui.values[0]).toString() + "vnđ" + '-' + addPlus(ui.values[1]) + "vnđ");
				$('.price_from').val(ui.values[0]);
				$('.price_to').val(ui.values[1]);
			}

		});
		$("#amount").val(addPlus($("#slider-range").slider("values", 0)) +
			"vnđ" + '-' + addPlus($("#slider-range").slider("values", 1)) + 'vnđ');
	});
	function addPlus(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}
</script>

<script>
	$('.write-comment').click(function () {
		var name_comment = $('.name_comment').val();
		var email_comment = $('.email_comment').val();
		var comment = $('.comment').val();
		var pro_id_cmt = $('.product_id_comment').val();
		if (name_comment == '' || email_comment == '' || comment == '') {
			alert('Hãy điền đầy đủ thông tin');
		} else {
			$.ajax({
				method: 'POST',
				url: '/comment/send',
				data: { name_comment: name_comment, email_comment: email_comment, comment: comment, pro_id_cmt: pro_id_cmt },
				success: function () {
					alert('Đánh giá của bạn đã được ghi nhận');
				}
			})
		}


	});
</script>

<!-- Check ô nhập mật khẩu thứ 2 có giống với ô 1 hay không -->
<script>
	document.getElementById('password2').addEventListener('input', checkPasswordMatch);

	function checkPasswordMatch() {
		var password1 = document.getElementById('password1').value;
		var password2 = document.getElementById('password2').value;

		if (password1 !== password2) {
			document.getElementById('password2').setCustomValidity('Mật khẩu không khớp');
		} else {
			document.getElementById('password2').setCustomValidity('');
		}
	}
</script>









<script>
	// Hiển thị vòng xoay khi trang được tải lại
	window.addEventListener('beforeunload', function () {
		document.getElementById('loader').style.display = 'block';
	});

</script>





</body>



<script>
	$.ajax({
		url: '<?= base_url('search-by-disease') ?>',
		method: 'POST',
		data: { disease_name: 'Tên bệnh từ AI' },
		success: function (response) {
			$('#result-container').html(response);
		},
		error: function (err) {
			console.error('Lỗi:', err);
		}
	});

</script>



</html>