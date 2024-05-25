import React, { useState } from 'react';
import axios from 'axios';
import './assets/styles/LoginForm.css';
import { useNavigate } from "react-router-dom";

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
            setError('Error occurred. Please try again.');
        }
    };

    return (
        <div className="form-container col-5">
            <div className="form-group">
                <form onSubmit={handleSubmit} id="register-form">
                    {error && <div className="error">{error}</div>}

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
                        required
                    />

                    <div className="submit-button-container">
                        <button type="submit" className="btn btn-success">Register</button>
                    </div>
                </form>
            </div>
            <div className='messages2'>
                Already have an account? Click <a className='ml-1 click-here' href="/">here</a>
            </div>
        </div>
    );
}

export default RegisterForm;
