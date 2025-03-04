<?php
class customerModel extends CI_Model
{
    public function selectCustomer()
    {
        // $this->db->where('role_id', 2);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function selectCustomerById($id)
    {
        $query = $this->db->get_where('users', ['id' => $id]);
        return $query->row();
    }

    public function updateCustomer($id, $data)
    {
        return $this->db->update('users', $data, ['id' => $id]);
    }


    public function getCustomerByEmailAndPhone($email, $phone){
        $query = $this->db->get_where('users', ['email'=> $email,'phone'=> $phone, 'role_id' => 2]);
        return $query->row();
    }


    public function getCustomerByEmail($email){
        $query = $this->db->get_where('users', ['email'=> $email, 'role_id' => 2]);
        return $query->row();
    }

        public function updateCustomerForgotPassword($email, $phone, $update_data) {
            // Kiểm tra dữ liệu đầu vào
            if (is_array($update_data)) {
                $this->db->where('email', $email);
                $this->db->or_where('phone', $phone);
                $this->db->where('role_id', 2); // Hoặc giá trị role_id thích hợp
                return $this->db->update('users', $update_data);
            } else {
                // Ghi log hoặc xử lý lỗi nếu update_data không phải là mảng
                log_message('error', 'update_data không phải là mảng.');
                return false;
            }
        }
    
        public function updateCustomerChangePassword($email, $phone, $update_data) {
            // Kiểm tra dữ liệu đầu vào
            if (is_array($update_data)) {
                $this->db->where('email', $email);
                $this->db->or_where('phone', $phone);
                $this->db->where('role_id', 2); // Hoặc giá trị role_id thích hợp
                return $this->db->update('users', $update_data);
            } else {
                // Ghi log hoặc xử lý lỗi nếu update_data không phải là mảng
                log_message('error', 'update_data không phải là mảng.');
                return false;
            }
        }


    public function updateTokenCustomer($update_data, $email, $phone)
    {
        $this->db->where('email', $email);
        $this->db->or_where('phone', $phone);
        $this->db->where('role_id', 2);
        return $this->db->update('users', $update_data);
    }

    
   


    public function deleteCustomer($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }




}

?>