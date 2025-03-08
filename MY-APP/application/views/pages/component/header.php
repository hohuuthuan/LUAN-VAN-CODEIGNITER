<header id="header">
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="logo pull-left">
							<a href="<?php echo base_url('/') ?>"><img style="width: 100px" src="<?php echo base_url('frontend/image/logo.png') ?>" alt="" />PESTICIDE SHOP</a>
						</div>

					</div>
					<div class="col-sm-8">
						<div class="shop-menu pull-right">
							<ul class="nav navbar-nav">
								<?php
								if ($this->session->userdata('logged_in_customer')) {
									?>
									<li>
										<a href="#">
											<li class="nav-item dropdown">
												<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
													data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fa fa-user"></i>
													<b>
														<?php echo $this->session->userdata('logged_in_customer')['username'] ?>
													</b>
													<i class="fa-solid fa-caret-down"></i>
										

												</a>
												<div style=" text-align: center;" class="dropdown-menu" aria-labelledby="navbarDropdown">
													<a class="dropdown-item"
														href="<?php echo base_url('profile-user'); ?>"><h5>Thông tin cá nhân</h5></a>
												</div>
											</li>
										</a>
									</li>
									<!-- <li><a href="#"><i class="fa fa-star"></i> Wishlist</a></li> -->
									<li>
										<a href="<?php echo base_url('checkout') ?>"><i class="fa fa-crosshairs"></i>
											Checkout
										</a>
									</li>
									<li>

										<a href="<?php echo base_url('dang-xuat') ?>"><i class="fa fa-lock"></i> Logout</a>
									</li>
								<?php
								} else {
									?>
									<li><a href="<?php echo base_url('dang-nhap') ?>"><i class="fa fa-lock"></i> Login</a>
									</li>
								<?php } ?>
								<li>
									<a href="<?php echo base_url('gio-hang') ?>"><i class="fa fa-shopping-cart"></i>
										Cart</a>
								</li>
								<li>
									<a href="<?php echo base_url('order_customer/listOrder') ?>"><i class="fa fa-list"></i> List Order
									</a>
								</li>

							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->

		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-7">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse"
								data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="<?php echo base_url('/') ?>" class="active">Home</a></li>
								<li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<?php
										foreach ($category as $key => $cate) {
											?>
											<li><a
													href="<?php echo base_url('danh-muc/' . $cate->id . '/' . $cate->slug) ?>"><?php echo $cate->title ?></a>
											</li>
										<?php } ?>
									</ul>
								</li>
								<!-- <li class="dropdown"><a href="#">Blog<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<li><a href="blog.html">Blog List</a></li>
										<li><a href="blog-single.html">Blog Single</a></li>
									</ul>
								</li>
								<li><a href="404.html">404</a></li>
								<li><a href="contact-us.html">Contact</a></li> -->
								<!-- <li><a href="<?php echo base_url('getlayoutAI')?>">Dự đoán bệnh sầu riêng</a></li> -->
							</ul>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="search_box pull-right">
							<form action="<?php echo base_url('search-product') ?>" method="GET">
								<input type="text" name="keyword" placeholder="Search product..." id="searchKeyword"  />
								<input type="submit" class="btn btn-default" value="Search" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header>