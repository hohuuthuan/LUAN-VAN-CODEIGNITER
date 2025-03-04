<section>
		<div class="container">
			<div class="row">
			<?php $this->load->view('pages/component/sidebar'); ?>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center"><?php echo $title ?></h2>
						<?php 
							foreach ($allproductbybrand_pagination as $key => $braPro) {
						?>
						<form action="<?php echo base_url('add-to-cart') ?>" method="POST">
							<div class="col-sm-4">
								<div class="product-image-wrapper">
								<input type="hidden" value="<?php echo $braPro->id?>" name="product_id">
								<input type="hidden" value="1" name="quantity">
									<div class="single-products">
											<div class="productinfo text-center">
												<img src="<?php echo base_url('uploads/product/'.$braPro->image)?>" alt="<?php echo $braPro->title ?>" />
												<h2><?php echo number_format($braPro->selling_price,0,',','.')?> VND</h2>
												<p><?php echo $braPro->title ?></p>
												<a href="<?php echo base_url('san-pham/'.$braPro->id.'/'.$braPro->slug)?>" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>Details</a>
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