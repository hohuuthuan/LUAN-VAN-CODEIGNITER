<section id="form"><!--form-->
		<div class="container">
			<?php if($this->session->flashdata('success')) { ?>
				<div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
			<?php } elseif($this->session->flashdata('error')) { ?>
				<div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
			<?php } ?>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form action="<?php echo base_url('login-customer')?>" method="POST">
							<input type="email" name="email" placeholder="Email" />
							<?php echo form_error('email'); ?>
							<input type="password" name="password" placeholder="Enter Password" />
							<?php echo form_error('password'); ?>
							<div style="display: flex; align-items: center;" class="">
							<button type="submit" class="btn btn-default">Login</button>
							<a style="margin-left: 10px; margin-top: 20px" href="<?php echo base_url('forgot-password-layout')?>"><u>Quên mật khẩu</u></a>
							</div>
						</form>
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>New User Signup!</h2>
						<form action="<?php echo base_url('dang-ky')?>" method="POST">
							<input type="text" name="username" placeholder="Name"/>
							<?php echo form_error('username'); ?>
							<input type="text" name="phone" placeholder="Phone"/>
							<?php echo form_error('phone'); ?>
							<input type="text" name="address" placeholder="Address"/>
							<?php echo form_error('address'); ?>
							<input type="email" name="email" placeholder="Email Address"/>
							<?php echo form_error('email'); ?>
							<input type="password" name="password" placeholder="Password"/>
							<?php echo form_error('password'); ?>
							<div style="display: flex;">
								<button type="submit" class="btn btn-default">Signup</button>
								
							</div>
							
						</form>
						
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->