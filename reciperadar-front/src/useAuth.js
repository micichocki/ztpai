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
        localStorage.setItem("user_role", JSON.stringify(user_role)); 
        localStorage.setItem("email", email);
        localStorage.setItem("name", user_credentials.name);
        localStorage.setItem("surname", user_credentials.surname);
        localStorage.setItem("uc_id", user_credentials.id);
        localStorage.setItem("followed_recipes", JSON.stringify(user_credentials.followed_recipes)); 
        console.log(user_credentials.followers_count)
        localStorage.setItem("followers_count", JSON.stringify(user_credentials.followers_count)); 
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
