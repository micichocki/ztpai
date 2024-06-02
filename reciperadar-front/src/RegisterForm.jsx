import React, { useState } from 'react';
import axios from 'axios';
import './assets/styles/LoginForm.css';
import { useNavigate } from "react-router-dom";
import kitchenAppliancesImage from './assets/img/Kitchen-appliances-bro.svg';


function RegisterForm() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (event) => {
        event.preventDefault();
        const uninterceptedAxiosInstance = axios.create();
        try {
            const response = await uninterceptedAxiosInstance.post('https://localhost:8000/api/register', {
                email,
                password,
                confirmPassword 
            });
            
            if (response.status === 200) { 
                console.log('Registration successful');
                navigate('/'); 
            } else {
                setError('Registration failed');
            }
        } catch (error) {
            console.error('Error occurred:', error);
            if (error.response && error.response.data && error.response.data.error) {
                setError(error.response.data.error); 
            } else {
                setError('Error occurred. Please try again.');
            }
        }
    };

    return (
        <div className="container">
            
          <div className="form-container">
          {error && 
                  <div className="alert alert-danger mt-1 alert-login" role="alert">
                    {error}
                  </div>
                }
            <div className="form-group">
              <form onSubmit={handleSubmit} id="main-form">
                    <label htmlFor="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />

                    <label htmlFor="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                     <label htmlFor="confirmPassword">Confirm Password</label>
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"

                        value={confirmPassword}
                        onChange={(e) => setConfirmPassword(e.target.value)}                
                    />

<div className="submit-button-container">
                  <button type="submit" className="btn btn-success">Sign In</button>
                </div>
              </form>
            </div>
            <div className="messages2">
              You already have an account? Click <a className="ml-1 click-here" href="/">here</a>
            </div>
          </div>
          <div className="image-text-container">
            <img src={kitchenAppliancesImage} alt="Kitchen appliances image" />
            <p className='jomh big-font'>Welcome to our culinary recipe
                                                    app!        </p>
            <p className='jomh'>Step into the world of culinary adventures to begin your journey towards better tasting!
</p>
          </div>
        </div>
    );
}

export default RegisterForm;
