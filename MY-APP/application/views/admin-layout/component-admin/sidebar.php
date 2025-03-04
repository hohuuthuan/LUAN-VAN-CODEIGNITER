<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span>
									<img
										alt="image"
										class="img-circle"
										src="img/profile_small.jpg"
									/>
								</span>
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<span class="clear">
										<span class="block m-t-xs">
											<strong class="font-bold">David Williams</strong>
										</span>
										<span class="text-muted text-xs block"
											>Art Director <b class="caret"></b
										></span>
									</span>
								</a>
								<ul class="dropdown-menu animated fadeInRight m-t-xs">
									<li><a href="#">Profile</a></li>
									<li><a href="#">Contacts</a></li>
									<li><a href="#">Mailbox</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo base_url('logout_admin'); ?>">Logout</a></li>
								</ul>
							</div>
							<div class="logo-element">IN+</div>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý thương hiệu</span>
								<span class="fa arrow"></span
							></a>
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('brand/create'); ?>">Thêm thương hiệu</a></li>
								<li class="">
									<a href="<?php echo base_url('brand/list'); ?>">Danh sách thương hiệu</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý danh mục</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('category/create'); ?>">Thêm danh mục</a></li>
								<li class="">
									<a href="<?php echo base_url('category/list'); ?>">Danh sách danh mục</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý sản phẩm</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('product/create'); ?>">Thêm sản phẩm</a></li>
								<li class="">
									<a href="<?php echo base_url('product/list'); ?>">Danh sách sản phẩm</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý đơn hàng</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('order_admin/listOrder'); ?>">Danh sách đơn hàng</a></li>
								
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý banner</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('slider/create'); ?>">Thêm Banner mới</a></li>
								<li class="">
									<a href="<?php echo base_url('slider/list'); ?>">Danh sách Banner</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý người dùng</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('customer/create'); ?>">Thêm người dùng mới</a></li>
								<li class="">
									<a href="<?php echo base_url('customer/list'); ?>">Danh sách người dùng</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý kho hàng</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('warehouse/list'); ?>">Nhập hàng vào kho</a></li>
								<li class="">
									<a href="<?php echo base_url('warehouse/list'); ?>">Danh sách sản phẩm trong kho</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Thống kê</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('revenue'); ?>">Xem thống kê</a></li>
								<li class="">
									<a href="<?php echo base_url('revenue'); ?>">Thống kê doanh thu</a>
								</li>
								<li class="">
									<a href="<?php echo base_url('revenue'); ?>">Thống kê lợi nhuận</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="index.html"
								><i class="fa fa-th-large"></i>
								<span class="nav-label">Quản lý bình luận</span>
								<span class="fa arrow"></span
							></a>
	
							<ul class="nav nav-second-level">
								<li><a href="<?php echo base_url('comment'); ?>">Danh sách bình luận</a></li>
				
							</ul>
						</li>
				
					</ul>
				</div>
			</nav>