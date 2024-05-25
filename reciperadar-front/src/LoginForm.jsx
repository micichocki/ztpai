import React, { useState } from 'react';
import axios from './axiosConfig'; 
import './assets/styles/LoginForm.css';
import { useNavigate } from "react-router-dom";

function LoginForm() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (event) => {
        event.preventDefault();
        
        try {
            const response = await axios.post('https://localhost:8000/api/login_check', {
                username,
                password
            });

            if (response.status === 200) {
                const token = response.data.token;
                console.log()
                if (token) {
                    const token = response.data.token;
                    localStorage.setItem("token", token);
                    
                    console.log('Login successful');
                    navigate('/dashboard'); 
                } else {
                    setError('Token not found in response');
                }
            } else {
                setError('Login failed');
            }
        } catch (error) {
            console.error('Error occurred:', error);
            setError('Please provide correct credentials');
        }
    };

    return (
        <div className="form-container col-5">
            <div className="form-group">
                <form onSubmit={handleSubmit} id="main-form">
                    {error && <div className="error">{error}</div>}

                    <label htmlFor="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="username"
                        value={username}
                        onChange={(e) => setUsername(e.target.value)}
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

                    <div className="submit-button-container">
                        <button type="submit" className="btn btn-success">Sign In</button>
                    </div>
                </form>
            </div>
            <div className='messages2'>
                Don't have an account? Click <a className='ml-1 click-here' href="/register">here</a>
            </div>
        </div>
    );
}

export default LoginForm;
