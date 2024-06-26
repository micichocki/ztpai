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
import ReportIssueForm from './ReportComponent'
import AdminPanel from './AdminPanel'
import EditRecipeForm from './RecipeEdit'
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
          <Route path="/report" element={<ReportIssueForm setIsAuthenticated={setIsAuthenticated} />} />
          <Route path="/admin" element={<AdminPanel setIsAuthenticated={setIsAuthenticated} />} />
          <Route path="/edit-recipe/:id" element={<EditRecipeForm />} />
        </Routes>
      </div>
    </Router>
  );
}

function Register() {
  return (
      <RegisterForm />
  );
}



function Login() {
  return (
        <LoginForm />
  );
}


export default App;
