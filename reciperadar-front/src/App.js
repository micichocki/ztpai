import 'bootstrap/dist/css/bootstrap.css';
import React from 'react';
import { useState, useEffect } from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import './assets/styles/App.css';
import Navbar from './Navbar';
import LoginForm from './LoginForm';
import RegisterForm from './RegisterForm';
import DashboardComponent from './DashboardComponent';
import RecipeDetail from './RecipeDetail';
import RecipeAddComponent from './RecipeAddComponent'
import Logout from './LogoutComponent';
import Profile from './ProfileComponent';
import 'jquery';
import 'popper.js'; 

function App() {
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('token');
    const userAuthenticated = !!token; 
    setIsAuthenticated(userAuthenticated);
  }, []);

  return (
    <Router>
      <div>
        <Navbar isAuthenticated={isAuthenticated} />
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/dashboard" element={<DashboardComponent isAuthenticated={isAuthenticated} setIsAuthenticated={setIsAuthenticated} />} />
          <Route path="/recipe/:id" element={<RecipeDetail isAuthenticated={isAuthenticated} />} />
          <Route path="/add-recipe" element={<RecipeAddComponent isAuthenticated={isAuthenticated} />} />
          <Route path="/logout" element={<Logout setIsAuthenticated={setIsAuthenticated} />} />
          <Route path="/profile/:id" element={<Profile setIsAuthenticated={setIsAuthenticated} />} />
        </Routes>
      </div>
    </Router>
  );
}

function Register() {
  return (
    <div className="d-flex justify-content-center align-items-center vh-100 ">
      <RegisterForm />
    </div>
  );
}



function Login() {
  return (
      <div className="d-flex justify-content-center align-items-center vh-100 ">
        <LoginForm />
      </div>

  );
}


export default App;
