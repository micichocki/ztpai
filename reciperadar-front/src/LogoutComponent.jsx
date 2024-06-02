import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

function Logout({ setIsAuthenticated }) {
  console.log("Logging out")
  const navigate = useNavigate();

  useEffect(() => {
    const handleLogout = async () => {
      try {      
        localStorage.removeItem('token');
        localStorage.removeItem('user_id');
        localStorage.removeItem('user_role');
        localStorage.removeItem('followed_recipes');
        localStorage.removeItem('uc_id');
        localStorage.removeItem('name');
        localStorage.removeItem('surname');
        setIsAuthenticated(false);

        navigate('/');
      } catch (error) {
        console.error('Error logging out:', error);
      }
    };

    handleLogout();
  }, [navigate, setIsAuthenticated]);

  return <div>Logging out...</div>;
}

export default Logout;
