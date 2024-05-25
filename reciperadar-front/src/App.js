import 'bootstrap/dist/css/bootstrap.css';
import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import './assets/styles/App.css';
import Navbar from './Navbar';
import LoginForm from './LoginForm';
import RegisterForm from './RegisterForm';
import Home from './Home';
import Recipe from './Recipe';


function App() {
  return (
    <Router>
      <div>
        <Navbar />
        <Routes>
          <Route path="/home" element={<Home />} />
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/dashboard" element={<Recipe />} />   
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
