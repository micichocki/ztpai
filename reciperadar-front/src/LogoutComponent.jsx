import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

function Logout({ setIsAuthenticated }) {
  console.log("Logging out")
  const navigate = useNavigate();

  useEffect(() => {
    const handleLogout = async () => {
      try {      
        localStorage.removeItem('token');
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
