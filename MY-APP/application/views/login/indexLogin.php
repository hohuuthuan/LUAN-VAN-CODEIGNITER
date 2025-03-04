<div class="container">
  <?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
  <?php } elseif ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
  <?php } ?>
  <h2>Login admin</h2>
  <form action="<?php echo base_url('login-admin') ?>" method="POST">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
      <?php echo form_error('email'); ?>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
      <?php echo form_error('password'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
    <a href="<?php echo base_url('register-admin') ?>" class="btn btn-primary">Đăng ký admin</a>
  </form>
</div>