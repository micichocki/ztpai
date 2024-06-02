import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

const useAuth = () => {
  const navigate = useNavigate();

  useEffect(() => {
    const verifyToken = async () => {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          navigate('/');
          return;
        }

        const response = await axios.post('https://localhost:8000/jwt_verify', { token });
        const { valid, user_id, user_role, user_credentials, email } = response.data;
        localStorage.setItem("user_id", user_id);
        if (!valid) {
          navigate('/');
        }
      } catch (error) {
        console.error('Error verifying token:', error);
        navigate('/');
      }
    };

    verifyToken();
  }, [navigate]);
};

export default useAuth;
