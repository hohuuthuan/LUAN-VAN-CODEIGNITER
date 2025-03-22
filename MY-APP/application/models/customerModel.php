<?php
class customerModel extends CI_Model
{
    public function selectCustomer()
    {
        // $this->db->where('role_id', 2);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function selectCustomerById($UserID)
    {
        $query = $this->db->get_where('users', ['UserID' => $UserID]);
        return $query->row();
    }

    public function updateCustomer($UserID, $data)
    {
        return $this->db->update('users', $data, ['UserID' => $UserID]);
    }


    public function getCustomerByEmailAndPhone($email, $phone){
        $query = $this->db->get_where('users', ['Email'=> $email,'Phone'=> $phone, 'Role_ID' => 2]);
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
                $this->db->where('role_id', 2);
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
                $this->db->where('role_id', 2);
                return $this->db->update('users', $update_data);
            } else {
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



    public function deleteCustomer($UserID)
    {
        return $this->db->delete('users', ['UserID' => $UserID]);
    }




}

?>