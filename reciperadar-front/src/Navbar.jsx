import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import './assets/styles/Navbar.css';

function Navbar({ isAuthenticated }) {
  const userId = localStorage.getItem("userId");
  const name = localStorage.getItem("name") || '';

  const [isNavCollapsed, setIsNavCollapsed] = useState(true);

  const handleNavCollapse = () => setIsNavCollapsed(!isNavCollapsed);

  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <Link className="navbar-brand website-name" to="/dashboard">RecipeRadar</Link>
      <button className="navbar-toggler" type="button" onClick={handleNavCollapse}>
        <span className="navbar-toggler-icon"></span>
      </button>
      <div className={`${isNavCollapsed ? 'collapse' : ''} navbar-collapse`} id="navbarNav">
        <ul className={`navbar-nav ml-auto ${isNavCollapsed ? 'mr-3' : ''}`}>
          {isAuthenticated && (
            <li className="nav-item">
              <Link className="nav-link nav-text" to={`/profile/${userId}`}>Profile</Link>
            </li>
          )}
          {isAuthenticated && (
            <li className="nav-item">
              <Link className="nav-link nav-text" to="/logout">Logout</Link>
            </li>
          )}
            {isAuthenticated && (
            <li className="nav-item">
              <Link className="nav-link nav-text" to="/report">Report</Link>
            </li>
          )}
          <li className="nav-item">
            <a className="nav-link nav-text" href="https://github.com/micichocki">Contact</a>
          </li>
          {isNavCollapsed && name && (
            <li className="ml-2 nav-item">
              <span className="nav-link nav-text welcome-indicator">
                Hi, <span className="color-span">{name}</span>
              </span>
            </li>
          )}
        </ul>
      </div>
    </nav>
  );
}

export default Navbar;
