<section>
	<div class="container">
		<div class="row">
			<?php $this->load->view('pages/component/sidebar'); ?>

			<div class="col-sm-9 padding-right">

				<?php
				foreach ($product_details as $key => $pro_det) {
					?>
					<div class="product-details"><!--product-details-->
						<div class="col-sm-5">
							<div class="view-product">
								<img style="width: 300px; height: 300px"
									src="<?php echo base_url('uploads/product/' . $pro_det->image) ?>"
									alt="<?php echo $pro_det->title ?>" />

							</div>


						</div>

						<form action="<?php echo base_url('add-to-cart') ?>" method="POST">
							<div class="col-sm-7">
								<?php if ($this->session->flashdata('success')) { ?>
									<div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
								<?php } elseif ($this->session->flashdata('error')) { ?>
									<div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
								<?php } ?>
								<div class="product-information"><!--/product-information-->
									<h2><?php echo $pro_det->title ?></h2>
									<input type="hidden" value="<?php echo $pro_det->id ?>" name="product_id">
									<span>



										<?php
										// Kiểm tra giá trị của $allPro->discount
										if (isset($pro_det->discount) && $pro_det->discount != 0) {
											// Tính giá giảm
											$price_no_discount = $pro_det->selling_price;
											$pro_det->selling_price = $pro_det->selling_price * (1 - $pro_det->discount / 100);
											?>
											<h2>
												<span><?php echo number_format($pro_det->selling_price, 0, ',', '.') ?>
													VND</span>
												<label
													style="text-decoration: line-through; margin-top: 5px"><?php echo number_format($price_no_discount, 0, ',', '.') ?>
													VND</label>
												<br>

											</h2>
											<?php
										} else {
											// Hiển thị giá gốc nếu discount bằng 0
											?>
											<h2><span><?php echo number_format($pro_det->selling_price, 0, ',', '.') ?>
													VND</span></h2>
											<br>
											<?php
										}
										?>

										<br>
										<label>Quantity: <?php echo $pro_det->quantity ?></label>
										<input type="number" min="1" value="1" name="quantity" />
										<button type="submit" class="btn btn-fefault cart">
											<i class="fa fa-shopping-cart"></i>
											Add to cart
										</button>
									</span>
									<p><b>Tình trạng:</b>
										<?php
										if ($pro_det->quantity > 0) {
											echo "Còn hàng";
										} else {
											echo "Hết hàng";
										}
										?>
									</p>

									<p style="text-align: justify; text-justify: inter-word"><b>Mô tả:</b> <?php echo $pro_det->description ?></p>

									<p><b>Brand:</b> <?php echo $pro_det->tenthuonghieu ?> </p>
									<p><b>Category:</b> <?php echo $pro_det->tendanhmuc ?></p>
									<a href=""><img src="images/product-details/share.png" class="share img-responsive"
											alt="" /></a>
								</div><!--/product-information-->
							</div>
						</form>
					</div><!--/product-details-->
				<?php } ?>

				<div class="category-tab shop-details-tab"><!--category-tab-->
					<div class="col-sm-12">
						<ul class="nav nav-tabs">

							<li class="active"><a href="#reviews" data-toggle="tab">Reviews (5)</a></li>
						</ul>
					</div>
					<div class="tab-content">


						<div class="tab-pane fade active in" id="reviews">
							<div class="col-sm-12">
								<?php
								foreach ($product_comments as $comment) {
									?>
									<ul>
										<li><a href=""><i class="fa fa-user"></i><?php echo $comment->name ?></a></li>
										<li><a href=""><i class="fa fa-clock-o"></i><?php echo $comment->date_cmt ?></a>
										</li>
										<p><?php echo $comment->comment ?></p>

									</ul>


								<?php } ?>
								<form>
									<span>
										<input type="hidden" required class="product_id_comment"
											value="<?php echo $pro_det->id ?>">
										<input class="name_comment" type="text" required
											placeholder="Họ và Tên của bạn..." />
										<input class="email_comment" type="email" required placeholder="Email..." />
									</span>
									<textarea class="comment"  placeholder="Viết đánh giá..." required>

									</textarea>
									<!-- <b>Rating: </b> <img src="images/product-details/rating.png" alt="" /> -->
									<button type="button" class="btn btn-default pull-right write-comment">
										Gửi đánh giá
									</button>
								</form>
							</div>
						</div>

					</div>
				</div><!--/category-tab-->



			</div>
		</div>
	</div>
</section>