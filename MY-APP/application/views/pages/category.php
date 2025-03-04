<section>
		<div class="container">
			<div class="row">
			<?php $this->load->view('pages/component/sidebar'); ?>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="exampleFormControlSelect1">Lọc theo</label>
									<select id="select-filter" class="form-control select-filter">
										<option value="0">---Lọc theo---</option>
										<option value="?kytu=asc">Ký tự A-Z</option>
										<option value="?kytu=desc">Ký tự Z-A</option>
										<option value="?gia=asc">Giá tăng dần</option>
										<option value="?gia=desc">Giá giảm dần</option>
									</select>
								</div>
							</div>
							<div class="col-md-7">
								<form method="GET">
								<p>
									<label for="amount">Price range:</label>
									<input type="text" id="amount" readonly="" style="border:0; color:#f6931f; font-weight:bold;">
									<input type="hidden" class="price_from" name="from">
									<input type="hidden" class="price_to" name="to">
									<input type="submit" class="btn btn-primary filter-price" value="Lọc giá sản phẩm">
								</p>
								
								<div id="slider-range"></div>
								</form>
							
							</div>
						</div>




						<h2 class="title text-center"><?php echo $title ?></h2>
						<?php 
							foreach ($allproductbycate_pagination as $key => $catePro) {
						?>
						<form action="<?php echo base_url('add-to-cart') ?>" method="POST">
							<div class="col-sm-4">
								<div class="product-image-wrapper">
								<input type="hidden" value="<?php echo $catePro->id?>" name="product_id">
								<input type="hidden" value="1" name="quantity">
									<div class="single-products">
											<div class="productinfo text-center">
												<img src="<?php echo base_url('uploads/product/'.$catePro->image)?>" alt="<?php echo $catePro->title ?>" />
												<h2><?php echo number_format($catePro->selling_price,0,',','.')?> VND</h2>
												<p><?php echo $catePro->title ?></p>
												<a href="<?php echo base_url('san-pham/'.$catePro->id.'/'.$catePro->slug)?>" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>Details</a>
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
						<?php echo $links ?>
					</div><!--features_items-->

					
				</div>
			</div>
		</div>
	</section>