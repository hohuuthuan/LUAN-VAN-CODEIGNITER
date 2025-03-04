<div class="container">
  <?php if($this->session->flashdata('success')) { ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
  <?php } elseif($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
  <?php } ?>
  <h2>Đăng ký ADMIN</h2>
  <form action="<?php echo base_url('register-admin-submit') ?>" method="POST">
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
      <?php echo form_error('username'); ?>
    </div>
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
    <button type="submit" class="btn btn-primary">Đăng ký</button>
  </form>
</div>
